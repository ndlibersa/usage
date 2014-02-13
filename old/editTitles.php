<?php

$publisherPlatformID = $_GET['publisherPlatformID'];
$platformID = $_GET['platformID'];
$titleSearch = $_POST['titleSearch'];
# shouldnt be any quotes but just to make sure
$titleSearch = str_replace("\"","",$titleSearch);

if (($titleSearch =='') && ($publisherPlatformID == '') && ($platformID == '')){
	header( 'Location: titles.php?error=1' ) ;
}


$head = 'titles';
include 'includes/header.php';
include 'includes/common.php';
require 'includes/db.php';


?>

<script type>

  var publisherPlatformID = '<?php echo $publisherPlatformID; ?>';
  var platformID = '<?php echo $platformID; ?>';
  var titleSearch = "<?php echo $titleSearch; ?>";
  var i = 1;

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


  function showAddISSN(titleID){

 	httpObject = getHTTPObject();
    if (httpObject != null) {
      httpObject.open("GET", "includes/ajax_functions.php?function=getISSNReasonDropDown&titleID="+titleID+"&i="+i, true);
      httpObject.send(null);

  		httpObject.onreadystatechange = function (){
			if(httpObject.readyState == 4){
				dropdown = httpObject.responseText;
				document.getElementById('div_add_issn_type_' + titleID).innerHTML+="<select name='issn_type_"+titleID+"_"+i+"' id='issn_type_"+titleID+"_"+i+"'><option value='print'>print</option><option value='online'>online</option></select><br />";
				document.getElementById('div_add_issn_reason_' + titleID).innerHTML+= dropdown + "<br />";
				document.getElementById('div_add_issn_' + titleID).innerHTML+="<input type='text' name='issn_"+titleID+"_"+i+"' id='issn_"+titleID+"_"+i+"' style='width:60px'>";
				document.getElementById('div_add_issn_prompt_' + titleID).innerHTML+="<a href='javascript:addISSN("+titleID+","+i+");'>insert</a><br />";

				i++;
			}
  		}
    }

  }



  function addISSN(titleID, ID){
  	issn = document.getElementById('issn_' + titleID + '_' + ID).value;
  	if (isISSN(issn) == false) { alert ("ISSN must be 8 characters"); return; }
  	issnType = document.getElementById('issn_type_' + titleID + '_' + ID).options[document.getElementById('issn_type_' + titleID + '_' + ID).selectedIndex].value;
  	issnReason = document.getElementById('issn_reason_' + titleID + '_' + ID).options[document.getElementById('issn_reason_' + titleID + '_' + ID).selectedIndex].value;

	httpObject = getHTTPObject();
    if (httpObject != null) {
      httpObject.open("GET", "includes/ajax_functions.php?function=addISSN&titleID="+titleID+"&issn="+issn+"&issnType="+issnType+"&issnReason="+issnReason, true);
      httpObject.send(null);

  		httpObject.onreadystatechange = function (){
			if(httpObject.readyState == 4){
				alert (httpObject.responseText);
				updateTitlesTable();
			}
  		}
    }


  }


  function removeISSN(titleISSNID){

	httpObject = getHTTPObject();
    if (httpObject != null) {
      httpObject.open("GET", "includes/ajax_functions.php?function=removeISSN&titleISSNID="+titleISSNID, true);
      httpObject.send(null);

  		httpObject.onreadystatechange = function (){
			if(httpObject.readyState == 4){
				alert (httpObject.responseText);
				updateTitlesTable();
			}
  		}
    }


  }

  function updateTitlesTable(){
	httpObject = getHTTPObject();
    if (httpObject != null) {
      httpObject.open("GET", "includes/ajax_functions.php?function=getTitlesTable&publisherPlatformID="+publisherPlatformID+"&platformID="+platformID+"&titleSearch="+titleSearch, true);
      httpObject.send(null);

  		httpObject.onreadystatechange = function (){
			if(httpObject.readyState == 4){
				document.getElementById('div_titles').innerHTML=httpObject.responseText;
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
	while ($row = mysql_fetch_assoc($result)) {
		echo "<h2>" . $row['publisher'] . " / " . $row['platform'] . "</h2>";
	}
	mysql_free_result($result);
}else if ($platformID){
	$result = mysql_query("select platform.name platform from platform where platformID='" . $platformID . "';");
	while ($row = mysql_fetch_assoc($result)) {
		echo "<h2>" . $row['platform'] . "</h2>";
	}
	mysql_free_result($result);
}else{
	echo "<h2>Search Results for: " . $titleSearch . "</h2>";
}

?>


<?php

	if ($publisherPlatformID) {
		$result = mysql_query("select distinct t.titleID titleID, title from title t, title_stats_monthly tsm where tsm.titleID = t.titleID and publisherPlatformID = '" . $publisherPlatformID . "' order by title;");
	}else if ($platformID){
		$result = mysql_query("select distinct t.titleID titleID, title from title t, title_stats_monthly tsm, publisher_platform pp where pp.publisherPlatformID = tsm.publisherPlatformID and tsm.titleID = t.titleID and pp.platformID = '" . $platformID . "' order by title;");
	}else{
		$result = mysql_query("select distinct titleID, title from title t where (title like '" . $titleSearch . "%' or title like '% " . $titleSearch . " %') order by title;");
	}

	if (mysql_num_rows($result) == '0'){
		if ($publisherPlatformID) {
			echo "No titles found for this publisher / platform combination";
		}else if ($platformID){
			echo "No titles found for this platform";
		}else{
			echo "No titles found for search term.<br /><a href='titles.php'>return to titles page</a>";
		}
	}else{
?>

<h3>Associated Titles and ISSNs</h3>

<div id="div_titles">
<table border="0" style="width:650px">
<tr>
<th>&nbsp;</th>
<th>ISSN Type</th>
<th>ISSN Change Reason</th>
<th>ISSN</th>
<th>&nbsp;</th>
</tr>


<?php

		while ($row = mysql_fetch_assoc($result)) {

			echo "<tr>";

			$issn_result = mysql_query("select issn from title_issn ti left join issn_change_reason icr on (icr.ISSNChangeReasonID = ti.ISSNChangeReasonID) where ti.titleID = '" . $row['titleID'] . "' order by issnType desc LIMIT 1;");
			$issn_row = mysql_fetch_assoc($issn_result);

			if ($issn_row['issn']){
				echo "<td style='width:250px'><b>" . $row['title'] . "</b><br /><a href=\"javascript:popUp('relatedTitles.php?titleID=" . $row['titleID'] . "');\">view related titles</a>&nbsp;&nbsp;<a href='http://findtext.library.nd.edu:8889/ndu_local?url_ver=Z39.88-2004&ctx_ver=Z39.88-2004&ctx_enc=info:ofi/enc:UTF-8&rfr_id=info:sid/ND_ejl_stat&url_ctx_fmt=info:ofi/fmt:kev:mtx:ctx&svc_val_fmt=info:ofi/fmt:kev:mtx:sch_svc&sfx.ignore_date_threshold=1&rft.issn=" . $issn_row['issn']  . "' target='_blank'>view in findtext</a></td>";
			}else{
				echo "<td style='width:250px'><b>" . $row['title'] . "</b><br /><a href=\"javascript:popUp('relatedTitles.php?titleID=" . $row['titleID'] . "');\">view related titles</a></td>";
			}


			$issn_result = mysql_query("select titleissnid, issn, issntype, reason from title_issn ti left join issn_change_reason icr on (icr.ISSNChangeReasonID = ti.ISSNChangeReasonID) where ti.titleID = '" . $row['titleID'] . "' order by issnType desc;");
			while ($issn_row = mysql_fetch_assoc($issn_result)) {
				$displayISSN = substr($issn_row['issn'],0,4) . "-" . substr($issn_row['issn'],4,4);

				echo "<td>" . $issn_row['issntype'] . "</td>";
				if ($issn_row['reason']){
					echo "<td>" . $issn_row['reason'] . "</td>";
				}else{
					echo "<td>N/A</td>";
				}
				echo "<td>" . $displayISSN . "</td>";
				echo "<td style='width:150px'><a href='javascript:removeISSN(" . $issn_row['titleissnid'] . ");'>remove this issn</a></td>";
				echo "</tr>";

				echo "<tr>";
				echo "<td>&nbsp;</td>";
			}

			echo "<td colspan='2'><a href=\"javascript:showAddISSN('" .  $row['titleID'] . "');\">add issn</a></td><td colspan='2'>&nbsp;</td></tr>";
			echo "<tr>";
			echo "<td>&nbsp;</td>";
			echo "<td>";
			echo "<div id='div_add_issn_type_" .  $row['titleID'] . "'>";
			echo "</div>";
			echo "</td>";
			echo "<td>";
			echo "<div id='div_add_issn_reason_" .  $row['titleID'] . "'>";
			echo "</div>";
			echo "</td>";
			echo "<td>";
			echo "<div id='div_add_issn_" .  $row['titleID'] . "'>";
			echo "</div>";
			echo "</td>";
			echo "<td>";
			echo "<div id='div_add_issn_prompt_" .  $row['titleID'] . "'>";
			echo "</div>";
			echo "</td>";
			echo "</tr>";


		}
	}
	mysql_free_result($result);
?>
	</table>
	</div>
<br />
<br />
<?php include 'includes/footer.php'; ?>