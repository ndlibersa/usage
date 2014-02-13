<?php
$head = 'stats';
include 'includes/header.php';
require 'includes/db.php';
?>
  <h2>Publisher / Platform Statistics Update</h2>
  <h3>Choose Publisher / Platform to edit</h3>

  <?php

	//print each platform, then each publisher under
	$result = mysql_query("select distinct platform.platformID, platform.name platform from publisher_platform pp, platform where pp.platformID = platform.platformID  order by 2;");

	echo "<div style='line-height:130%;'>";
	while ($row = mysql_fetch_assoc($result)) {

		echo "<span class='platformText'>" . $row['platform'] . "</span>&nbsp;&nbsp;<a href='editStats.php?platformID=" . $row['platformID'] . "'>edit statistics</a>";
		echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:toggleDivs('" . $row['platformID'] . "');\" onMouseOver='document.image_" . $row['platformID'] . ".src=\"images/plus_sel.gif\"' onMouseOut='document.image_" . $row['platformID'] . ".src=\"images/plus.gif\"'><img src='images/plus.gif' style='border:0px' alt='show publisher list' name='image_" . $row['platformID'] . "'></a>&nbsp;<a href=\"javascript:toggleDivs('" . $row['platformID'] . "');\">show publisher list</a><br />";

		echo "<div id='div_" . $row['platformID'] . "' style='display:none;width:725px;margin-left:29px'>";

		$publisher_result = mysql_query("select publisher.name publisher, publisherPlatformID from publisher_platform pp, publisher where pp.publisherID = publisher.publisherID and platformID = '" . $row['platformID'] . "' order by 1,2;");

		while ($publisher_row = mysql_fetch_assoc($publisher_result)) {
			echo $publisher_row['publisher'] . "&nbsp;&nbsp;<a href='editStats.php?publisherPlatformID=" . $publisher_row['publisherPlatformID'] . "'>edit statistics</a><br />";
		}

		echo "</div>";
		echo "<br />";
		mysql_free_result($publisher_result);

	}

	mysql_free_result($result);
	echo "</div>";

  ?>

<?php include 'includes/footer.php'; ?>