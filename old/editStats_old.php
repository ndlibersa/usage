<?php

$head = 'stats';
include 'includes/header.php';
include 'includes/common.php';
require 'includes/db.php';

$publisherPlatformID = $_GET['publisherPlatformID'];
$platformID = $_GET['platformID'];
?>

<script type>

  var publisherPlatformID = '<?php echo $publisherPlatformID; ?>';
  var platformID = '<?php echo $platformID; ?>';

  //For AJAX
  //Get the HTTP Object

  function getHTTPObject(){
  	if (window.ActiveXObject)
  		return new ActiveXObject("Microsoft.XMLHTTP");
  	else if (window.XMLHttpRequest)
  		return new XMLHttpRequest();
  	else {
  		alert("Your browser does not support AJAX.");
  		return null;
  	}

  }


  function deleteMonth(month, year, archiveInd){
	if (confirm("Do you really want to delete this month?") == true) {
		httpObject = getHTTPObject();

		if (httpObject != null) {
		  httpObject.open("GET", "includes/ajax_functions.php?function=deleteMonth&publisherPlatformID="+publisherPlatformID+"&month="+month+"&year="+year+"&archiveInd="+archiveInd, true);
		  httpObject.send(null);

			httpObject.onreadystatechange = function (){
				if(httpObject.readyState == 4){
					alert (httpObject.responseText);
					updateStatsTable();
				}
			}
		}
	}
  }



  function updateOverride(titleStatsMonthlyID){
  	overrideUsageCount = document.getElementById('overrideUsageCount_' + titleStatsMonthlyID).value;

	httpObject = getHTTPObject();
    if (httpObject != null) {
      httpObject.open("GET", "includes/ajax_functions.php?function=updateOverride&titleStatsMonthlyID="+titleStatsMonthlyID+"&overrideUsageCount="+overrideUsageCount, true);
      httpObject.send(null);

  		httpObject.onreadystatechange = function (){
			if(httpObject.readyState == 4){
				alert (httpObject.responseText);
				updateStatsTable();
			}
  		}
    }


  }




  function removeOutlier(titleStatsMonthlyID){
	if (confirm("Do you really want to ignore this outlier flag?") == true) {
		httpObject = getHTTPObject();
		if (httpObject != null) {
		  httpObject.open("GET", "includes/ajax_functions.php?function=removeOutlier&titleStatsMonthlyID="+titleStatsMonthlyID, true);
		  httpObject.send(null);

			httpObject.onreadystatechange = function (){
				if(httpObject.readyState == 4){
					alert (httpObject.responseText);
					updateStatsTable();
				}
			}
		}
	}

  }


  function updateStatsTable(){
	httpObject = getHTTPObject();
    if (httpObject != null) {
      httpObject.open("GET", "includes/ajax_functions.php?function=getStatsTable&publisherPlatformID=<?php echo $publisherPlatformID; ?>", true);
      httpObject.send(null);

  		httpObject.onreadystatechange = function (){
			if(httpObject.readyState == 4){
				document.getElementById('div_stats').innerHTML=httpObject.responseText;
			}
  		}
    }
  }




function toggleLayer(whichLayer, state) {
  var elem, vis;
  if(document.getElementById) // this is the way the standards work
    elem = document.getElementById(whichLayer);
  else if(document.all) // this is the way old msie versions work
      elem = document.all[whichLayer];
  else if(document.layers) // this is the way nn4 works
    elem = document.layers[whichLayer];

  if (elem){
   vis = elem.style;
   vis.display = state;
  }
}


function popUp(URL) {
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=450,height=400');");
}



var httpObject = null;

</script>
<?php

if ($platformID) {



}

if ($publisherPlatformID) {
	$result = mysql_query("select publisher.name publisher, platform.name platform from publisher_platform pp, publisher, platform where pp.platformID = platform.platformID and pp.publisherID = publisher.publisherID and publisherPlatformID='" . $publisherPlatformID . "';");
}else{

	$result = mysql_query("select platform.name platform from platform where platformID='" . $platformID . "';");
	while ($row = mysql_fetch_assoc($result)) {
		echo "<h2>" . $row['platform'] . "</h2>";
	}
	mysql_free_result($result);


	$result = mysql_query("select pp.publisherPlatformID, publisher.name publisher, platform.name platform from publisher_platform pp, publisher, platform where pp.platformID = platform.platformID and pp.publisherID = publisher.publisherID and pp.platformID='" . $platformID . "';");
}

while ($row = mysql_fetch_assoc($result)) {
	echo "<h3>" . $row['publisher'] . "</h3>";
	if (!($publisherPlatformID)) {
		$publisherPlatformID = $row['publisherPlatformID'];
	}

?>

	<b>Statistics</b>

	<div id="div_stats">
	<table border="0" style="width:700px">
	<?php


		$month_result = mysql_query("select distinct year, month, archiveInd from title_stats_monthly where publisherPlatformID='" . $publisherPlatformID . "' order by year, archiveInd, month;");
		$totalRows = mysql_num_rows($month_result);
		$currentRow=1;
		while ($month_row = mysql_fetch_assoc($month_result)) {
			if ($month_row['archiveInd'] == "1") {$archive = 'Archive';}else{$archive='';}


			//monthly ouliers
			$query = "select titleStatsMonthlyID, title, archiveInd, usageCount, overrideUsageCount, color
				from title_stats_monthly tsm, title t, outlier o
				where tsm.titleID = t.titleID
				and o.outlierID = tsm.outlierID
				and publisherPlatformID='" . $publisherPlatformID . "'
				and archiveInd='" . $month_row['archiveInd'] . "'
				and year='" . $month_row['year'] . "'
				and month='" . $month_row['month'] . "';";

			$outlier_result = mysql_query($query);

			echo "<tr>";
			echo "<td style='width:40px'><b>" . numberToMonth($month_row['month']) . " " . $month_row['year'] . "</b><br />" . $archive . "</td>";
			echo "<td style='width:40px'><a href=\"javascript:deleteMonth('" . $month_row['month'] . "','" . $month_row['year'] . "','" . $month_row['archiveInd'] . "')\">delete entire month</a></td>";

			if (mysql_num_rows($outlier_result) != 0) {
				echo "<td style='width:200px'><a href=\"javascript:popUp('outliers.php?publisherPlatformID=" . $publisherPlatformID . "&archiveInd=" . $month_row['archiveInd'] . "&month=" . $month_row['month'] . "&year=" . $month_row['year'] . "');\">view outliers for this month</a></td>";
			}else{
				echo "<td style='width:200px'>&nbsp;</td>";
			}

			echo "</tr>";






			//Print YTD - only prints those titles for which there were outliers
			if (($month_row['month'] == "12") || ($totalRows == $currentRow)){

				$ytd_result = mysql_query("select distinct titleStatsYTDID, title, totalCount, HTMLCount, PDFCount, overrideTotalCount, overrideHTMLCount, overridePDFCount
				from title_stats_ytd tsy, title_stats_monthly tsm, title t
				where tsy.titleID = t.titleID
				and tsm.publisherPlatformID = tsy.publisherPlatformID
				and tsm.titleID = tsy.titleID
				and tsm.year = tsy.year
				and tsm.archiveInd = tsy.archiveInd
				and tsm.outlierID > 0
				and tsy.publisherPlatformID='" . $publisherPlatformID . "'
				and tsy.archiveInd='" . $month_row['archiveInd'] . "'
				and tsy.year='" . $month_row['year'] . "';");

				if (mysql_num_rows($ytd_result) > 0){
					?>
					<tr>
					<td class="ytd"><b>YTD <?php echo $month_row['year']; ?></b></font></td>
					<td>
					<a href="javascript:popUp('ytd_override.php?publisherPlatformID=<?php echo $publisherPlatformID . "&archiveInd=" . $month_row['archiveInd'] . "&year=" . $month_row['year']; ?>');">update overrides for this year</a>
					</td>
					<td><a target='_blank' href='spreadsheet.php?publisherPlatformID=<?php echo $publisherPlatformID; ?>&year=<?php echo $month_row['year']; ?>&archiveInd=<?php echo $month_row['archiveInd']; ?>'>view spreadsheet</a></td>
					</tr>
					<?php
				}else{
					?>
					<tr>
					<td class="ytd"><b>YTD <?php echo $row['year']; ?></b><br /><?php echo $archive; ?></font></td>
					<td width="90">(no outliers found for this year)</td>
					<td><a target='_blank' href='spreadsheet.php?publisherPlatformID=<?php echo $publisherPlatformID; ?>&year=<?php echo $month_row['year']; ?>&archiveInd=<?php echo $month_row['archiveInd']; ?>'>view spreadsheet</a></td>
					</tr>
					<tr>
					<td colspan='3'>&nbsp;</td>
					</tr>
					<?php
				}

			}

			$currentRow++;

		}
		mysql_free_result($month_result);
	?>
		</table>
		</div>
<?php
}
mysql_free_result($result);


?>
<br />
<br />
<?php include 'includes/footer.php'; ?>