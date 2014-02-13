<?php

/*
**************************************************************************************************************************
** CORAL Usage Statistics Module v. 1.1
**
** Copyright (c) 2010 University of Notre Dame
**
** This file is part of CORAL.
**
** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
**
** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
**
**************************************************************************************************************************
*/

class SushiService extends DatabaseObject {

	protected function defineRelationships() {}

	protected function overridePrimaryKeyName() {}

	public function getByPlatformID($platformID){
		if (isset($platformID)) {
			$query = "SELECT * FROM `$this->tableName` WHERE `platformID` = '$platformID'";
			$result = $this->db->processQuery($query, 'assoc');

			foreach (array_keys($result) as $attributeName) {
				$this->addAttribute($attributeName);
				$this->attributes[$attributeName] = $result[$attributeName];
			}
		}
	}



	public function getByPublisherPlatformID($publisherPlatformID){
		if (isset($publisherPlatformID)) {
			$query = "SELECT * FROM `$this->tableName` WHERE `publisherPlatformID` = '$publisherPlatformID'";
			$result = $this->db->processQuery($query, 'assoc');

			foreach (array_keys($result) as $attributeName) {
				$this->addAttribute($attributeName);
				$this->attributes[$attributeName] = $result[$attributeName];
			}
		}
	}

	public function getPublisherOrPlatform(){
		if (($this->platformID != "") && ($this->platformID > 0)){
			return new Platform(new NamedArguments(array('primaryKey' => $this->platformID)));
		}else{
			return new PublisherPlatform(new NamedArguments(array('primaryKey' => $this->publisherPlatformID)));
		}

	}


	public function unscheduledImports(){
		$query = "SELECT platformID, publisherPlatformID, sushiServiceID, serviceURL, reportLayouts, releaseNumber
		FROM `$this->tableName`
		WHERE serviceDayOfMonth = '' OR serviceDayOfMonth = '0' OR serviceDayOfMonth is NULL
		ORDER BY 4";

		$result = $this->db->processQuery(stripslashes($query), 'assoc');

		$searchArray = array();
		$importArray = array();

		//need to do this since it could be that there's only one result and this is how the dbservice returns result
		if (isset($result['platformID'])){

			foreach (array_keys($result) as $attributeName) {
				$resultArray[$attributeName] = $result[$attributeName];
			}

			array_push($importArray, $resultArray);
		}else{
			foreach ($result as $row) {
				$resultArray = array();
				foreach (array_keys($row) as $attributeName) {
					$resultArray[$attributeName] = $row[$attributeName];
				}
				array_push($importArray, $resultArray);
			}
		}

		return $importArray;

	}



	public function upcomingImports(){
		$query = "SELECT platformID, publisherPlatformID, sushiServiceID, serviceURL, reportLayouts, releaseNumber,
		if(serviceDayOfMonth > day(now()), str_to_date(concat(EXTRACT(YEAR_MONTH FROM NOW()), lpad(serviceDayOfMonth,2,'0')), '%Y%m%d'), str_to_date(concat(EXTRACT(YEAR_MONTH FROM NOW()) + 1, lpad(serviceDayOfMonth,2,'0')), '%Y%m%d') ) next_import
		FROM `$this->tableName`
		WHERE serviceDayOfMonth > 0
		ORDER BY 4";

		$result = $this->db->processQuery(stripslashes($query), 'assoc');

		$searchArray = array();
		$importArray = array();

		//need to do this since it could be that there's only one result and this is how the dbservice returns result
		if (isset($result['platformID'])){

			foreach (array_keys($result) as $attributeName) {
				$resultArray[$attributeName] = $result[$attributeName];
			}

			array_push($importArray, $resultArray);
		}else{
			foreach ($result as $row) {
				$resultArray = array();
				foreach (array_keys($row) as $attributeName) {
					$resultArray[$attributeName] = $row[$attributeName];
				}
				array_push($importArray, $resultArray);
			}
		}

		return $importArray;

	}


	public function runAll(){
		$reportLayouts = $this->reportLayouts;
		$rlArray = explode(";", $reportLayouts);

		foreach($rlArray as $reportLayout){
			$xmlFile = $this->sushiTransfer($reportLayout);

			$this->parseXML($xmlFile, $reportLayout);

		}

		if ($reportLayouts == ""){
			echo "At least one report type must be set up!";
		}

	}

	private function sushiTransfer($reportLayout){

		date_default_timezone_set('UTC');
		$yyyy = date("Y");
		$ppObj = $this->getPublisherOrPlatform();

		//if report layout is BR and Release is 3, change it to 1
		if((preg_match('/BR/i', $reportLayout)) && ($this->releaseNumber == "3")){
			$releaseNumber = '1';
		}else{
			$releaseNumber = $this->releaseNumber;
		}
			



		if (($this->wsdlURL == '') || (strtoupper($this->wsdlURL) == 'COUNTER')){
			if ($this->releaseNumber == "4"){
				$wsdl = 'http://www.niso.org/schemas/sushi/counter_sushi4_0.wsdl';
			}else{
				$wsdl = 'http://www.niso.org/schemas/sushi/counter_sushi3_0.wsdl';
			}
		}else{
			$wsdl=$this->wsdlURL;
		}

		$serviceProvider = str_replace('"','',$ppObj->reportDisplayName);

		// always do start of this year till last month
  		$endDate = date_create_from_format("Ymd", date("Y") . date("m") . "01" );
  		date_sub($endDate, date_interval_create_from_date_string('1 days'));

  		$startDate = date_format($endDate, "Y") . "-01-01";
  		$endDate = date_format($endDate,"Y-m-d");

		$createDate = date("Y-m-d\TH:i:s.0\Z");
		$id = uniqid("CORAL:", true);

		// look at $Security to ses if it uses an extension
		if(preg_match('/Extension=/i', $this->security)){
		  $extensions = array();
		  $varlist = explode(";", $this->security);
		  foreach( $varlist as $params){
		    list($extVar, $extVal) = explode("=", $params);
		    $extensions[$extVar] = $extVal;
		    if ($extVar == 'Extension'){
		      $extension = $extVal;
		    }
		  }
		}

		if (!empty($extension)){
		  include BASE_DIR . 'sushiincludes/extension_'.$extension.'.inc.php';
		}else{
		  if (preg_match("/http/i", $this->security)){
		    try{
		      $client = new SoapClient($wsdl,
		      	array(
		          'login'          => $this->login,
		          'password'       => $this->password,
		          'location' 		=> $this->serviceURL,
		          "trace"      => 1,
		          "exceptions" => 1,
		        )
		      );
		    }catch (Exception $e){
		      $error = $e->__toString();
		      echo "Failed to connect to $serviceProvider via http with login and password: " . $error . "<br />Trying: " . var_dump($client);
		      exit();
		    }
		  }else{
			if ((strtoupper($this->wsdlURL) != 'COUNTER') && ($this->wsdlURL != '')){
		      try{
		        $client = new SoapClient($wsdl,array(
		        "trace"      => 1,
		        "exceptions" => 1));
		      }catch (Exception $e){
		        $error = $e->__toString();
		        echo "Failed to connect to $serviceProvider via wsdl: " . $error . "<br />Trying: " . $wsdl;
		        exit();
		      }
		    }else{
		      try{
		        $client = new SoapClient($wsdl,array(
		        'location' => $this->serviceURL,
		        "trace"      => 1,
		        "exceptions" => 1));
		      }catch (Exception $e){
		        $error = $e->__toString();
		        echo "Failed to connect to $serviceProvider: " . $error . "<br />Trying: " . var_dump($client);
		        exit();
		      }
		    }
		  }
		}

		if (preg_match("/wsse/i", $security)){
		    // Prepare SoapHeader parameters
		    $strWSSENS = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd";
		    $objSoapVarUser = new SoapVar($this->login, XSD_STRING, NULL, $strWSSENS, NULL, $strWSSENS);
		    $objSoapVarPass = new SoapVar($this->password, XSD_STRING, NULL, $strWSSENS, NULL, $strWSSENS);
		    $objWSSEAuth = new clsWSSEAuth($objSoapVarUser, $objSoapVarPass);
		    $objSoapVarWSSEAuth = new SoapVar($objWSSEAuth, SOAP_ENC_OBJECT, NULL, $strWSSENS, 'UsernameToken', $strWSSENS);
		    $objWSSEToken = new clsWSSEToken($objSoapVarWSSEAuth);
		    $objSoapVarWSSEToken = new SoapVar($objWSSEToken, SOAP_ENC_OBJECT, NULL, $strWSSENS, 'UsernameToken', $strWSSENS);
		    $objSoapVarHeaderVal=new SoapVar($objSoapVarWSSEToken, SOAP_ENC_OBJECT, NULL, $strWSSENS, 'Security', $strWSSENS);
		    $objSoapVarWSSEHeader = new SoapHeader($strWSSENS, 'Security', $objSoapVarHeaderVal,false);

		    // Prepare Soap Client
		    try{
		      $client->__setSoapHeaders(array($objSoapVarWSSEHeader));
		    }catch (Exception $e){
		      $error = $e->getMessage();
		      echo "Failed to connect to $serviceProvider: " . $error . "<br />Trying: " . var_dump($client);
		    }

		}

		try{
		    $result = $client->GetReport(array
		                     ('Requestor' => array
		                       ('ID' => $this->requestorID,
		                         'Name' => $user->loginID,
		                         'Email' => 'sushi@niso.org'
		                       ),
		                       'CustomerReference' => array
		                       ('ID' => $this->customerID,
		                         'Name' => $user->loginID
		                       ),
		                       'ReportDefinition'  => array
		                       ('Filters' => array
		                         ('UsageDateRange' => array
		                           ('Begin' => $startDate,
		                             'End' => $endDate
		                           )
		                         ),
		                         'Name' => $reportLayout,
		                         'Release' => $releaseNumber
		                       ),
		                       'Created' => $createDate,
		                       'ID' => $id,
		                       'connection_timeout' => 1000
		                     )

		        );
		}catch(Exception $e){
		    $error = $e->__toString();
		    echo "Failed to pull report from $serviceProvider: " . $error;
		    echo $client->__getLastRequest();
		    exit();
		}

		$xml = $client->__getLastResponse();

		$fname = $serviceProvider.'_'.$reportLayout.'_'.$startDate.'_'.$endDate.'.xml';
		$replace="_";
		$pattern="/([[:alnum:]_\.-]*)/";
		$fname = 'sushistore/' . str_replace(str_split(preg_replace($pattern,$replace,$fname)),$replace,$fname);
		
		$xmlFileName = BASE_DIR . $fname;
		file_put_contents($xmlFileName, $xml);


		return $fname;
		
		$log .= "\n Report saved: $fname";
		$log .= "\n $reportLayout retrieved from $serviceProvider for the period from $startDate to $endDate";

	}



	private function parseXML($fName, $reportLayout){


		//////////////////////////////////////
		//PARSE XML!!
		//////////////////////////////////////

		$xmlFileName = BASE_DIR . $fName;
		$log .= "XML File Name: " . $fName . "\n";

		//read layouts ini file to get the available layouts
		$layoutsArray = parse_ini_file(BASE_DIR . "layouts.ini", true);
		$layoutColumns = array();

		$reader = new XMLReader();
		if (!$reader->open($xmlFileName)) {
			$log .= "Unable to read " . $xmlFileName . "\n";
    		die("Failed to open " . $xmlFileName);
		}


		$layoutCode = "";
		$countArray = array();

		while ($reader->read()) {
			//First - get report information
			if (($reader->nodeType == XMLReader::ELEMENT) && ($reader->localName == 'Report') && (count($layoutColumns) == '0')) {
				$name = $reader->getAttribute("Name");
				$version = $reader->getAttribute("Version");

				$layoutCode = $name;	

				if (($version == "3") || ($version =="4")){
					$version = "R" . $version;
				}
				if ($version != ''){
					$layoutCode .= "_" . $version;		
				}else{
					$layoutCode .= "_R" . $this->releaseNumber; 
				}

				//At this point, determine the format of the report to port to csv from the layouts.ini file
				$layoutKey = $layoutsArray[ReportTypes][$layoutCode];
	  			$layoutColumns = $layoutsArray[$layoutKey]['columns'];


	  			//if this way of determining layout was unsuccessful, just use the layout sent in
	  			if (count($layoutColumns) == "0"){
	  				$layoutCode = $reportLayout . "_R" . $this->releaseNumber;

					$layoutKey = $layoutsArray[ReportTypes][$layoutCode];
		  			$layoutColumns = $layoutsArray[$layoutKey]['columns'];
	  			}
				
				$log .= "Layout: " . $layoutCode . "\n";
				

			}

			if (($reader->nodeType == XMLReader::ELEMENT) && ($reader->localName == 'ReportItems')) {
				if ((count($layoutColumns) == '0') || ($layoutCode == '')){
					echo "Exiting process.  Unable to determine report layout!  <br /><br />Log: " . nl2br($log);
	  				exit;
	  			}

				//reset variables
				$identifierArray=array();
				$reportArray = array();

				//loop through each element under "Item"
		        while ($reader->read()) {

		        	//get the element name
		          	if ($reader->nodeType == XMLReader::ELEMENT){
		          		$elementName = trim($reader->localName);

						//move to next to get the text
				         if (($elementName != "Instance") &&($elementName != "ItemIdentifier")){
				         	$reader->read();		          		
				         }

			            if ($reader->nodeType == XMLReader::TEXT
			              || $reader->nodeType == XMLReader::CDATA
			              || $reader->nodeType == XMLReader::WHITESPACE
			              || $reader->nodeType == XMLReader::SIGNIFICANT_WHITESPACE) {
			              	$elementValue = trim($reader->value);

		          		
		          			switch ($elementName) {
		          				case 'ItemPlatform':
					               	$reportArray['platform'] = $elementValue;
		          					break;
		          				case 'ItemPublisher':
		          					$reportArray['publisher'] = $elementValue;
		          					break;
		          				case 'ItemName':
		          					$reportArray['title'] = $elementValue;
		          					break;
		          				case 'ActivityType':
		          					$reportArray['activityType'] = strtoupper($reader->value);
		          					break;
		          				case 'Type':
		          					$idType = strtoupper($reader->value);
		          					break;
		          				case 'Value':
		          					$identifierArray[$idType] = $reader->value;
		          					break;
		          				case 'Begin':
		          					$totalCountsArray[$m] = $countArray;

		          					$date = new DateTime($reader->value);
									$m = strtolower($date->format('M'));
									$y = strtolower($date->format('Y'));

									$countArray = array();
		          					break;
		          				case 'MetricType':
		          					$metricType = strtoupper($reader->value);

		          					//make sure metric types have conformity
		          					if (!(strpos($metricType,'HTML') === false)){
		          						$metricType ='html';
		          					}else if (!(strpos($metricType,'PDF') === false)){
		          						$metricType ='pdf';
		          					}else{
		          						$metricType ='ytd';
		          					}
		          					break;
		          				case 'Count':
		          					$countArray[$metricType] = $reader->value;
		          					
		          					break;
		          			}
		          		

			          	}

			        //Finished parsing the Title!!!  	
			        }else if ($reader->nodeType == XMLReader::END_ELEMENT
		              && $reader->localName == "ReportItems") {

			        	foreach($identifierArray as $key => $value){
			        		if (!(strrpos($key,'PRINT') === false) && !(strrpos($key,'ISSN') === false)){
			        			$reportArray['issn'] = $value;
			        		}else if (!(strrpos($key,'ONLINE') === false) && !(strrpos($key,'ISSN') === false)){
			        			$reportArray['eissn'] = $value;
			        		}else if (!(strpos($key,'PRINT') === false) && !(strpos($key,'ISBN') === false)){
			        			$reportArray['isbn'] = $value;
			        		}else if (!(strpos($key,'ONLINE') === false) && !(strpos($key,'ISBN') === false)){
			        			$reportArray['eisbn'] = $value;
			        		}else if (!(strpos($key,'DOI') === false)){
			        			$reportArray['doi'] = $value;
			        		}else if (!(strpos($key,'PROPRIETARY') === false)){
			        			$reportArray['pi']=$value;
			        		}

			        	}

						//get the last array into the totals array
      					$totalCountsArray[$m] = $countArray;

      					//now figure out the months and the ytd, etc totals			   
      					foreach ($totalCountsArray as $key => $countArray){
      						if ($key != ''){
      							$reportArray[$key] = intval($countArray['ytd']);

      							$reportArray['ytd'] += intval($countArray['ytd']);
      							$reportArray['ytdPDF'] += intval($countArray['pdf']);
      							$reportArray['ytdHTML'] += intval($countArray['html']);
      						}

      					}


      					//Now look at the report's layoutcode's columns to order them properly
      					$finalArray=array();
      					foreach($layoutColumns as $colName){
      						$finalArray[] = $reportArray[$colName];
      					}

      					$txtOut .= implode($finalArray,"\t") . "\n";

						$totalCountsArray=array();
						break;
		            }
		        } 
            }

   		}

   		$reader->close();

   		if (($layoutKey == "") || (count($layoutColumns) == '0') || ($txtOut == "")){
			if (file_exists($xmlFileName)) {					
				$log .= "The SUSHI transfer was successful but the XML parsing did not complete or no data was found.\n\n";
			
				$xml = simplexml_load_file($xmlFileName);
				echo nl2br($log);
				echo "The following is the XML response:<br />";

				echo nl2br(htmlspecialchars(str_replace("</","\n",file_get_contents($xmlFileName))));

			}else{
				echo "The XML file does not exist.  Please verify you have write permissions on 'sushistore'.<br /><br />";
				echo nl2br($log);

			}

			exit;
		}


		#Save final text delimited "file" and log output on server
		$txtFile = 'archive/' . date('Ymdhi') . '.txt';
		$fp = fopen(BASE_DIR . $txtFile, 'w');
		fwrite($fp, $txtOut);
		fclose($fp);

		$log .= "Archive/Text File Name: " . $txtFile . "\n";


		//save to import log!!
		$importLog = new ImportLog();
		$importLog->loginID = "sushi";
		$importLog->layoutCode = $layoutCode;
		$importLog->fileName = $txtFile;
		$importLog->archiveFileURL = $fname; //came from top part
		$importLog->logFileURL = '';
		$importLog->details = $log;

		try {
			$importLog->save();
			$importLogID = $importLog->primaryKey;
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		//made it this far
		echo "Processing Complete for " . $layoutCode . ".  Please find import file under Queue.<br />";


		$importLogPlatformLink = new ImportLogPlatformLink();
		$importLogPlatformLink->importLogID = $importLogID;
		$importLogPlatformLink->platformID = $this->platformID;


		try {
			$importLogPlatformLink->save();
		} catch (Exception $e) {
			echo $e->getMessage();
		}


	}

}


//for soap headers
class clsWSSEAuth{
  private $username;
  private $password;
  function __construct($username, $password){
    $this->username=$username;
    $this->password=$password;
  }
}
class clsWSSEToken{
  private $usernameToken;
  function __construct ($innerVal){
    $this->usernameToken = $innerVal;
  }
}



?>
