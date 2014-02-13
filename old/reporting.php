<?php

$pageTitle = 'Report Options';
include 'includes/header.php';
require 'includes/db.php';
?>


<table class="headerTable">
<tr><td>
<div class="headerText" style='margin-bottom:9px;'>Publisher / Platform Reporting Administrative Update</div>

<b>Directions:</b>  Mark the checkboxes to add / remove specific Platforms or Publishers to the default report list.<br />Click 'edit report display name' to change the display name in the reporting system for specific Platforms or Publishers.
<br /><br />
<?php

//print each platform, then each publisher under
$result = mysql_query("select distinct platform.platformID, platform.name platform, platform.reportDisplayName reportPlatform, platform.reportDropDownInd from publisher_platform pp, platform where pp.platformID = platform.platformID  order by 2;");

echo "Available<br />As Default<br />Report";

echo "<div style='line-height:130%;'>";
while ($row = mysql_fetch_assoc($result)) {
	if ($row['reportDropDownInd'] == '1') { $reportDropDownInd = 'checked';}else{$reportDropDownInd = '';}

	echo "<div id = 'div_platform_" . $row['platformID'] . "'>";
	echo "<input type='checkbox' id='chk_platform_" . $row['platformID']  . "' onclick='javascript:updatePlatformDropDown(" . $row['platformID']  . ");' $reportDropDownInd>";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;<span class='platformText'>" . $row['platform'] . "</span>";
	if ($row['reportPlatform'])  echo "&nbsp;&nbsp;(<i>" . $row['reportPlatform'] . "</i>)";
	echo "&nbsp;&nbsp;<a href='ajax_forms.php?action=getReportDisplayForm&height=122&width=248&type=platform&updateID=" . $row['platformID'] . "&modal=true' class='thickbox'>edit report display name</a><br />";
	echo "</div>";
	echo "<span id='span_platform_" . $row['platformID'] . "_response' style='color:red'></span>";


	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:showPublisherList('" . $row['platformID'] . "');\"><img src='images/arrowright.gif' style='border:0px' alt='show publisher list' name='image_" . $row['platformID'] . "' id='image_" . $row['platformID'] . "'></a>&nbsp;<a href=\"javascript:showPublisherList('" . $row['platformID'] . "');\" name='link_" . $row['platformID'] . "' id='link_" . $row['platformID'] . "'>show publisher list</a><br />";

	echo "\n<div id='div_" . $row['platformID'] . "' style='display:none;max-width:600px;margin-left:50px;'>";

	$publisher_result = mysql_query("select publisher.name publisher, publisherPlatformID, publisher.publisherID, pp.reportDisplayName reportPublisher, pp.reportDropDownInd reportPublisherDropDownInd from publisher_platform pp, publisher where pp.publisherID = publisher.publisherID and platformID = '" . $row['platformID'] . "' order by 1,2;");

	while ($publisher_row = mysql_fetch_assoc($publisher_result)) {
		if ($publisher_row['reportPublisherDropDownInd'] == '1') { $reportDropDownInd = 'checked';}else{$reportDropDownInd = '';}

		echo "<div id = 'div_publisher_" . $publisher_row['publisherPlatformID'] . "'>";
		echo "<table><tr valign='top'><td><input type='checkbox' id='chk_publisher_" . $publisher_row['publisherPlatformID']  . "' onclick='javascript:updatePublisherDropDown(" . $publisher_row['publisherPlatformID']  . ");' $reportDropDownInd></td>";


		echo "<td>" . $publisher_row['publisher'];
		if ($publisher_row['reportPublisher'])  echo "&nbsp;&nbsp;(<i>" . $publisher_row['reportPublisher'] . "</i>)";
		echo "&nbsp;&nbsp;<a href='ajax_forms.php?action=getReportDisplayForm&height=122&width=248&type=publisher&updateID=" . $publisher_row['publisherPlatformID'] . "&modal=true' class='thickbox'>edit report display name</a></td></tr></table>";
		echo "</div>";
		echo "<span id='span_publisher_" . $publisher_row['publisherPlatformID'] . "_response' style='color:red'></span>";
	}

	echo "</div>";
	echo "<br />";
	mysql_free_result($publisher_result);

}

mysql_free_result($result);
echo "</div>";

?>

</td>
</tr>
</table>

<script type="text/javascript" src="js/reporting.js"></script>

<?php include 'includes/footer.php'; ?>