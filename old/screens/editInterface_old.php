<?php
include 'includes/header.php';
include 'includes/common.php';
require 'includes/db.php';
//error_reporting(1);

$publisherPlatformID = $_GET['publisherPlatformID'];
?>

<script type>

  var publisherPlatformID = <?php echo $publisherPlatformID; ?>;

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

  function addInterface(){
  	startYear = document.getElementById('startYear').value;
  	endYear = document.getElementById('endYear').value;
  	if (document.getElementById('counterCompliantInd').checked == true){counterCompliantInd = 1;
	}else{	counterCompliantInd = 0; }
  	if (document.getElementById('HTMLMultiplicationInd').checked == true){		HTMLMultiplicationInd = 1;
	}else{	HTMLMultiplicationInd = 0; }
  	interfaceNotes = document.getElementById('interfaceNotes').value;

	httpObject = getHTTPObject();
    if (httpObject != null) {
      httpObject.open("GET", "includes/ajax_functions.php?function=addInterface&publisherPlatformID="+publisherPlatformID+"&startYear="+startYear+"&endYear="+endYear+"&counterCompliantInd="+counterCompliantInd+"&HTMLMultiplicationInd="+HTMLMultiplicationInd+"&interfaceNotes="+interfaceNotes, true);
      httpObject.send(null);

  		httpObject.onreadystatechange = function (){
			if(httpObject.readyState == 4){
				document.getElementById('div_interface_response').innerHTML= httpObject.responseText;
				updateAddInterfaceTable();
			}
  		}
    }


  }



  function updateInterface(publisherPlatformInterfaceID){
  	startYear = document.getElementById('startYear_' + publisherPlatformInterfaceID).value;
  	endYear = document.getElementById('endYear_' + publisherPlatformInterfaceID).value;
  	if (document.getElementById('counterCompliantInd_' + publisherPlatformInterfaceID).checked == true){counterCompliantInd = 1;
	}else{	counterCompliantInd = 0; }
  	if (document.getElementById('HTMLMultiplicationInd_' + publisherPlatformInterfaceID).checked == true){		HTMLMultiplicationInd = 1;
	}else{	HTMLMultiplicationInd = 0; }
  	interfaceNotes = document.getElementById('interfaceNotes_' + publisherPlatformInterfaceID).value;

	httpObject = getHTTPObject();
    if (httpObject != null) {
      httpObject.open("GET", "includes/ajax_functions.php?function=updateInterface&publisherPlatformInterfaceID="+publisherPlatformInterfaceID+"&startYear="+startYear+"&endYear="+endYear+"&counterCompliantInd="+counterCompliantInd+"&HTMLMultiplicationInd="+HTMLMultiplicationInd+"&interfaceNotes="+interfaceNotes, true);
      httpObject.send(null);

  		httpObject.onreadystatechange = function (){
			if(httpObject.readyState == 4){
				document.getElementById('div_interface_response').innerHTML= httpObject.responseText;
				updateAddInterfaceTable();
			}
  		}
    }


  }


  function deleteInterface(interfaceID){
	httpObject = getHTTPObject();
    if (httpObject != null) {
      httpObject.open("GET", "includes/ajax_functions.php?function=deleteInterface&interfaceID="+interfaceID, true);
      httpObject.send(null);

  		httpObject.onreadystatechange = function (){
			if(httpObject.readyState == 4){
				document.getElementById('div_interface_response').innerHTML= httpObject.responseText;
				updateInterfaceTable();
			}
  		}
    }

  }


  function updateAddInterfaceTable(){
	httpObject = getHTTPObject();
    if (httpObject != null) {
      httpObject.open("GET", "includes/ajax_functions.php?function=getAddInterfaceTable", true);
      httpObject.send(null);

  		httpObject.onreadystatechange = function (){
			if(httpObject.readyState == 4){
				document.getElementById('div_interface_add').innerHTML=httpObject.responseText;
				updateInterfaceTable();
			}
  		}
    }
  }

  function updateInterfaceTable(){
	httpObject = getHTTPObject();
    if (httpObject != null) {
      httpObject.open("GET", "includes/ajax_functions.php?function=getInterfaceTable&publisherPlatformID=<?php echo $publisherPlatformID; ?>", true);
      httpObject.send(null);

  		httpObject.onreadystatechange = function (){
			if(httpObject.readyState == 4){

				document.getElementById('div_interfaces').innerHTML=httpObject.responseText;
			}
  		}
    }
  }





  function deleteMonth(month, year){
	httpObject = getHTTPObject();

    if (httpObject != null) {
      httpObject.open("GET", "includes/ajax_functions.php?function=deleteMonth&publisherPlatformID="+publisherPlatformID+"&month="+month+"&year="+year, true);
      httpObject.send(null);

  		httpObject.onreadystatechange = function (){
			if(httpObject.readyState == 4){
				document.getElementById('div_stats_response').innerHTML= httpObject.responseText;
				//updateInterfaceTable();
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
				document.getElementById('div_stats_response').innerHTML= httpObject.responseText;
				//updateAddInterfaceTable();
			}
  		}
    }


  }


  function removeOutlier(titleStatsMonthlyID){
	httpObject = getHTTPObject();
    if (httpObject != null) {
      httpObject.open("GET", "includes/ajax_functions.php?function=removeOutlier&titleStatsMonthlyID="+titleStatsMonthlyID, true);
      httpObject.send(null);

  		httpObject.onreadystatechange = function (){
			if(httpObject.readyState == 4){
				document.getElementById('div_stats_response').innerHTML= httpObject.responseText;
				//updateAddInterfaceTable();
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



var httpObject = null;

</script>

<?php


	$result = mysql_query("select publisher.name publisher, platform.name platform from publisher_platform pp, publisher, platform where pp.platformID = platform.platformID and pp.publisherID = publisher.publisherID and publisherPlatformID='" . $publisherPlatformID . "';");
	while ($row = mysql_fetch_assoc($result)) {
		echo "<h2>" . $row['publisher'] . " / " . $row['platform'] . "</h2>";

	}
	mysql_free_result($result);

	?>

	<div id="div_interface_response">&nbsp;</div>
	<h3>Current Interface Records</h3>
	<div id="div_interfaces">
	<table border="1">
    <tr>
    <th>Start Year</th>
    <th>End Year</th>
    <th>Counter<br />Compliant?</th>
    <th>Multiplies<br />HTML?</th>
    <th>Interface Notes</th>
    <th>&nbsp;</th>
    </tr>

	<?php

	$result = mysql_query("select * from platform_interface where platformID='" . $platformID . "';");
	while ($row = mysql_fetch_assoc($result)) {
		if ($row['counterCompliantInd'] == "1") $counterCompliantInd = 'checked'; else $counterCompliantInd = '';
		if ($row['HTMLMultiplicationInd'] == "1") $HTMLMultiplicationInd = 'checked'; else $HTMLMultiplicationInd = '';

		echo "<tr>";
		echo "<td><input name='startYear_" . $row['publisherPlatformInterfaceID'] . "' id='startYear_" . $row['publisherPlatformInterfaceID'] . "' type='text' size='10' value='" . $row['startYear'] . "' /></td>";
		echo "<td><input name='endYear_" . $row['publisherPlatformInterfaceID'] . "' id='endYear_" . $row['publisherPlatformInterfaceID'] . "' type='text' size='10' value='" . $row['endYear'] . "' /></td>";
		echo "<td align='center'><input type='checkbox' name='counterCompliantInd_" . $row['platformInterfaceID'] . "' id='counterCompliantInd_" . $row['publisherPlatformInterfaceID'] . "' $counterCompliantInd /></td>";
		echo "<td align='center'><input type='checkbox' name='HTMLMultiplicationInd_" . $row['platformInterfaceID'] . "' id='HTMLMultiplicationInd_" . $row['publisherPlatformInterfaceID'] . "' $HTMLMultiplicationInd /></td>";
		echo "<td><textarea name='interfaceNotes_" . $row['publisherPlatformInterfaceID'] . "' id='interfaceNotes_" . $row['publisherPlatformInterfaceID'] . "' cols='35' rows='2'>" . $row['interfaceNotes'] . "</textarea></td>";
		echo "<td><a href='javascript:updateInterface(" . $row['publisherPlatformInterfaceID'] . ");'>update</a><br /><a href='javascript:deleteInterface(" . $row['publisherPlatformInterfaceID'] . ");'>delete</a></td>";
		echo "</tr>";

	}
	mysql_free_result($result);

	?>
	</table>
	</div>

	<br />
	<div id="div_interface_add_prompt">
		<a href="javascript:toggleLayer('div_interface_add','block');javascript:toggleLayer('div_interface_add_prompt','none');"><---- Click to add new interface records-----></a><br />
	</div>
	<div id="div_interface_add" style="display:none">
	<h3>Add New Interface Record</h3>
	<table border="1">
    <tr>
    <th>Start Year</th>
    <th>End Year</th>
    <th>Counter<br />Compliant?</th>
    <th>Multiplies<br />HTML?</th>
    <th>Interface Notes</th>
    <th>&nbsp;</th>
    </tr>
	<tr>
	<td><input name='startYear' id='startYear' type='text' size='10' value='' /></td>
	<td><input name='endYear' id='endYear' type='text' size='10' value='' /></td>
	<td align='center'><input type='checkbox' name='counterCompliantInd' id='counterCompliantInd' /></td>
	<td align='center'><input type='checkbox' name='HTMLMultiplicationInd' id='HTMLMultiplicationInd' /></td>
	<td><textarea name='interfaceNotes' id='interfaceNotes' cols='35' rows='2'></textarea></td>
	<td><a href='javascript:addInterface();'>add</a></td>
	</tr>
   </table>
   <a href="javascript:toggleLayer('div_interface_add','none');javascript:toggleLayer('div_interface_add_prompt','block');">Click to hide</a>
   </div>

	<div id="div_stats_response">&nbsp;</div>
	<h3>Statistics</h3>
	<div id="div_stats">
	<table border="0">
	<?php


	//get (and save in an array) YTD data for display
	$arrayYTD = array();
	$result = mysql_query("select distinct year from title_stats_ytd where publisherPlatformID='" . $publisherPlatformID . "';");
	while ($row = mysql_fetch_assoc($result)) {
		$arrayYTD[$row['year']] = "<tr>";
		$arrayYTD[$row['year']] .= "<td><b>YTD " . $row['year'] . "</b></font></td>";
		$arrayYTD[$row['year']] .= "<td>&nbsp;</td>";
		$arrayYTD[$row['year']] .= "</tr>";
	}
	mysql_free_result($result);





	$result = mysql_query("select distinct year, month from title_stats_monthly where publisherPlatformID='" . $publisherPlatformID . "' order by year, month;");
	while ($row = mysql_fetch_assoc($result)) {
		echo "<tr>";
		echo "<td><b>" . numberToMonth($row['month']) . " " . $row['year'] . "</b></font></td>";
		echo "<td><a href=\"javascript:deleteMonth('" . $row['month'] . "','" . $row['year'] . "')\">delete entire month</a></td>";
		echo "</tr>";

		//monthly ouliers
		$query = "select titleStatsMonthlyID, title, usageCount, overrideUsageCount, color
			from title_stats_monthly tsm, title t, outlier o
			where tsm.titleID = t.titleID
			and o.outlierID = tsm.outlierID
			and publisherPlatformID='" . $publisherPlatformID . "'
			and year='" . $row['year'] . "'
			and month='" . $row['month'] . "';";

		$outlier_result = mysql_query($query);

		if (mysql_num_rows($outlier_result) != 0) {
			echo "<tr>";
			echo "<td>&nbsp;</td>";
			echo "<td>";
			echo "<div id='div_show_outlier_" . $row['month'] . "_" . $row['year'] . "'>";
			echo "<a href=\"javascript:toggleLayer('div_outlier_" . $row['month'] . "_" . $row['year'] . "','block');javascript:toggleLayer('div_show_outlier_" . $row['month'] . "_" . $row['year'] . "','none')\">view outliers for this month</a>";
			echo "</div>";

			echo "<div id='div_outlier_" . $row['month'] . "_" . $row['year'] . "' style='display:none'>";
			echo "<table border='0'>";

			while ($outlier_row = mysql_fetch_assoc($outlier_result)) {
				echo "<tr>";
				echo "<td style='width:150px;'>" . $outlier_row['title'] . "</td>";
				echo "<td style='width:50px;text-align:right;background-color:" . $outlier_row['color'] . "'>" . $outlier_row['usageCount'] . "</td>";
				echo "<td ><input type='text' name = 'overrideUsageCount_" . $outlier_row['titleStatsMonthlyID'] . "' id = 'overrideUsageCount_" . $outlier_row['titleStatsMonthlyID'] . "' value='" . $outlier_row['overrideUsageCount'] . "' style='width:50px'></td>";
				echo "<td ><a href=\"javascript:updateOverride('" . $outlier_row['titleStatsMonthlyID'] . "');\">update override</a></td>";
				echo "<td ><a href=\"javascript:removeOutlier('" . $outlier_row['titleStatsMonthlyID'] . "');\">ignore outlier</a></td>";

				echo "</tr>";
			}

			echo "</table>";
			echo "<a href=\"javascript:toggleLayer('div_outlier_" . $row['month'] . "_" . $row['year'] . "','none');javascript:toggleLayer('div_show_outlier_" . $row['month'] . "_" . $row['year'] . "','block')\">hide outliers for this month</a>";
			echo "</div>";

			mysql_free_result($outlier_result);

			echo "</td>";
			echo "</tr>";
		}




	}
	mysql_free_result($result);

	?>

	</table>
	</div>

<?php include 'includes/footer.php'; ?>