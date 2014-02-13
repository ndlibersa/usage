<?php

$pageTitle = 'File Import';
include 'includes/header.php';
?>
  <script type="text/javascript">

  function validateForm(){
	  if (document.getElementById('usageFile').value == '') {
		alert ('Please select a file.');
		return false;
	  }else{
	  	return true;
	  }

  }

  </script>


<table class="headerTable">
<tr><td>
<div class="headerText" style='margin-bottom:9px;'>Usage Statistics File Upload</div>


  <?php

	#print errors if passed in

	$errorNumber = $_GET['error'];
	switch ($errorNumber){
		case 1:
			echo "<font color='red'>Incorrect File format, must be .txt!</font><br /><br />";
			break;
		case 2:
			echo "<font color='red'>There was an error uploading the file.  Please verify the size is not over 5MB and try again!</font><br /><br />";
			break;

	}



  ?>
  <font color='red'>File should be saved as .txt files in tab delimited format</font><br /><br />
    <form id="form1" name="form1" enctype="multipart/form-data" onsubmit="return validateForm()" method="post" action="uploadConfirmation.php">

      <b>Choose File:</b><br /><input type="file" name="usageFile" id="usageFile" accept="text/html" class='bigger' /><br /><br />
      <input type="checkbox" name="archiveInd" id="archiveInd" />&nbsp;Check here if this is an Archive File<br />
      <input type="checkbox" name="overrideInd" id="overrideInd" />&nbsp;Check here to override previous month verification<br /><br />
      <input type="submit" name="submitFile" id="submitFile" value="Upload" />
      <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    </form>
    <h1>&nbsp;</h1>


</td>
</tr>
</table>


<?php include 'includes/footer.php'; ?>