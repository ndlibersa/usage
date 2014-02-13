<?php
$head = 'stats';
include 'includes/header.php';
require 'includes/db.php';
?>
  <h2>Publisher / Platform Statistics Update</h2>
  <b>Choose Publisher / Platform to edit</b>
  <br />

  <?php

	//print each platform, then each publisher under
	$result = mysql_query("select distinct platform.platformID, platform.name platform from publisher_platform pp, platform where pp.platformID = platform.platformID  order by 2;");

	while ($row = mysql_fetch_assoc($result)) {

		echo "<br /><b>" . $row['platform'] . "</b>  <a href='editStats.php?platformID=" . $row['platformID'] . "'>edit statistics</a>";
		echo "<div id='display_div_" . $row['platformID'] . "'>&nbsp;&nbsp;&nbsp;<a href=\"javascript:toggleDivState('div_" . $row['platformID'] . "',1);toggleDivState('display_div_" . $row['platformID'] . "',0);\">show publisher list</a></div>";	echo "<div id='div_" . $row['platformID'] . "' style='display:none'>";

		$publisher_result = mysql_query("select publisher.name publisher, publisherPlatformID from publisher_platform pp, publisher where pp.publisherID = publisher.publisherID and platformID = '" . $row['platformID'] . "' order by 1,2;");

		while ($publisher_row = mysql_fetch_assoc($publisher_result)) {
			echo $publisher_row['publisher'] . "  <a href='editStats.php?publisherPlatformID=" . $publisher_row['publisherPlatformID'] . "'>edit statistics</a><br />";
		}

		echo "<br /><a href=\"javascript:toggleDivState('div_" . $row['platformID'] . "',0);toggleDivState('display_div_" . $row['platformID'] . "',1);\">hide publisher list</a>";
		echo "</div>";
		mysql_free_result($publisher_result);

	}

	mysql_free_result($result);

  ?>

<?php include 'includes/footer.php'; ?>