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
		  httpObject.open("GET", "includes/ajax_functions.php?function=deleteMonth&publisherPlatformID="+publisherPlatformID+"&platformID="+platformID+"&month="+month+"&year="+year+"&archiveInd="+archiveInd, true);
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
      httpObject.open("GET", "includes/ajax_functions.php?function=getStatsTable&publisherPlatformID="+publisherPlatformID+"&platformID="+platformID, true);
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

if ($publisherPlatformID) {
	$result = mysql_query("select publisher.name publisher, platform.name platform from publisher_platform pp, publisher, platform where pp.platformID = platform.platformID and pp.publisherID = publisher.publisherID and publisherPlatformID='" . $publisherPlatformID . "';");
}else{
	$result = mysql_query("select platform.name platform from platform where platformID='" . $platformID . "';");
}

while ($row = mysql_fetch_assoc($result)) {
	if ($publisherPlatformID){
		echo "<h2>" . $row['publisher'] . " / " . $row['platform'] . "</h2>";
	}else{
		echo "<h2>" . $row['platform'] . "</h2>";
	}
}
mysql_free_result($result);


?>

<h3>Statistics</h3>

<div id="div_stats">
<table border="0" style="width:480px">
<?php

	if ($publisherPlatformID){
		$result = mysql_query("select distinct year, month, archiveInd from title_stats_monthly where publisherPlatformID='" . $publisherPlatformID . "' order by year, archiveInd, month;");
	}else{
		$result = mysql_query("select distinct year, month, archiveInd from title_stats_monthly tsm, publisher_platform pp where pp.publisherPlatformID = tsm.publisherPlatformID and pp.platformID = '" . $platformID . "' order by year, archiveInd, month;");
	}

	while ($row = mysql_fetch_assoc($result)) {
		if ($row['archiveInd'] == "1") {$archive = 'Archive';}else{$archive='';}

		echo "<tr>";
		echo "<td style='width:80px'><b>" . numberToMonth($row['month']) . " " . $row['year'] . "</b><br />" . $archive . "</td>";
		echo "<td style='width:200px'><a href=\"javascript:deleteMonth('" . $row['month'] . "','" . $row['year'] . "','" . $row['archiveInd'] . "')\">delete entire month</a></td>";

		//monthly ouliers
		if ($publisherPlatformID){
			$query = "select *
			from title_stats_monthly tsm
			where outlierID <> 0 and publisherPlatformID='" . $publisherPlatformID . "'
			and archiveInd='" . $row['archiveInd'] . "'	and year='" . $row['year'] . "'	and month='" . $row['month'] . "' and ignoreOutlierInd = 0;";
		}else{
			$query = "select tsm.*
			from title_stats_monthly tsm, publisher_platform pp
			where tsm.publisherPlatformID = pp.publisherPlatformID and outlierID <> 0 and platformID='" . $platformID . "'
			and archiveInd='" . $row['archiveInd'] . "'	and year='" . $row['year'] . "'	and month='" . $row['month'] . "' and ignoreOutlierInd = 0;";

		}
		$outlier_result = mysql_query($query);

		if (mysql_num_rows($outlier_result) != 0) {
			echo "<td style='width:200px'><a href=\"javascript:popUp('outliers.php?publisherPlatformID=" . $publisherPlatformID . "&platformID=" . $platformID . "&archiveInd=" . $row['archiveInd'] . "&month=" . $row['month'] . "&year=" . $row['year'] . "');\">view outliers for this month</a></td>";
		}else{
			echo "<td style='width:200px'>&nbsp;</td>";
		}

		echo "</tr>";

		#figure out if this is the max row to print the YTD
		if ($publisherPlatformID){
			$max_result = mysql_query("select max(month) max_month from title_stats_monthly where publisherPlatformID='" . $publisherPlatformID . "' and year='" . $row['year'] . "' and archiveInd='" . $row['archiveInd'] . "';");
		}else{
			$max_result = mysql_query("select max(month) max_month from title_stats_monthly tsm, publisher_platform pp where pp.publisherPlatformID = tsm.publisherPlatformID and pp.platformID = '" . $platformID . "' and year='" . $row['year'] . "' and archiveInd='" . $row['archiveInd'] . "';");
		}
		$max_row = mysql_fetch_assoc($max_result);

		//Print YTD - only prints those titles for which there were outliers
		if (($row['month'] == "12") || ($row['month'] == $max_row['max_month'])){
			if ($publisherPlatformID){
				$ytd_result = mysql_query("select distinct titleStatsYTDID
				from title_stats_ytd tsy, title_stats_monthly tsm
				where tsm.publisherPlatformID = tsy.publisherPlatformID
				and tsm.titleID = tsy.titleID
				and tsm.year = tsy.year
				and tsm.archiveInd = tsy.archiveInd
				and tsm.outlierID > 0
				and tsy.publisherPlatformID='" . $publisherPlatformID . "'
				and tsy.archiveInd='" . $row['archiveInd'] . "'
				and tsy.year='" . $row['year'] . "' and ignoreOutlierInd = 0;");
			}else{
				$ytd_result = mysql_query("select distinct titleStatsYTDID
				from title_stats_ytd tsy, title_stats_monthly tsm, publisher_platform pp
				where tsm.publisherPlatformID = tsy.publisherPlatformID
				and pp.publisherPlatformID = tsy.publisherPlatformID
				and tsm.titleID = tsy.titleID
				and tsm.year = tsy.year
				and tsm.archiveInd = tsy.archiveInd
				and tsm.outlierID > 0
				and pp.platformID='" . $platformID . "'
				and tsy.archiveInd='" . $row['archiveInd'] . "'
				and tsy.year='" . $row['year'] . "' and ignoreOutlierInd = 0;");
			}
			if (mysql_num_rows($ytd_result) > 0){
				?>
				<tr>
				<td class="ytd"><b>YTD <?php echo $row['year']; ?></b></font></td>
				<td>
				<a href="javascript:popUp('ytd_override.php?publisherPlatformID=<?php echo $publisherPlatformID . "&platformID=" . $platformID . "&archiveInd=" . $row['archiveInd'] . "&year=" . $row['year']; ?>');">update overrides for this year</a>
				</td>
				<?php
			}else{
				?>
				<tr>
				<td class="ytd"><b>YTD <?php echo $row['year']; ?></b><br /><?php echo $archive; ?></font></td>
				<td>(no outliers found for this year)</td>
				<?php
			}

			?>

			<td><a target='_blank' href='spreadsheet.php?publisherPlatformID=<?php echo $publisherPlatformID; ?>&platformID=<?php echo $platformID; ?>&year=<?php echo $row['year']; ?>&archiveInd=<?php echo $row['archiveInd']; ?>'>view spreadsheet</a></td>
			</tr>
			<tr>
			<td colspan='3'>&nbsp;</td>
			</tr>

			<?php

		}

	}
	mysql_free_result($result);
?>
	</table>
	</div>
<br />
<br />
<?php include 'includes/footer.php'; ?>