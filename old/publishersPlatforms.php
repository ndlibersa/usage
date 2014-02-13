<?php

$pageTitle = "Edit Publishers / Platforms";

include 'includes/header.php';
require 'includes/db.php';

if ($_GET['error'] == "1"){
	$errorMessage = "<font color='red'>Please enter a title</font>";
}


?>

<table class="headerTable">
<tr><td>
<div class="headerText">Publisher / Platform Update</div>


  <?php echo $errorMessage; ?><form name='searchTitles' action='editPublisherPlatform.php' method='post'>

  <br />

  <?php

	//print each platform, then each publisher under
	$result = mysql_query("select distinct platform.platformID, platform.name platform from publisher_platform pp, platform where pp.platformID = platform.platformID  order by 2;");

	echo "<div style='line-height:130%;margin-left:5px;text-align:left;'>\n";
	while ($row = mysql_fetch_assoc($result)) {
		echo "<div style='margin-bottom:15px;'>";
		echo "<span class='platformText'>" . $row['platform'] . "</span>&nbsp;&nbsp;<a href='editPublisherPlatform.php?platformID=" . $row['platformID'] . "' class='smallLink'>edit</a>";
		echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:showPublisherList('" . $row['platformID'] . "');\"><img src='images/arrowright.gif' style='border:0px' alt='show publisher list' id='image_" . $row['platformID'] . "'></a>&nbsp;<a href=\"javascript:showPublisherList('" . $row['platformID'] . "');\" id='link_" . $row['platformID'] . "'>show publisher list</a><br />";

		echo "<div id='div_" . $row['platformID'] . "' style='display:none;width:600px;margin-left:40px'>";

		$publisher_result = mysql_query("select publisher.name publisher, publisherPlatformID from publisher_platform pp, publisher where pp.publisherID = publisher.publisherID and platformID = '" . $row['platformID'] . "' order by 1,2;");

		while ($publisher_row = mysql_fetch_assoc($publisher_result)) {
			echo $publisher_row['publisher'] . "&nbsp;&nbsp;<a href='editPublisherPlatform.php?publisherPlatformID=" . $publisher_row['publisherPlatformID'] . "'>edit</a><br />";
		}

		echo "</div>";
		mysql_free_result($publisher_result);
		echo "</div>";

	}

	mysql_free_result($result);
	echo "</div>\n";

  ?>

</td>
</tr>
</table>

<script type="text/javascript" src="js/publishersPlatforms.js"></script>

<?php include 'includes/footer.php'; ?>