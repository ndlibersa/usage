<?php
/*
**************************************************************************************************************************
** CORAL Usage Statistics Module v. 1.0
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


set_time_limit(300);

include_once 'directory.php';
include_once 'user.php';

$pageTitle = 'Upload Process Complete';

//read layouts ini file to get the layouts to map to columns in the database
$layoutsArray = parse_ini_file("layouts.ini", true);


$uploadedFile = $_POST['upFile'];
$orgFileName = $_POST['orgFileName'];
$archiveInd = $_POST['archiveInd'];
$overrideInd = $_POST['overrideInd'];
$printISSNArray = array();

$file_handle = fopen($uploadedFile, "r");

$topLogOutput = "";
$logOutput = "Process started on " . date('l jS \of F Y h:i A') . "<br />";
$logOutput.= "File: " . $uploadedFile . "<br /><br />";
$monthlyInsert='';
$screenOutput = '';

$startFlag = "N";
$formatFlag = "N";


//determine config settings for outlier usage
$config = new Configuration();
$outlier = array();

if ($config->settings->useOutliers == "Y"){

	$logOutput.="Outlier Parameters:<br />";

	$outliers = new Outlier();
	$outlierArray = array();

	foreach($outliers->allAsArray as $outlierArray) {

		$logOutput.="Level " . $outlierArray['outlierLevel'] . ": " . $outlierArray['overageCount'] . " over plus " .  $outlierArray['overagePercent'] . "% over <br />";

		$outlier[$outlierArray['outlierID']]['overageCount'] = $outlierArray['overageCount'];
		$outlier[$outlierArray['outlierID']]['overagePercent'] = $outlierArray['overagePercent'];
		$outlier[$outlierArray['outlierID']]['outlierLevel'] = $outlierArray['outlierLevel'];
	}

}

$logOutput .="<br /><br />";

//get column values from layouts array to determine layout - checking columns 2 through 20 (column 1 - Journal - is not always entered, column 7 - Feb - on may not be included)
for ($i = 2;$i <= 20;$i++){
	$columnCheck[$i] = $layoutsArray['layout1']['column' . $i];
}


//initialize some variables
$rownumber=0;
$holdPlatform = '';
$holdPublisher = '';
$holdPublisherPlatformID = '';
$holdYear = '';
$startMonth = '';

//loop through each line of file
while (!feof($file_handle)) {

	//get each line out of the file handler
 	$line = fgets($file_handle);

 	//check column formats if the format correct flag has not been set yet
	if ($formatFlag == "N"){
		$columnArray = explode("\t",$line);

	 	//strip spaces
	 	foreach ($columnArray as $key => $value){
			$columnArray[$key] = trim($value);
	 	}

	 	//this is the header column
	 	if ((strpos($columnArray[1],$columnCheck[2]) === 0) && (strpos($columnArray[2],$columnCheck[3]) === 0) && (strpos($columnArray[3],$columnCheck[4]) === 0) && (strpos($columnArray[4],$columnCheck[5]) === 0) && (strpos($columnArray[5],$columnCheck[6]) === 0) && (isset($columnArray[5])) ){
			$formatFlag = "Y";

			list ($month,$year) = preg_split("/[-\/. ]/",$columnArray[5]);
			if ($year < 100) $year = 2000 + $year;

			//loop through each month and the 3 ytd columns to determine which column number we should refer to when getting data below
			for ($i=5;$i<20;$i++){
				//convert to lowercase for comparison
				if (isset($columnArray[$i])){
					$columnArray[$i] = strtolower($columnArray[$i]);

					//strip the 'ytd ' since not all spreadsheets have it
					if (strpos($columnArray[$i], 'ytd ') === 0){
						$columnArray[$i] = substr($columnArray[$i], 4, strlen($columnArray[$i]));
					}

					//dont care about the year (after the "-")
					if (strpos($columnArray[$i],'-') > 0){
						$month=substr($columnArray[$i], 0, strpos($columnArray[$i],'-'));
					}elseif (strpos($columnArray[$i],' ') > 0){
						$month=substr($columnArray[$i], 0, strpos($columnArray[$i],' '));
					}else{
						$month=$columnArray[$i];
					}

					if ($month == 'jan'){$columnNumberArray['1']=$i;
					}elseif ($month == 'feb') {$columnNumberArray['2']=$i;
					}elseif ($month == 'mar') {$columnNumberArray['3']=$i;
					}elseif ($month == 'apr') {$columnNumberArray['4']=$i;
					}elseif ($month == 'may') {$columnNumberArray['5']=$i;
					}elseif ($month == 'jun') {$columnNumberArray['6']=$i;
					}elseif ($month == 'jul') {$columnNumberArray['7']=$i;
					}elseif ($month == 'aug') {$columnNumberArray['8']=$i;
					}elseif ($month == 'sep') {$columnNumberArray['9']=$i;
					}elseif ($month == 'oct') {$columnNumberArray['10']=$i;
					}elseif ($month == 'nov') {$columnNumberArray['11']=$i;
					}elseif ($month == 'dec') {$columnNumberArray['12']=$i;
					}elseif ($month == 'total') {$columnNumberArray['ytd']=$i;
					}elseif ($month == 'html') {$columnNumberArray['ytdHTML']=$i;
					}elseif ($month == 'pdf') {$columnNumberArray['ytdPDF']=$i;}

				}//end column isset

			//end for loop
			}

		//end if column checking
		}

	//end if format flag checked
	}



	//as long as the flags are set to print out then we can continue
	if (($startFlag == "Y")  && !(strpos($line,"\t") == "0")) {

		$lineArray = explode("\t",$line);
		$month = array();
		$journalTitle = $lineArray[$layoutsArray['layout1']['journal']];
		$platformName = $lineArray[$layoutsArray['layout1']['platform']];
		$publisherName = $lineArray[$layoutsArray['layout1']['publisher']];
		$printISSN = $lineArray[$layoutsArray['layout1']['printISSN']];
		$onlineISSN = $lineArray[$layoutsArray['layout1']['onlineISSN']];
		$month['1'] = $lineArray[$columnNumberArray['1']];
		if (isset($columnNumberArray['2'])) $month['2'] = $lineArray[$columnNumberArray['2']];
		if (isset($columnNumberArray['3'])) $month['3'] = $lineArray[$columnNumberArray['3']];
		if (isset($columnNumberArray['4'])) $month['4'] = $lineArray[$columnNumberArray['4']];
		if (isset($columnNumberArray['5'])) $month['5'] = $lineArray[$columnNumberArray['5']];
		if (isset($columnNumberArray['6'])) $month['6'] = $lineArray[$columnNumberArray['6']];
		if (isset($columnNumberArray['7'])) $month['7'] = $lineArray[$columnNumberArray['7']];
		if (isset($columnNumberArray['8'])) $month['8'] = $lineArray[$columnNumberArray['8']];
		if (isset($columnNumberArray['9'])) $month['9'] = $lineArray[$columnNumberArray['9']];
		if (isset($columnNumberArray['10'])) $month['10'] = $lineArray[$columnNumberArray['10']];
		if (isset($columnNumberArray['11'])) $month['11'] = $lineArray[$columnNumberArray['11']];
		if (isset($columnNumberArray['12'])) $month['12'] = $lineArray[$columnNumberArray['12']];
		if (isset($columnNumberArray['ytd'])) $ytd = $lineArray[$columnNumberArray['ytd']];
		if (isset($columnNumberArray['ytdHTML'])) $ytdHTML = $lineArray[$columnNumberArray['ytdHTML']];
		if (isset($columnNumberArray['ytdPDF'])) $ytdPDF = $lineArray[$columnNumberArray['ytdPDF']];


		################################################################
		// PLATFORM
		// Query to see if the Platform already exists, if so, get the ID
		#################################################################
		//check it against the previous row - no need to do another lookup if we've already figured out the platform
		//strip out double quotes
		$platformName = trim(str_replace ('"','',$platformName));

		if (!($platformID) || ($platformName != $holdPlatform)){
			//get the platformID if available
			$platformTestObj = new Platform();
			$platformObj = new Platform();
			$platformObj = $platformTestObj->getByName($platformName);

			if (is_object($platformObj)) $platformID = $platformObj->platformID;

			//Find the most recent month for this year / Platform that we have statistics for if override isn't set
			if (($platformID) && !($startMonth)){
				if ($overrideInd == 1){
					$logOutput .= "Override indicator set - all months will be imported.";
				}else{
					$monthArray = $platformObj->getTotalMonths($archiveInd, $year);
					$count_months = $monthArray['count_months'];
					$min_month = $monthArray['min_month'];
					$max_month = $monthArray['max_month'];


					if ($count_months == 12){
						$logOutput .= "Entire year already exists for this Platform / year.  No counts will be imported.";
						$startMonth = 13;
					}else if (($min_month == 1) && ($max_month < 13)) {
						$startMonth=$max_month + 1;
						$logOutput .= "Month Started at: " . $startMonth;
					}else if ($count_months == 0){
						$logOutput .= "No records exist for this Platform / year.  Import will start with month 1.";
					}else{
						$endMonth=$min_month-1;
						$logOutput .= "Partial year records exist for this Platform / year.  Import will start with month 1 and end with month $endMonth.";
					}

				}

			}
		}

		if (!isset($startMonth) || ($startMonth == '')){
			$startMonth = 1;
		}

		if (!isset($endMonth) || ($endMonth == '')){
			$endMonth = 12;
		}


		//For log output we only want to print the 	year once
		if ($year != $holdYear) {
			$logOutput .= "<br />Year: " . $year;
		}

		//If Platform does not already exist, insert it and get the new ID
		if (!$platformID && $platformName){

			$platform = new Platform();
			$platform->platformID = '';
			$platform->name = $platformName;
			$platform->reportDisplayName = $platformName;
			$platform->reportDropDownInd = '0';
			$platform->organizationID = '';

			try {
				$platform->save();
				$platformID = $platform->primaryKey;
			} catch (Exception $e) {
				echo $e->getMessage();
			}

			//also insert into Platform Note
			$platformNote = new PlatformNote();
			$platformNote->platformID = $platformID;
			$platformNote->startYear = date('Y');
			$platformNote->counterCompliantInd = '';

			try {
				$platformNote->save();
			} catch (Exception $e) {
				echo $e->getMessage();
			}


			#add to output on screen
			$screenOutput .= "<br /><b>New Platform set up: " . $platformName . "   <a href='publisherPlatform.php?platformID=" . $platformID . "'>edit</a></b>";


		}


		#################################################################
		// PUBLISHER
		// Query to see if the Publisher already exists, if so, get the ID
		#################################################################

		//check it against the previous row - no need to do another lookup if we've already figured out the platform
		//strip out double quotes
		$publisherName = trim(str_replace ('"','',$publisherName));

		if (!($publisherID) || ($publisherName != $holdPublisher)){
			//get the publisher object
			$publisherTestObj = new Publisher();
			$publisherObj = new Publisher();
			$publisherObj = $publisherTestObj->getByName($publisherName);
			if (is_object($publisherObj)) $publisherID = $publisherObj->publisherID;
		}



		//If it does not already exist, insert it and get the new ID
		if (($publisherID == '') && ($publisherName)){

			$publisher = new Publisher();
			$publisher->publisherID = '';
			$publisher->name = $publisherName;

			try {
				$publisher->save();
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			$publisherID = $publisher->primaryKey;
		}



		#################################################################
		// PUBLISHER / PLATFORM
		// Query to see if the Publisher / Platform already exists, if so, get the ID
		#################################################################
		//check it against the previous row - no need to do another lookup if we've already figured out the publisherplatformID
		if (!($publisherPlatformID) || ($publisherName != $holdPublisher) || ($platformName != $holdPlatform)){
			//get the publisher platform object
			$publisherPlatformTestObj = new PublisherPlatform();
			$publisherPlatformObj = $publisherPlatformTestObj->getPublisherPlatform($publisherID, $platformID);
			if (is_object($publisherPlatformObj)) $publisherPlatformID = $publisherPlatformObj->publisherPlatformID;
		}



		//If it does not already exist, insert it and get the new ID
		if (!$publisherPlatformID && $publisherID && $platformID){

			$publisherPlatform = new PublisherPlatform();
			$publisherPlatform->publisherPlatformID = '';
			$publisherPlatform->publisherID = $publisherID;
			$publisherPlatform->platformID = $platformID;
			$publisherPlatform->organizationID = '';
			$publisherPlatform->reportDropDownInd = '0';
			$publisherPlatform->reportDisplayName = $publisherName;

			try {
				$publisherPlatform->save();
			} catch (Exception $e) {
				echo $e->getMessage();
			}

			$publisherPlatformID = $publisherPlatform->primaryKey;


			#add to log output
			$logOutput .= "<br />New Publisher / Platform set up: " . $publisherName . " / " . $platformName;

		}


		#################################################################
		// TITLE
		// Query to see if the Title already exists, if so, get the ID
		#################################################################
		//first, remove the '-' from the ISSNs
		$printISSN = strtoupper(trim(str_replace ('-','',$printISSN)));
		//remove blank
		$printISSN = strtoupper(trim(str_replace (' ','',$printISSN)));
		if (strpos(strtoupper($printISSN),'N/A') !== false) $printISSN = '';
		if ($printISSN == '00000000') $printISSN = '';
		if (strtoupper($printISSN) == 'XXXXXXXX') $printISSN = '';

		$onlineISSN = strtoupper(trim(str_replace ('-','',$onlineISSN)));
		//remove blank
		$onlineISSN = strtoupper(trim(str_replace (' ','',$onlineISSN)));
		if (strpos(strtoupper($onlineISSN),'N/A') !== false) $onlineISSN = '';
		if ($onlineISSN == '00000000') $onlineISSN = '';
		if (strtoupper($onlineISSN) == 'XXXXXXXX') $onlineISSN = '';
		if (!$printISSN) { $searchISSN = trim($onlineISSN); }else{$searchISSN=trim($printISSN);}


		//strip everything after parenthesis from Title
		if (strpos($journalTitle,' (Subs') !== false) $journalTitle = substr($journalTitle,0,strpos($journalTitle,' (Subs'));
		if (strpos($journalTitle,'<BR>') !== false) $journalTitle = substr($journalTitle,0,strpos($journalTitle,'<BR>'));

		//strip out double quotes, escape single quotes and fix &
		$journalTitle = trim(str_replace ('"','',$journalTitle));
		$journalTitle = trim(str_replace ("'","''",$journalTitle));
		$journalTitle = trim(str_replace ("&amp;","&",$journalTitle));


		$titleObj = new Title();
		$titleID = $titleObj->getByTitle($journalTitle, $printISSN, $onlineISSN, $publisherPlatformID);

		if ($titleID) $newTitle=0;


		//If it does not already exist, insert it into the Title and issn tables and get the new ID
		if (!$titleID && $journalTitle && ((strlen($printISSN) == "8") || !$printISSN)){

			$titleObj = new Title();
			$titleObj->titleID = '';
			$titleObj->title = $journalTitle;

			try {
				$titleObj->save();
				$titleID = $titleObj->primaryKey;
			} catch (Exception $e) {
				echo $e->getMessage();
			}

			$newTitle=1;


			#also insert into Title ISSN table
			if (strlen($printISSN) == "8") {
				$titleISSN = new TitleISSN();
				$titleISSN->titleISSNID = '';
				$titleISSN->titleID = $titleID;
				$titleISSN->issn = $printISSN;
				$titleISSN->issnType = 'print';

				try {
					$titleISSN->save();
				} catch (Exception $e) {
					echo $e->getMessage();
				}
			}

			if (strlen($onlineISSN) == "8") {
				$titleISSN = new TitleISSN();
				$titleISSN->titleISSNID = '';
				$titleISSN->titleID = $titleID;
				$titleISSN->issn = $onlineISSN;
				$titleISSN->issnType = 'online';

				try {
					$titleISSN->save();
				} catch (Exception $e) {
					echo $e->getMessage();
				}
			}


		}else{
			$titleObj = new Title(new NamedArguments(array('primaryKey' => $titleID)));

			if (($journalTitle && ((strlen($printISSN) == "8") || !$printISSN))){

				//If Title already existed
				//still should check for new online ISSN since they can be added in later spreadsheets
				if (strlen($onlineISSN) == "8") {
					//try to avoid doing this
					//$titleObj = new Title(new NamedArguments(array('primaryKey' => $titleID)));

					//if this online issn already isn''t in the DB
					if (!$titleObj->getExistingOnlineISSN($onlineISSN)) {
						$titleISSN = new TitleISSN();
						$titleISSN->titleISSNID = '';
						$titleISSN->titleID = $titleID;
						$titleISSN->issn = $onlineISSN;
						$titleISSN->issnType = 'online';

						try {
							$titleISSN->save();
						} catch (Exception $e) {
							echo $e->getMessage();
						}
					}
				}

			}

		}


		$holdYear = $year;
		$holdPubPlat = $publisherName . " / " . $platformName;
		$holdPublisher = $publisherName;
		$holdPlatform = $platformName;


		//do the same for Publisher/Platform text name
		$pubPlat = $publisherName . " / " . $platformName;

		if ($pubPlat != $holdPubPlat) {
			if (trim($pubPlat)){
				$logOutput .= "<br /><br />Publisher / Platform: " . $pubPlat;
			}
		}

		$mergeInd = 0;
		if ($titleID) {
			$rownumber++;
			//Add Title to log output
			if (trim($journalTitle)){
				$logOutput .="<br /><br />Title: " . $journalTitle;
			}


			//now we can insert the actual stats
			for ($i=$startMonth; $i<=$endMonth; $i++){
				$usageCount = '';

				if ((isset($month[$i])) && ($month[$i] != '')){
					$usageCount = $month[$i];
					$usageCount = str_replace(',','',$usageCount);
					$usageCount = str_replace('"','',$usageCount);
				}

				//must do this or will not insert '0' count records
				if ($usageCount >= "0"){
					$logOutput .="\r<br />";

					//skip if month is current month or in the future
					if ( mktime(0,0,0,$i,1,$year) < mktime(0,0,0,date('m'),1,date('Y'))){

						//if this is an override or the print ISSN already existed on the spreadsheet (value in array for each print issn is set to 1 later on)

						//if (($overrideInd == 1) || ($printISSNArray[$printISSN] == 1)) {

							//this is a merged title
							if (($printISSN) && (isset($printISSNArray[$printISSN]) && $printISSNArray[$printISSN] == 1)) {
								//add the other titles count in with this titles counts to merge the two together ($i = month)
								$usageCount+=$titleObj->getUsageCountByMonth($archiveInd, $year, $i, $publisherPlatformID);

								//now delete the old one ($i = month)
								$titleObj->deleteMonth($archiveInd, $year, $i, $publisherPlatformID);


								//flag when inserted into db that this is a merged statistic
								$mergeInd = 1;

								$logOutput .= "Duplicate record for this Print ISSN in same spreadsheet: Month: " . $i . "  New Count: " . $usageCount;
							}

							#calculate Outlier - dont bother if this is a new Title
							if (($newTitle == 0) && (count($outlier) > 0)){
								#figure out which months to pull - start with this month previous year
								$prevYear = $year-1;
								$prevMonths='';
								$currMonths='';
								$yearAddWhere='';
								$outlierID = '0';
								$outlierLevel = '';

								if ($i == 1){
									$yearAddWhere = "(year = " . $prevYear . ")";
								}else{
									for ($j=$i; $j<=11; $j++){
										$prevMonths .= $j . ", ";
									}
									$prevMonths .= "12";

									for ($j=1; $j<$i-1; $j++){
										$currMonths .= $j . ", ";
									}
									$currMonths .= $j;
									$yearAddWhere .= "((year = $prevYear and month in ($prevMonths)) or (year = $year and month in ($currMonths)))";
								}

								//get the previous 12 months data in an array
								$usageCountArray = array();
								$usageCountArray = $titleObj->get12MonthUsageCount($archiveInd, $publisherPlatformID, $yearAddWhere);

								$avgCount = 0;
								if (count($usageCountArray) == "12"){

									foreach ($usageCountArray as $usageCountRec) {
										$avgCount += $usageCountRec['usageCount'];
									}

									$avgCount = $avgCount / 12;

									foreach ($outlier as $k => $outlierArray) {
										if ($usageCount > ((($avgCount * ($outlierArray['overagePercent']/100)) + $outlierArray['overageCount'])) ) {
											//we can overwrite previous Outlier level so that we just take the highest Outlier level
											$outlierID = $k;
											$outlierLevel = $outlierArray['outlierLevel'];
										}
									}

								}else{
									$outlierID = '0';
								}

							}else{
								$outlierID = '0';
							}

							//if override and this is not a merged title delete original data so we don't have duplicates in system ($i = month)
							if ((!isset($printISSNArray[$printISSN])) && ($overrideInd == 1)){
								$titleObj->deleteMonth($archiveInd, $year, $i, $publisherPlatformID);
							}

							$monthlyUsageSummary = new MonthlyUsageSummary();
							$monthlyUsageSummary->titleID = $titleID;
							$monthlyUsageSummary->publisherPlatformID = $publisherPlatformID;
							$monthlyUsageSummary->year = $year;
							$monthlyUsageSummary->month = $i;
							$monthlyUsageSummary->archiveInd = $archiveInd;
							$monthlyUsageSummary->usageCount = $usageCount;
							$monthlyUsageSummary->outlierID = $outlierID;
							$monthlyUsageSummary->mergeInd = $mergeInd;
							$monthlyUsageSummary->ignoreOutlierInd = '0';
							$monthlyUsageSummary->overrideUsageCount = null;

							try {
								$monthlyUsageSummary->save();
							} catch (Exception $e) {
								echo $e->getMessage();
							}


							if (is_numeric($usageCount)){
								$logOutput .= "New Usage Count Record Added: Month: " . $i . "  Count: " . $usageCount;
							}else{
								$logOutput .= "Usage Count Record is not numeric for month: " . $i . "  Count: " . $usageCount . " imported as 0.";
							}


							$monthlyInsert=1;

							if ($outlierID){
								$logOutput .= "<br /><font color=\"red\">Outlier found for this record: Level " . $outlierLevel . "</font>";
							}


						//}else{
						//	$logOutput .= "Record already exists: Month: " . $i . "  Count: " . $usageCount;
						//}

					}else{
						$logOutput .= "Current or future month will not be imported: " . $i . "-" . $year . ": " . $usageCount;
					}

				//end usage count is entered
				}

			//end month for loop
			}



			//Insert YTD data
			//First delete existing, we will always overlay this data

			if ($ytd == true || $ytd == "0"){
				//only do this if any monthly data was imported
				if ($monthlyInsert == "1"){

					//make HTML and PDF counts 0 if they dont exist
					if (!$ytdHTML) $ytdHTML=0;
					if (!$ytdPDF) $ytdPDF=0;

					$ytd = str_replace(',','',$ytd);
					$ytd = str_replace('"','',$ytd);
					$ytdHTML = str_replace(',','',$ytdHTML);
					$ytdHTML = str_replace('"','',$ytdHTML);
					$ytdPDF = str_replace(',','',$ytdPDF);
					$ytdPDF = str_replace('"','',$ytdPDF);

					//this is a merged title
					if (($printISSN) && (isset($printISSNArray[$printISSN]) && $printISSNArray[$printISSN] == 1)) {

						$yearCountArray = array();
						$yearCountArray = $titleObj->getTotalCountByYear($archiveInd, $year, $publisherPlatformID);

						$ytd += $yearCountArray['usageCount'];
						$ytdHTML += $yearCountArray['ytdHTMLCount'];
						$ytdPDF += $yearCountArray['ytdPDFCount'];

						$logOutput .= "<br />YTD Already Exists for this Print ISSN, counts are added together.";
					}

					//delete these yearly stats since we will next overwrite them
					$titleObj->deleteYearlyStats($archiveInd, $year, $publisherPlatformID);

					$yearlyUsageSummary = new YearlyUsageSummary();
					$yearlyUsageSummary->yearlyUsageSummaryID = '';
					$yearlyUsageSummary->titleID = $titleID;
					$yearlyUsageSummary->publisherPlatformID = $publisherPlatformID;
					$yearlyUsageSummary->year = $year;
					$yearlyUsageSummary->archiveInd = $archiveInd;
					$yearlyUsageSummary->totalCount = $ytd;
					$yearlyUsageSummary->ytdHTMLCount = $ytdHTML;
					$yearlyUsageSummary->ytdPDFCount = $ytdPDF;
					$yearlyUsageSummary->mergeInd = $mergeInd;
					$yearlyUsageSummary->overrideTotalCount = '';
					$yearlyUsageSummary->overrideHTMLCount = '';
					$yearlyUsageSummary->overridePDFCount = '';

					try {
						$yearlyUsageSummary->save();
						$logOutput .= "<br />YTD Total Count: " . $ytd . "<br />YTD HTML Count: " . $ytdHTML . "<br />YTD PDF Count: " . $ytdPDF;
					} catch (Exception $e) {
						echo $e->getMessage();
					}


				}else{
					$logOutput .= "<br />No YTD import performed since monthly stats were not imported";
				}

			//end ytd if statement
			}

			# add to array so we can determine if print ISSN already exists in this spreadsheet to add counts together
			$printISSNArray[$printISSN] = 1;

		}else{ //end if for if Title match found
			$topLogOutput .= "<font color='red'>Title match did not complete correctly, please check print ISSN to verify for Title:  " . $journalTitle . ".</font><br />";
		}


	//end start flag if
	}


	//check "Total for all" is in first column  - set flag to start import after this
	if (strpos($line,"Total for all") !== false){
		$startFlag = "Y";
	}


	//reset all ID variables that were just set
	$titleID='';
	$publisherID='';
	$platformID='';
	$publisherPlatformID='';


//end loop for each line
}
fclose($file_handle);


#Save log output on server
$logfile = 'logs/' . date('Ymdhi') . '.php';
$excelfile = 'logs/' . date('Ymdhi') . '.xls';
$fp = fopen($logfile, 'w');
fwrite($fp, "<?php header(\"Content-type: application/vnd.ms-excel\");\nheader(\"Content-Disposition: attachment; filename=" . $excelfile . "\"); ?>");
fwrite($fp, "<html><head></head><body>");
fwrite($fp, $topLogOutput);  //for major errors
fwrite($fp, "<br />");
fwrite($fp, $logOutput);
fwrite($fp, "</body></html>");
fclose($fp);

//send email to email addresses listed in DB
$logEmailAddress = new LogEmailAddress();
$emailAddresses = array();

foreach ($logEmailAddress->allAsArray() as $emailAddress){
	$emailAddresses[] = $emailAddress['emailAddress'];
}

$util = new Utility();
$Base_URL = $util->getCORALURL() . "usage/";

$mailOutput='';
if (count($emailAddresses) > 0){
	$email = new Email();
	$email->to 			= implode(", ", $emailAddresses);
	$email->subject		= "Log Output for $uploadedFile";
	$email->message		= "Usage Statistics File Import Run!\n\nPlease find log file: \n\n" . $Base_URL . $logfile;


	if ($email->send()) {
		$mailOutput = "Log has been emailed to " . implode(", ", $emailAddresses);
	}else{
		$mailOutput = "Email to " . implode(", ", $emailAddresses) . " Failed!";
	}
}


include 'templates/header.php';

//Log import in database
$importLog = new ImportLog();
$importLog->importLogID = '';
$importLog->loginID = $user->loginID;
$importLog->fileName = $orgFileName;
$importLog->archiveFileURL = $uploadedFile;
$importLog->logFileURL = $logfile;
$importLog->details = $rownumber . " titles processed.";

try {
	$importLog->save();
} catch (Exception $e) {
	echo $e->getMessage();
}



?>


<table class="headerTable">
<tr><td>
<div class="headerText">Status</div>
	<br />
    <p>File archived as <?php echo $Base_URL . $uploadedFile; ?>.</p>
    <p>Log file available at: <a href='<?php echo $Base_URL . $logfile; ?>'><?php echo $Base_URL . $excelfile; ?></a>.</p>
    <p>Process completed.  <?php echo $mailOutput; ?></p>
    <br />
    <?php echo $screenOutput; ?><br />
    <p>&nbsp; </p>

</td>
</tr>
</table>


<?php include 'templates/footer.php'; ?>