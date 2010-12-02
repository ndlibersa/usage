<?php

if (isset($_GET['publisherPlatformID'])){
	$publisherPlatformID = $_GET['publisherPlatformID'];
	$platformID = '';
}

if (isset($_GET['platformID'])){
	$platformID = $_GET['platformID'];
	$publisherPlatformID = '';
}



if (($publisherPlatformID == '') && ($platformID == '')){
	header( 'Location: publisherPlatformList.php?error=1' ) ;
}


$pageTitle = 'View or Edit Publisher / Platform';

include 'templates/header.php';


if ($publisherPlatformID) {
	$obj = new PublisherPlatform(new NamedArguments(array('primaryKey' => $publisherPlatformID)));
	$pub = new Publisher(new NamedArguments(array('primaryKey' => $obj->publisherID)));
	$displayName = $pub->name;
}else if ($platformID){
	$obj = new Platform(new NamedArguments(array('primaryKey' => $platformID)));
	$displayName = $obj->name;
}
?>


<table class="headerTable" style="background-image:url('images/header.gif');background-repeat:no-repeat;">
<tr><td>
	<table style='width:897px;'>
	<tr style='vertical-align:top'>
	<td><span class="headerText"><?php echo $displayName; ?></span><br /><br /></td>
	<td style='text-align:right;'>&nbsp;</td>
	</tr>
	</table>




<?php

if (count($obj->getTitles()) == '0'){
	if ($publisherPlatformID) {
		echo "No titles found for this Publisher / Platform combination";
	}else if ($platformID){
		echo "No titles found for this Platform";
	}
}else{
?>

	<input type='hidden' name='platformID' id='platformID' value='<?php echo $platformID; ?>'>
	<input type='hidden' name='publisherPlatformID' id='publisherPlatformID' value='<?php echo $publisherPlatformID; ?>'>

	<div style="width: 781px;" id ='div_displayLogins'>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
			<tr>
				<td class="sidemenu">
					<div class="sidemenuselected" style='position: relative; width: 91px'><a href='javascript:void(0)' class='showLogins'>Logins</a></div>
					<div class='sidemenuunselected'><a href='javascript:void(0)' class='showNotes'>Notes</a></div>
					<div class='sidemenuunselected'><a href='javascript:void(0)' class='showStats'>Statistics</a></div>
					<div class='sidemenuunselected'><a href='javascript:void(0)' class='showTitles'>Titles</a></div>
				</td>
				<td class='mainContent'>

					<div id='div_loginDetails'>
					<img src = "images/circle.gif">Loading...
					</div>
				</td>
			</tr>
		</table>
	</div>



	<div style="display:none; width: 781px;" id ='div_displayNotes'>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
			<tr>
				<td class="sidemenu">
					<div class="sidemenuunselected"><a href='javascript:void(0)' class='showLogins'>Logins</a></div>
					<div class="sidemenuselected" style='position: relative; width: 91px'><a href='javascript:void(0)' class='showNotes'>Notes</a></div>
					<div class='sidemenuunselected'><a href='javascript:void(0)' class='showStats'>Statistics</a></div>
					<div class='sidemenuunselected'><a href='javascript:void(0)' class='showTitles'>Titles</a></div>
				</td>
				<td class='mainContent'>

					<div id='div_noteTextDetails'>
					<img src = "images/circle.gif">Loading...
					</div>
				</td>
			</tr>
		</table>
	</div>



	<div id ='div_displayStats' style='display:none;width:781px;'>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
			<tr>
				<td class="sidemenu">
					<div class="sidemenuunselected"><a href='javascript:void(0)' class='showLogins'>Logins</a></div>
					<div class='sidemenuunselected'><a href='javascript:void(0)' class='showNotes'>Notes</a></div>
					<div class='sidemenuselected' style='position: relative; width: 91px'><a href='javascript:void(0)' class='showStats'>Statistics</a></div>
					<div class='sidemenuunselected'><a href='javascript:void(0)' class='showTitles'>Titles</a></div>
				</td>
				<td class='mainContent'>

					<div id='div_statsDetails'>
					<img src = "images/circle.gif">Loading...
					</div>
				</td>
			</tr>
		</table>
	</div>


	<div id ='div_displayTitles' style='display:none;width:781px;'>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
			<tr>
				<td class="sidemenu">
					<div class="sidemenuunselected"><a href='javascript:void(0)' class='showLogins'>Logins</a></div>
					<div class='sidemenuunselected'><a href='javascript:void(0)' class='showNotes'>Notes</a></div>
					<div class='sidemenuunselected'><a href='javascript:void(0)' class='showStats'>Statistics</a></div>
					<div class='sidemenuselected' style='position: relative; width: 91px'><a href='javascript:void(0)' class='showTitles'>Titles</a></div>
				</td>
				<td class='mainContent'>
					<div id='div_titleDetails'>
						<img src = "images/circle.gif">Loading...
					</div>
				</td>
			</tr>
		</table>
	</div>


</td></tr>
</table>



<script type="text/javascript" src="js/publisherPlatform.js"></script>


<?php

}
include 'templates/footer.php';

?>
