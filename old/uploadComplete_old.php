<?php
$head = 'fileupload';

require 'includes/db.php';


#read layouts ini file to get the layouts to map to columns in the database
$layoutsArray = parse_ini_file("layouts.ini", true);


$uploadedFile = $_POST['upFile'];
$orgFileName = $_POST['orgFileName'];
$archiveInd = $_POST['archiveInd'];
$overrideInd = $_POST['overrideInd'];
$printISSNArray = array();

if ($archiveInd) { $archiveInd = 1; } else { $archiveInd = 0; }


$file_handle = fopen($uploadedFile, "r");

$logOutput = "Process started on " . date('l jS \of F Y h:i A') . "<br />";
$logOutput.= "File: " . $uploadedFile . "<br /><br />";

$startFlag = "N";
$formatFlag = "N";


#get outlier info
$result = mysql_query("select * from outlier order by outlierLevel;");
$outlier = array();
$logOutput.="Outlier Parameters:<br />";

while ($row = mysql_fetch_assoc($result)) {
	$logOutput.="Level " . $row['outlierLevel'] . ": " . $row['overageCount'] . " over plus " .  $row['overagePercent'] . "% over <br />";

	$outlier[$row['outlierID']]['overageCount'] = $row['overageCount'];
	$outlier[$row['outlierID']]['overagePercent'] = $row['overagePercent'];

}

$logOutput .="<br /><br />";

mysql_free_result($result);


#get column values from layouts array to determine layout - checking columns 2 through 20 (column 1 - Journal - is not always entered, column 7 - Feb - on may not be included)
for ($i = 2;$i <= 20;$i++){
	$columnCheck[$i] = $layoutsArray['layout1']['column' . $i];
}

$rownumber=0;


while (!feof($file_handle)) {

 //get each line out of the file handler
 $line = fgets($file_handle);


 #check column formats if the format correct flag has not been set yet
 if ($formatFlag == "N"){
	 $columnArray = split("\t",$line);

	 //strip spaces
	 foreach ($columnArray as $key => $value){
		$columnArray[$key] = trim($value);
	 }

	 //this is the header column
	 if ((strpos($columnArray[1],$columnCheck[2]) === 0) && (strpos($columnArray[2],$columnCheck[3]) === 0) && (strpos($columnArray[3],$columnCheck[4]) === 0) && (strpos($columnArray[4],$columnCheck[5]) === 0) && (strpos($columnArray[5],$columnCheck[6]) === 0) ){
		$formatFlag = "Y";
		$numberOfColumns = count($lineArray);

		list ($month,$year) = split("[-\/. ]",$columnArray[5]);
		if ($year < 100) { $year = 2000 + $year; }

		//loop through each month and the 3 ytd columns to determine which column number we should refer to when getting data below
		for ($i=5;$i<20;$i++){
			//convert to lowercase for comparison
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

		}

	 }
 }



 //as long as the flags are set to print out, and the line exists, print the line formatted in table
 if (($startFlag == "Y")  && !(strpos($line,"\t") == "0")) {

	$lineArray = split("\t",$line);
	$month = array();
	$journalTitle = $lineArray[$layoutsArray['layout1']['journal']];
	$platform = $lineArray[$layoutsArray['layout1']['platform']];
	$publisher = $lineArray[$layoutsArray['layout1']['publisher']];
	$printISSN = $lineArray[$layoutsArray['layout1']['printISSN']];
	$onlineISSN = $lineArray[$layoutsArray['layout1']['onlineISSN']];
	$publisher = $lineArray[$layoutsArray['layout1']['publisher']];
	$month['1'] = $lineArray[$columnNumberArray['1']];
	$month['2'] = $lineArray[$columnNumberArray['2']];
	$month['3'] = $lineArray[$columnNumberArray['3']];
	$month['4'] = $lineArray[$columnNumberArray['4']];
	$month['5'] = $lineArray[$columnNumberArray['5']];
	$month['6'] = $lineArray[$columnNumberArray['6']];
	$month['7'] = $lineArray[$columnNumberArray['7']];
	$month['8'] = $lineArray[$columnNumberArray['8']];
	$month['9'] = $lineArray[$columnNumberArray['9']];
	$month['10'] = $lineArray[$columnNumberArray['10']];
	$month['11'] = $lineArray[$columnNumberArray['11']];
	$month['12'] = $lineArray[$columnNumberArray['12']];
	$ytd = $lineArray[$columnNumberArray['ytd']];
	$ytdHTML = $lineArray[$columnNumberArray['ytdHTML']];
	$ytdPDF = $lineArray[$columnNumberArray['ytdPDF']];


	################################################################
	// PLATFORM
	// Query to see if the platform already exists, if so, get the ID
	#################################################################
	//Clean some of the data
	//replace ' with '' so SQL doesn't break
	$platform = str_replace ('\'','\'\'',$platform);
	//remove " quotes
	$platform = str_replace("\"","",$platform);

	$result = mysql_query("select platformID from platform where upper(name) = upper('" . $platform . "');");
	while ($row = mysql_fetch_assoc($result)) {
		$platformID = $row['platformID'];
	}

	mysql_free_result($result);


	//Find the most recent month for this year / platform that we have statistics for if override isn't set
	if (($platform) && !($startMonth)){
		if ($overrideInd){
			$logOutput .= "Override indicator set - all months will be imported.";
		}else{
			$result = mysql_query("select count(month) count_months, min(month) min_month, max(month) max_month from title_stats_monthly tsm, publisher_platform pp where tsm.publisherPlatformID = pp.publisherPlatformID and year = '" . $year . "' and pp.platformID = '" . $platformID . "' and archiveInd=$archiveInd;");
			while ($row = mysql_fetch_assoc($result)) {
				$count_months = $row['count_months'];
				$min_month = $row['min_month'];
				$max_month = $row['max_month'];
			}

			if ($count_months == 12){
				$logOutput .= "Entire year already exists for this platform / year.  No counts will be imported.";
			}else if (($min_month == 1) && ($max_month < 13)) {
				$startMonth=$max_month + 1;
				$logOutput .= "Month Started at: " . $startMonth;
			}else if ($count_months == 0){
				$logOutput .= "No records exist for this platform / year.  Import will start with month 1.";
			}else{
				$endMonth=$min_month-1;
				$logOutput .= "Partial year records exist for this platform / year.  Import will start with month 1 and end with month $endMonth.";
			}



			mysql_free_result($result);
		}

		if (!($startMonth)){
			$startMonth = 1;
		}

		if (!($endMonth)){
			$endMonth = 12;
		}

	}



	//For log output we only want to print the 	year once
	if ($year != $holdYear) {
		$logOutput .= "<br />Year: " . $year;
	}

	//If platform does not already exist, insert it and get the new ID
	if (!$platformID && $platform){
		mysql_query("insert into platform (name, reportDisplayName) values ('$platform', '$platform');");
		$platformID = mysql_insert_id();

		#also insert into platform interface
		mysql_query("insert into platform_interface (platformID, startYear, endYear) values ('$platformID', " . date('Y') . ", " . date('Y') . ");");

		#add to output on screen
		$screenOutput .= "<br /><b>New Platform set up: " . $platform . "   <a href='editInterface.php?platformID=" . $platformID . "'>edit</a></b>";


	}


	#################################################################
	// PUBLISHER
	// Query to see if the publisher already exists, if so, get the ID
	#################################################################
	//Clean some of the data
	//replace ' with '' so SQL doesn't break
	$publisher = str_replace ('\'','\'\'',$publisher);
	//remove " quotes
	$publisher = str_replace("\"","",$publisher);

	$result = mysql_query("select publisherID from publisher where upper(name) = upper('" . $publisher . "');");
	while ($row = mysql_fetch_assoc($result)) {
		$publisherID = $row['publisherID'];
	}

	mysql_free_result($result);


	//If it does not already exist, insert it and get the new ID
	if (!($publisherID) && ($publisher)){
		mysql_query("insert into publisher (name) values ('$publisher');");
		$publisherID = mysql_insert_id();
	}



	#################################################################
	// PUBLISHER / PLATFORM
	// Query to see if the publisher / platform already exists, if so, get the ID
	#################################################################
	$result = mysql_query("select publisherPlatformID from publisher_platform where publisherID = $publisherID and platformID = $platformID;");
	while ($row = mysql_fetch_assoc($result)) {
		$publisherPlatformID = $row['publisherPlatformID'];
	}

	mysql_free_result($result);


	//If it does not already exist, insert it and get the new ID
	if (!$publisherPlatformID && $publisherID && platformID){
		mysql_query("insert into publisher_platform (publisherID, platformID, reportDisplayName) values ('$publisherID', '$platformID', '$publisher');");
		$publisherPlatformID = mysql_insert_id();

		#add to log output
		$logOutput .= "<br />New Publisher / Platform set up: " . $publisher . " / " . $platform;

	}

	#################################################################
	// TITLE
	// Query to see if the title already exists, if so, get the ID
	#################################################################
	//first, remove the '-' from the ISSNs
	$printISSN = strtoupper(trim(str_replace ('-','',$printISSN)));
	if (strpos(strtoupper($printISSN),'N/A') !== false) $printISSN = '';
	if ($printISSN == '00000000') $printISSN = '';
	if (strtoupper($printISSN) == 'XXXXXXXX') $printISSN = '';
	$onlineISSN = strtoupper(trim(str_replace ('-','',$onlineISSN)));
	if (strpos(strtoupper($onlineISSN),'N/A') !== false) $onlineISSN = '';
	if ($onlineISSN == '00000000') $onlineISSN = '';
	if (strtoupper($onlineISSN) == 'XXXXXXXX') $onlineISSN = '';
	if (!$printISSN) { $searchISSN = trim($onlineISSN); }else{$searchISSN=trim($printISSN);}


	//Clean some of the data
	//replace ' with '' so SQL doesn't break
	$journalTitle = str_replace ('\'','\'\'',$journalTitle);
	//strip everything after parenthesis from title
	if (strpos($journalTitle,' (Subs') !== false) $journalTitle = substr($journalTitle,0,strpos($journalTitle,' (Subs'));
	if (strpos($journalTitle,'<BR>') !== false) $journalTitle = substr($journalTitle,0,strpos($journalTitle,'<BR>'));
	//remove " quotes
	$journalTitle = str_replace("\"","",$journalTitle);

	#default search to print ISSN only
	if ($printISSN) {
		$result = mysql_query("select distinct ti.titleID as titleID from title_issn ti, title t where t.titleID = ti.titleID and issnType = 'print' and issn = '" . $printISSN . "';");
	}else if ((!$printISSN) && ($onlineISSN)){
		$result = mysql_query("select distinct t.titleID as titleID from title_issn ti, title t, title_stats_monthly tsm where t.titleID = ti.titleID and issnType = 'online' and issn = '" . $onlineISSN . "' and t.titleID = tsm.titleID and publisherPlatformID = '" . $publisherPlatformID . "' and ucase(title) = ucase('" . $journalTitle . "');");
	}else if ((!$printISSN) && (!$onlineISSN)){
		$result = mysql_query("select distinct t.titleID as titleID from title t, title_stats_monthly tsm where t.titleID = tsm.titleID and publisherPlatformID = '" . $publisherPlatformID . "' and ucase(title) = ucase('" . $journalTitle . "');");
	}

	while ($row = mysql_fetch_assoc($result)) {
		$titleID = $row['titleID'];
		$newTitle=0;
	}


	mysql_free_result($result);


	//If it does not already exist, insert it into the title and issn tables and get the new ID
	if (!$titleID && $journalTitle && ((strlen($printISSN) == "8") || !$printISSN)){

		mysql_query("insert into title (title) values ('$journalTitle');");
		$titleID = mysql_insert_id();
		$newTitle=1;

		//$logOutput .= "<br />New Title Added: " . $journalTitle;

		#also insert into title ISSN table
		if (strlen($printISSN) == "8") {
			mysql_query("insert into title_issn (titleID, ISSN, ISSNType, ISSNChangeReasonID) values ('$titleID', '$printISSN', 'print', 0);");
		}

		if (strlen($onlineISSN) == "8") {
			mysql_query("insert into title_issn (titleID, ISSN, ISSNType, ISSNChangeReasonID) values ('$titleID', '$onlineISSN', 'online', 0);");
		}


	}else if (($journalTitle && ((strlen($printISSN) == "8") || !$printISSN))){

		//even if title exists
		//still should check for new online ISSN since they can be added in later spreadsheets
		if (strlen($onlineISSN) == "8") {
			$existISSN = 'N';

			$result = mysql_query("select ISSN from title_issn where ISSNType='online' and titleID = '" . $titleID . "';");

			while ($row = mysql_fetch_assoc($result)) {
				if ($row['ISSN'] == $onlineISSN){
					$existISSN = 'Y';
				}
			}

			mysql_free_result($result);

			//if this online issn already isn''t in the DB
			if ($existISSN == 'N') {
				mysql_query("insert into title_issn (titleID, ISSN, ISSNType, ISSNChangeReasonID) values ('$titleID', '$onlineISSN', 'online', 0);");
			}
		}

	}


	$holdYear = $year;

	//do the same for publisher/platform
	$pubPlat = $publisher . " / " . $platform;

	if ($pubPlat != $holdPubPlat) {
		if (trim($pubPlat)){
			$logOutput .= "<br /><br />Publisher / Platform: " . $pubPlat;
		}
	}

	$holdPubPlat = $publisher . " / " . $platform;

	$mergeInd = 0;
	if ($titleID) {
		$rownumber++;
		//Add Title to log output
		if (trim($journalTitle)){
			$logOutput .="<br /><br />Title: " . $journalTitle;
		}

		//now we can insert the actual stats
		for ($i=$startMonth; $i<=$endMonth; $i++){

			$usageCount = $month[$i];
			$usageCount = str_replace(',','',$usageCount);
			$usageCount = str_replace('"','',$usageCount);

			//must do this or will not insert '0' count records
			if ($usageCount == true || $usageCount == "0"){
				$logOutput .="\r<br />";

				#skip if month is current month or in the future
				if ( mktime(0,0,0,$i,1,$year) < mktime(0,0,0,date('m'),1,date('Y'))){

					#check if usage count is already entered
					$result = mysql_query("select usageCount from title_stats_monthly where titleID=$titleID and publisherPlatformID=$publisherPlatformID and year=$year and month=$i and archiveInd=$archiveInd;");

					if (($overrideInd) || (mysql_num_rows($result) == 0) || ($printISSNArray[$printISSN] == 1)) {
						if (($printISSN) && ($printISSNArray[$printISSN] == 1)) {
							$usageCount+=mysql_result($result,0);
							$result = mysql_query("delete from title_stats_monthly where titleID=$titleID and publisherPlatformID=$publisherPlatformID and year=$year and month=$i and archiveInd=$archiveInd;");

							//flag when inserted into db that this is a merged statistic
							$mergeInd = 1;

							$logOutput .= "Duplicate record for this Print ISSN in same spreadsheet: Month: " . $i . "  New Count: " . $usageCount;
						}else{
							if (is_numeric($usageCount)){
								$logOutput .= "New Usage Count Record Added: Month: " . $i . "  Count: " . $usageCount;
							}else{
								$logOutput .= "Usage Count Record is not numeric for month: " . $i . "  Count: " . $usageCount . " imported as 0.";
							}
						}

						#calculate outlier - dont bother if this is a new title
						if ($newTitle == 0){
							#figure out which months to pull - start with this month previous year
							$prevYear = $year-1;
							$prevMonths='';
							$currMonths='';
							$addWhere='';
							$outlierID = '';

							if ($i == 1){
								$addWhere = "(year = " . $prevYear . ")";
							}else{
								for ($j=$i; $j<=11; $j++){
									$prevMonths .= $j . ", ";
								}
								$prevMonths .= "12";

								for ($j=1; $j<$i-1; $j++){
									$currMonths .= $j . ", ";
								}
								$currMonths .= $j;
								$addWhere .= "((year = $prevYear and month in ($prevMonths)) or (year = $year and month in ($currMonths)))";
							}

							$result = mysql_query("select usageCount from title_stats_monthly where titleID=$titleID and publisherPlatformID=$publisherPlatformID and $addWhere and archiveInd=$archiveInd;");

							if (mysql_num_rows($result) == 12) {

								while ($row = mysql_fetch_assoc($result)) {
									$avgCount += $row['usageCount'];
								}

								$avgCount = $avgCount / 12;

								foreach ($outlier as $k => $outlierArray) {
									if ($usageCount > ((($avgCount * ($outlierArray['overagePercent']/100)) + $outlierArray['overageCount'])) ) {
										//we can overwrite previous outlier level so that we just take the highest outlier level
										$outlierID = $k;
									}
								}

							}else{
								$outlierID = '';
							}
							mysql_free_result($result);
						}else{
							$outlierID = '';
						}

						//if override is set delete original data so we don't have duplicates in system
						if ($overrideInd){
							mysql_query("delete from title_stats_monthly where titleID = '$titleID' and publisherPlatformID = '$publisherPlatformID' and year = '$year' and month = '$i' and archiveInd = '$archiveInd';");
						}

						mysql_query("insert into title_stats_monthly (titleID, publisherPlatformID, year, month, archiveInd, usageCount, outlierID, mergeInd) values ('$titleID', '$publisherPlatformID', '$year', '$i','$archiveInd', '$usageCount','$outlierID','$mergeInd');");
						$monthlyInsert=1;

						if ($outlierID){
							$logOutput .= "<br /><font color=\"red\">Outlier found for this record: Level " . $outlierID . "</font>";
						}


					}else{
						$logOutput .= "Record already exists: Month: " . $i . "  Count: " . $usageCount;
					}

					mysql_free_result($result);
				}else{
					$logOutput .= "Current or future month will not be imported: " . $i . "-" . $year . ": " . $usageCount;
				}

			}

		}



		//Insert YTD data
		//First delete existing, we will always overlay this data
		if ($ytd == true || $ytd == "0"){
			//only do this if any monthly data was imported
			if ($monthlyInsert == "1"){

				//make HTML and PDF counts 0 if they dont exist
				if (!$ytdHTML) {$ytdHTML=0;}
				if (!$ytdPDF) {$ytdPDF=0;}

				$ytd = str_replace(',','',$ytd);
				$ytd = str_replace('"','',$ytd);
				$ytdHTML = str_replace(',','',$ytdHTML);
				$ytdHTML = str_replace('"','',$ytdHTML);
				$ytdPDF = str_replace(',','',$ytdPDF);
				$ytdPDF = str_replace('"','',$ytdPDF);


				if (($printISSN) && ($printISSNArray[$printISSN] == 1)) {
					$result = mysql_query("select totalCount, HTMLCount, PDFCount from title_stats_ytd where titleID = $titleID and publisherPlatformID = $publisherPlatformID and year=$year and archiveInd = $archiveInd;");

					$ytd+=mysql_result($result, 0, 'totalCount');
					$ytdHTML+=mysql_result($result,0, 'HTMLCount');
					$ytdPDF+=mysql_result($result,0,'PDFCount');

					$logOutput .= "<br />YTD Already Exists for this Print ISSN, counts are added together.";
				}


				mysql_query("delete from title_stats_ytd where titleID = $titleID and publisherPlatformID = $publisherPlatformID and year=$year and archiveInd = $archiveInd;");

				if (mysql_query("insert into title_stats_ytd (titleID, publisherPlatformID, year, archiveInd, totalCount, HTMLCount, PDFCount, mergeInd) values ('$titleID', '$publisherPlatformID', '$year', '$archiveInd', '$ytd','$ytdHTML','$ytdPDF','$mergeInd');")) {
					$logOutput .= "<br />YTD Total Count: " . $ytd . "<br />YTD HTML Count: " . $ytdHTML . "<br />YTD PDF Count: " . $ytdPDF;
				}
			}else{
				$logOutput .= "<br />No YTD import performed since monthly stats were not imported";
			}
		}
	}else{ //end if for if title match found
		$topLogOutput .= "<font color='red'>Title match did not complete correctly, please check print ISSN to verify for title:  " . $journalTitle . ".</font><br />";
	}


	# add to array so we can determine if print ISSN already exists in this spreadsheet to add counts together
	$printISSNArray[$printISSN] = 1;

 }


 #check "Total for all" is in first column  - set flag to start import after this
 if (strpos($line,"Total for all") !== false){
	$startFlag = "Y";
 }


 #reset all ID variables that were just set
 $titleID='';
 $publisherID='';
 $platformID='';
 $publisherPlatformID='';

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

#Send Log Output
$result = mysql_query("select distinct emailAddress from log_email_address;");

while ($row = mysql_fetch_assoc($result)) {
	$emailAddresses .= $row['emailAddress'] . ',';
}

$to = substr($emailAddresses,0, (strlen($emailAddresses) - 1));
$body = "Usage Statistics File Import Run!\n\nPlease find log file: \n\n" . $Base_URL . $logfile;
$headers = "From: UsageStatistics";

if (mail($to, "Log Output for $uploadedFile", $body, $headers)) {
  	$mailOutput = "Log has been emailed to $to.";
}else{
	$mailOutput = "Email to $to Failed!";
}


list ($netID,$restofAddr) = split("@", $HTTP_SERVER_VARS['REMOTE_USER']);

#Log import in database
$query = "insert into import_log (netID, fileName, archiveFileURL, logFileURL, details) values ('$netID', '$orgFileName', '" . $Base_URL . $uploadedFile . "', '" . $Base_URL . $logfile . "', '" . $rownumber . " titles processed.');";
mysql_query($query);

?>

<?php include 'includes/header.php'; ?>
    <h2>Status</h2>
    <p>File archived as <?php echo $Base_URL . $uploadedFile; ?>".
    </p>
    <p>Log file available at: <a href='<?php echo $Base_URL . $logfile; ?>'><?php echo $Base_URL . $excelfile; ?></a>.</p>
    <p>Process completed.  <?php echo $mailOutput; ?></p>
    <br />
    <?php echo $screenOutput; ?><br />
    <p>&nbsp; </p>
<?php include 'includes/footer.php'; ?>