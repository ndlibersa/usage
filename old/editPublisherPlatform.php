<?php

$publisherPlatformID = $_GET['publisherPlatformID'];
$platformID = $_GET['platformID'];



if (($publisherPlatformID == '') && ($platformID == '')){
	header( 'Location: publishersPlatforms.php?error=1' ) ;
}


$pageTitle = 'Edit Publisher / Platform';

include 'includes/header.php';
include 'includes/common.php';
require 'includes/db.php';


if ($publisherPlatformID) {
	$result = mysql_query("select publisher.name publisher, platform.name platform from publisher_platform pp, publisher, platform where pp.platformID = platform.platformID and pp.publisherID = publisher.publisherID and publisherPlatformID='" . $publisherPlatformID . "';");
	while ($row = mysql_fetch_assoc($result)) {
		$displayName = $row['publisher'] . " / " . $row['platform'];
	}
	mysql_free_result($result);
}else if ($platformID){
	$result = mysql_query("select platform.name platform from platform where platformID='" . $platformID . "';");
	while ($row = mysql_fetch_assoc($result)) {
		$displayName = $row['platform'];
	}
	mysql_free_result($result);
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

if ($publisherPlatformID) {
	$result = mysql_query("select distinct t.titleID titleID, title from title t, title_stats_monthly tsm where tsm.titleID = t.titleID and publisherPlatformID = '" . $publisherPlatformID . "' order by title;");
}else if ($platformID){
	$result = mysql_query("select distinct t.titleID titleID, title from title t, title_stats_monthly tsm, publisher_platform pp where pp.publisherPlatformID = tsm.publisherPlatformID and tsm.titleID = t.titleID and pp.platformID = '" . $platformID . "' order by title;");
}


if (mysql_num_rows($result) == '0'){
	if ($publisherPlatformID) {
		echo "No titles found for this publisher / platform combination";
	}else if ($platformID){
		echo "No titles found for this platform";
	}
}else{
?>







	<input type='hidden' name='platformID' id='platformID' value='<?php echo $platformID; ?>'>
	<input type='hidden' name='publisherPlatformID' id='publisherPlatformID' value='<?php echo $publisherPlatformID; ?>'>

	<div style="width: 781px;" id ='div_displayNotes'>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
			<tr>
				<td class="sidemenu">
					<div class="sidemenuselected" style='position: relative; width: 91px'><a href='javascript:void(0)' class='showNotes'>Notes / Logins</a></div>
					<div class='sidemenuunselected'><a href='javascript:void(0)' class='showStats'>Statistics</a></div>
					<div class='sidemenuunselected'><a href='javascript:void(0)' class='showTitles'>Titles</a></div>
				</td>
				<td class='mainContent'>

					<div id='div_notesLoginDetails'>
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
					<div class="sidemenuunselected"><a href='javascript:void(0)' class='showNotes'>Notes / Logins</a></div>
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
					<div class="sidemenuunselected"><a href='javascript:void(0)' class='showNotes'>Notes / Logins</a></div>
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



<script type="text/javascript" src="js/editPublisherPlatform.js"></script>


<?php

}
include 'includes/footer.php';

?>
