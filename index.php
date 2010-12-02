<?php

$pageTitle='Home';

include 'templates/header.php';

?>

<table class="headerTable">

<tr style='vertical-align:top;'>
<td style="width:235px;padding-right:10px;">

<div class="headerText" style='margin-bottom:9px;'>Usage Statistics File Upload</div>


  <?php

	#print errors if passed in

	if (isset($_GET['error'])){
		$errorNumber = $_GET['error'];
		switch ($errorNumber){
			case 1:
				echo "<font color='red'>Incorrect File format, must be .txt!</font><br /><br />";
				break;
			case 2:
				echo "<font color='red'>There was an error uploading the file.  Please verify the size is not over 5MB and try again!</font><br /><br />";
				break;
			case 3:
				echo "<font color='red'>File has an incorrectly formatted name - try filename.txt!</font><br /><br />";
				break;
		}
	}


  ?>

  <font color='red'>Save file as .txt files in tab delimited format</font><br /><br />
    <form id="form1" name="form1" enctype="multipart/form-data" onsubmit="return validateForm()" method="post" action="uploadConfirmation.php">

      <b>Choose File:</b><span id='span_error' style='color:red'></span><br /><input type="file" name="usageFile" id="usageFile" accept="text/html" class='bigger' /><br /><br />
      <input type="checkbox" name="archiveInd" id="archiveInd" />&nbsp;This is an Archive Report (JR1a)<br />
      <input type="checkbox" name="overrideInd" id="overrideInd" />&nbsp;Override previous month verification<br /><br />
      <input type="submit" name="submitFile" id="submitFile" value="Upload" />
      <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    </form>
    <h1>&nbsp;</h1>



</td>
<td>

<div class="headerText" style='margin-bottom:9px;'>Recent Imports&nbsp;&nbsp;&nbsp;<span id='span_feedback'></span></div>
<div id='div_recentImports'>
</div>

</td></tr>

</table>


<script type="text/javascript" src="js/index.js"></script>

<?php include 'templates/footer.php'; ?>