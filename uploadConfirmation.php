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


include_once 'directory.php';

//First, move the uploaded file
// Where the file is going to be placed

if (count(explode (".", basename( $_FILES['usageFile']['name']))) == "2"){
	list ($fileNameStart, $fileNameExt) = explode (".", basename( $_FILES['usageFile']['name']));
}else if (count(explode (".", basename( $_FILES['usageFile']['name']))) == "3"){
	list ($dateStamp, $fileNameStart, $fileNameExt) = explode (".", basename( $_FILES['usageFile']['name']));
}else{
	header( 'Location: index.php?error=3' ) ;
}

$orgFileName = $_FILES['usageFile']['name'];

$ts = date("Ymd");
$target_path = "archive/" . $ts . "." . $fileNameStart .  "." . $fileNameExt;
$checkYear = '';

if ($fileNameExt != "txt") {
	header( 'Location: index.php?error=1' ) ;
}else{

  if(move_uploaded_file($_FILES['usageFile']['tmp_name'], $target_path)) {
	  $uploadConfirm = "The file ".  basename( $_FILES['usageFile']['name'])." has been uploaded successfully.<br />Please confirm the following data:<br />";
  } else{
	  header( 'Location: index.php?error=2' ) ;
  }
}

$pageTitle = 'Upload Process Confirmation';
include 'templates/header.php';

?>

<script language="javascript">

function updateSubmit(){
	document.confirmForm.submitForm.disabled=true;
	document.confirmForm.submitForm.value="Processing Contents...";
	document.confirmForm.submit();
}
</script>




<table class="headerTable">
<tr><td>
<div class="headerText">Usage Statistics File Upload</div>

  <?php


  #file upload was OK, now we can read the file to output for confirmation
  $startFlag = "N";
  $formatCorrectFlag = "N";
  $errorFlag = "N";
  $reportTypeSet = "";


  #read layouts ini file to get the available layouts
  $layoutsArray = parse_ini_file("layouts.ini", true);

  #read this file
  $file_handle = fopen($target_path, "r");


  echo $uploadConfirm;
  echo "<table class='dataTable' style='width:895px;'>";


  //set report type default - JR1 R3 since this is the first, only report that used to be accepted
  $layout = $layoutsArray[ReportTypes]['default'];
  $reportTypeDisplay = $layout . " (default)";
  $columnsToCheck = $layoutsArray[$layout]['columnToCheck'];

  while (!feof($file_handle)) {
     //get each line out of the file handler
     $line = fgets($file_handle);

     //if report type hasn't been figured out, check for it in the first row / column
     if ($reportTypeSet == ""){

		foreach ($layoutsArray[ReportTypes] as $reportTypeKey => $layoutKey){
			list($report,$release) = explode("_",$reportTypeKey);
			if ((strpos($line, $report) !== false) && (strpos($line, $release) !== false)){
				$reportTypeSet = 'Y'; 
				$columnsToCheck = $layoutsArray[$layoutKey]['columnToCheck'];
				$reportTypeDisplay = $line; 
				$layout = $layoutKey;
			}	
		}


     }

     //check column formats if the format correct flag has not been set yet
     if (($formatCorrectFlag == "N") && (count(explode("\t",$line)) >= count($columnsToCheck))){
		//positive unless proven negative
		$formatCorrectFlag = "Y";
		$lineArray = explode("\t",$line);

		foreach ($columnsToCheck as $key => $colCheckName){
			$fileColName = strtolower(trim($lineArray[$key]));	
			// echo "Col Check: " . strtolower($colCheckName) . ":   ";
			// echo "File Name: " . $fileColName . "<br />";

			if (strpos($fileColName, strtolower($colCheckName)) === false){
				$formatCorrectFlag='N';
				// echo $formatCorrectFlag . "<br /><br />";
			}	

		}


		 if ($formatCorrectFlag == 'Y'){
			$numberOfColumns = count($lineArray);

			list ($checkMonth,$checkYear) = preg_split("/[-\/.]/",$fileColName);
			if ($checkYear < 100) $checkYear = 2000 + $checkYear;

			//print out report type and year
			echo "<tr><td colspan='" . $numberOfColumns . "'>" . $reportTypeDisplay . " for " . $checkYear . "</td></tr>";

			#also print out column headers
			echo "<tr>";
			foreach ($lineArray as $value){
				echo "<th>" . $value . "</th>";
			}
			echo "</tr>";
		 }
	 }

	//as long as the flags are set to print out, and the line exists, print the line formatted in table
	//(strpos($line,"\t\t\t\t") === false)
	 if (($formatCorrectFlag == "Y") && ($startFlag == "Y")  && (strpos($line,"\t") != "0" )) {
	 	 echo "<tr>";
		 $lineArray = explode("\t",$line);

		 foreach($lineArray as $value){

			//Clean some of the data

			//strip everything after (Subs from Title
			if (strpos($value,' (Subs') !== false) $value = substr($value,0,strpos($value,' (Subs'));

			//remove " quotes
			$value = str_replace("\"","",$value);

			if (($value == '') || ($value == ' ')) {
				echo "<td>&nbsp;</td>";
		  	}else{
		  		echo "<td>" . $value . "</td>";
		  	}
		 }
		 echo "</tr>";

	 }


	 #check "Total for all" is in first column  - set flag to start import after this
     if (strpos($line,"Total for all") !== false){
     	$startFlag = "Y";
     }


  }
  echo "</table>";
  fclose($file_handle);


  $errrorFlag="N";

  if (($formatCorrectFlag == "N")){
   	echo "<br /><font color='red'><b>Error with Format</b>:  Report format is set to <b>" . $reportTypeDisplay . "</b> but does not match the column names listed in layouts.ini for this format.  Also, the month of January must exist.  <br /><br />Expecting columns: " . implode(", ", $columnsToCheck) . "</font><br />";
   	$errorFlag="Y";
  }

  if (($startFlag == "N")){
   	echo "<br /><font color='red'>Error with Format:  The line preceding the first should start with 'Total for all'.</font><br />";
   	$errorFlag="Y";
  }


  if ($checkYear > date('Y')){
  	echo "<br /><font color='red'>Error with Year:  Year listed in header (" . $checkYear . ") may not be ahead of current year.  Please correct and submit again.</font><br />";
  	$errorFlag="Y";
  }

  if ((isset($_POST['archiveInd'])) || (strpos("a",$layout) > 0)){
        echo "<br /><font color='red'>File is flagged as an Archive.  If this is incorrect use 'Cancel' to fix.</font><br />";
        $archiveInd = 1;
  }else{
        $archiveInd = 0;
  }

  if (isset($_POST['overrideInd'])){
  	echo "<br /><font color='red'>File is flagged to override verifications of previous month data.  If this is incorrect use 'Cancel' to fix.</font><br />";
  	$overrideInd = 1;
  }else{
  	$overrideInd = 0;
  }

  if ($errorFlag != "Y"){
  	echo "<br />Report Format: <b>" . $reportTypeDisplay . "</b><br />If this is incorrect, please use 'Cancel' to go back and fix the headers of the file.<br />";
  }

?>

	<br />
    <form id="confirmForm" name="confirmForm" enctype="multipart/form-data" method="post" action="uploadComplete.php">
    <input type="hidden" name="upFile" value="<?php echo $target_path; ?>">
    <input type="hidden" name="archiveInd" value="<?php echo $archiveInd; ?>">
    <input type="hidden" name="overrideInd" value="<?php echo $overrideInd; ?>">
    <input type="hidden" name="orgFileName" value="<?php echo $orgFileName; ?>">
    <input type="hidden" name="layout" value="<?php echo $layout; ?>">
	<table>
	<tr valign="center">
	<td>
	<input type="button" name="submitForm" id="submitForm" value="Confirm" <?php if ($errorFlag == "Y"){ echo "disabled"; } ?> onclick="javascript:updateSubmit();" />
    </td>
    <td>
	<input type="button" value="Cancel" onClick="javascript:history.back();">
    </td>
    </tr>
    </table>
    </form>
</td>
</tr>
</table>


<?php include 'templates/footer.php'; ?>
