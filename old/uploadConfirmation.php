<?php

//First, move the uploaded file
// Where the file is going to be placed

if (count(split ("[.]", basename( $_FILES['usageFile']['name']))) == "2"){
	list ($fileNameStart, $fileNameExt) = split ("[.]", basename( $_FILES['usageFile']['name']));
}else if (count(split ("[.]", basename( $_FILES['usageFile']['name']))) == "3"){
	list ($dateStamp, $fileNameStart, $fileNameExt) = split ("[.]", basename( $_FILES['usageFile']['name']));
}else{
	header( 'Location: fileupload.php?error=3' ) ;
}

$orgFileName = $_FILES['usageFile']['name'];

$ts = date("Ymd");
$target_path = "archive/" . $ts . "." . $fileNameStart .  "." . $fileNameExt;

if ($fileNameExt != "txt") {
	header( 'Location: fileupload.php?error=1' ) ;
}else{

  if(move_uploaded_file($_FILES['usageFile']['tmp_name'], $target_path)) {
	  $uploadConfirm = "The file ".  basename( $_FILES['usageFile']['name'])." has been uploaded successfully.<br />Please confirm the following data:<br />";
  } else{
	  header( 'Location: fileupload.php?error=2' ) ;
  }
}


$pageTitle = 'Upload Process Confirmation';
?>

<?php include 'includes/header.php'; ?>
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

  #read layouts ini file to get the available layouts
  $layoutsArray = parse_ini_file("layouts.ini", true);


  $file_handle = fopen($target_path, "r");

  #get column values from layouts array for test - checking columns 2 through 6 (column 1 - Journal - is not always entered, column 7 - Feb - on may not be included)
  for ($i = 2;$i <= 6;$i++){
  	$columnCheck[$i] = $layoutsArray['layout1']['column' . $i];
  }


  echo $uploadConfirm;
  echo "<table class='dataTable'>";

  while (!feof($file_handle)) {
	//get each line out of the file handler
     $line = fgets($file_handle);


	 #check column formats if the format correct flag has not been set yet
     if ($formatCorrectFlag == "N"){
		 list ($column1, $column2, $column3, $column4, $column5, $column6) = split("\t",$line);
		 $column1 = trim($column1);
		 $column2 = trim($column2);
		 $column3 = trim($column3);
		 $column4 = trim($column4);
		 $column5 = trim($column5);
		 $column6 = trim($column6);


		 #also get the full line in an array to print out the headers
		 $lineArray = split("\t",$line);

		 //remove white spaces for comparison
		 $column6 = trim($column6);

		 if ((strpos($column2,$columnCheck[2]) === 0) && (strpos($column3,$columnCheck[3]) === 0) && (strpos($column4,$columnCheck[4]) === 0) && (strpos($column5,$columnCheck[5]) === 0) && (strpos($column6,$columnCheck[6]) === 0) ){
			$formatCorrectFlag = "Y";
			$numberOfColumns = count($lineArray);

			list ($checkMonth,$checkYear) = split("[-\/.]",$column6);
			if ($checkYear < 100) $checkYear = 2000 + $checkYear;


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
		 $lineArray = split("\t",$line);

		 foreach($lineArray as $value){

			//Clean some of the data

			//strip everything after (Subs from title
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
   	echo "<br /><font color='red'>Error with Format:  File must hold column names listed in layouts.ini.  Please confirm layout.</font><br />";
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

  if ($_POST['archiveInd']){
  	echo "<br /><font color='red'>File is flagged as an Archive.  If this is incorrect use 'Cancel' to fix.</font><br />";
  }
  if ($_POST['overrideInd']){
  	echo "<br /><font color='red'>File is flagged to override verifications of previous month data.  If this is incorrect use 'Cancel' to fix.</font><br />";
  }
?>

	<br />
    <form id="confirmForm" name="confirmForm" enctype="multipart/form-data" method="post" action="uploadComplete.php">
    <input type="hidden" name="upFile" value="<?php echo $target_path; ?>">
    <input type="hidden" name="archiveInd" value="<?php echo $_POST['archiveInd']; ?>">
    <input type="hidden" name="overrideInd" value="<?php echo $_POST['overrideInd']; ?>">
    <input type="hidden" name="orgFileName" value="<?php echo $orgFileName; ?>">
	<table>
	<tr valign="center">
	<td>
	<input type="button" value="Cancel" onClick="javascript:history.back();">
    </td>
    <td>
    <input type="button" name="submitForm" id="submitForm" value="Confirm" <?php if ($errorFlag == "Y"){ echo "disabled"; } ?> onclick="javascript:updateSubmit();" />
    </td>
    </tr>
    </table>
    </form>
</td>
</tr>
</table>


<?php include 'includes/footer.php'; ?>
