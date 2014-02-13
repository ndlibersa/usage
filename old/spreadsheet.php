<?php

require 'includes/db.php';

$year = $_GET['year'];
$publisherPlatformID = $_GET['publisherPlatformID'];
$platformID = $_GET['platformID'];
$archiveInd = $_GET['archiveInd'];

if ($archiveInd == '1') {
	$archive='Archive';
}

#get outlier info
$result = mysql_query("select * from outlier order by outlierLevel;");
$outlier = array();

while ($row = mysql_fetch_assoc($result)) {
	$outlier[$row['outlierID']]['color'] = $row['color'];
}

mysql_free_result($result);



if ($publisherPlatformID){
	$result = mysql_query("select distinct publisher.name display_name from publisher_platform pp, publisher, platform where pp.platformID = platform.platformID and pp.publisherID = publisher.publisherID and publisherPlatformID='" . $publisherPlatformID . "';");
}else{
	$result = mysql_query("select distinct platform.name display_name from publisher_platform pp, publisher, platform where pp.platformID = platform.platformID and pp.publisherID = publisher.publisherID and pp.platformID='" . $platformID . "';");
}


while ($row = mysql_fetch_assoc($result)) {
	$display_name = $row['display_name'];
}
mysql_free_result($result);

$excelfile = $display_name . "_" . $year;


$excelfile = str_replace (' ','_',$excelfile);

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename='" . $excelfile . "'");

?>

<html>
<head>
</head>
<body>

<h2><?php echo $display_name . " " . $archive . " - " . $year; ?></h2>
<table border='1'>
<tr>
<th>&nbsp;</th>
<th>Publisher</th>
<th>Platform</th>
<th>Print ISSN</th>
<th>Online ISSN</th>
<th>Jan-<?php echo $year; ?></th>
<th>Feb-<?php echo $year; ?></th>
<th>Mar-<?php echo $year; ?></th>
<th>Apr-<?php echo $year; ?></th>
<th>May-<?php echo $year; ?></th>
<th>Jun-<?php echo $year; ?></th>
<th>Jul-<?php echo $year; ?></th>
<th>Aug-<?php echo $year; ?></th>
<th>Sep-<?php echo $year; ?></th>
<th>Oct-<?php echo $year; ?></th>
<th>Nov-<?php echo $year; ?></th>
<th>Dec-<?php echo $year; ?></th>
<th>YTD Total</th>
<th>YTD HTML</th>
<th>YTD PDF</th>
</tr>

<?php

if ($publisherPlatformID){
	$pubPlatSwitch = "and tsm.publisherPlatformID = '" . $publisherPlatformID . "'";
}else{
	$pubPlatSwitch = "and pp.platformID = '" . $platformID . "'";
}



//Add a line for totals

$query = "SELECT
	sum(if(month='1',usageCount,null)) january,
	sum(if(month='2',usageCount,null)) february,
	sum(if(month='3',usageCount,null)) march,
	sum(if(month='4',usageCount,null)) april,
	sum(if(month='5',usageCount,null)) may,
	sum(if(month='6',usageCount,null)) june,
	sum(if(month='7',usageCount,null)) july,
	sum(if(month='8',usageCount,null)) august,
	sum(if(month='9',usageCount,null)) september,
	sum(if(month='10',usageCount,null)) october,
	sum(if(month='11',usageCount,null)) november,
	sum(if(month='12',usageCount,null)) december
	FROM title t, title_stats_monthly tsm, publisher_platform pp, publisher p
	where t.titleID = tsm.titleID
	and tsm.publisherPlatformID = pp.publisherPlatformID
	and pp.publisherID = p.publisherID
	" . $pubPlatSwitch . "
	and tsm.year='" . $year . "'
	and tsm.archiveInd = '" . $archiveInd . "';";

$result = mysql_query($query);

?>

<tr>
<td><b>Total for all journals</b></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<?php

echo "<td><b>" . mysql_result($result,0,'january') . "</b></td>";
echo "<td><b>" . mysql_result($result,0,'february') . "</b></td>";
echo "<td><b>" . mysql_result($result,0,'march') . "</b></td>";
echo "<td><b>" . mysql_result($result,0,'april') . "</b></td>";
echo "<td><b>" . mysql_result($result,0,'may') . "</b></td>";
echo "<td><b>" . mysql_result($result,0,'june') . "</b></td>";
echo "<td><b>" . mysql_result($result,0,'july') . "</b></td>";
echo "<td><b>" . mysql_result($result,0,'august') . "</b></td>";
echo "<td><b>" . mysql_result($result,0,'september') . "</b></td>";
echo "<td><b>" . mysql_result($result,0,'october') . "</b></td>";
echo "<td><b>" . mysql_result($result,0,'november') . "</b></td>";
echo "<td><b>" . mysql_result($result,0,'december') . "</b></td>";


mysql_free_result($result);

//get ytd data if available
if ($publisherPlatformID){
	$result = mysql_query("select sum(totalCount) totalCount, sum(HTMLCount) HTMLCount, sum(PDFCount) PDFCount
		from title_stats_ytd tsy, publisher_platform pp
		where pp.publisherPlatformID = tsy.publisherPlatformID
		and tsy.publisherPlatformID = '" . $publisherPlatformID . "'
		and archiveInd ='" . $archiveInd . "' and year='" . $year . "';");

}else{
	$result = mysql_query("select sum(totalCount) totalCount, sum(HTMLCount) HTMLCount, sum(PDFCount) PDFCount
		from title_stats_ytd tsy, publisher_platform pp
		where pp.publisherPlatformID = tsy.publisherPlatformID
		and pp.platformID = '" . $platformID . "'
		and archiveInd ='" . $archiveInd . "' and year='" . $year . "';");
}



echo "<td><b>" . mysql_result($result,0,'totalCount') . "</b></td>";
echo "<td><b>" . mysql_result($result,0,'htmlCount') . "</b></td>";
echo "<td><b>" . mysql_result($result,0,'pdfCount') . "</b></td>";

mysql_free_result($result);



$query = "SELECT pp.publisherPlatformID, t.titleID, t.title, publisher.name publisher, platform.name platform,
	max(if(month='1',usageCount,null)) january,
	max(if(month='2',usageCount,null)) february,
	max(if(month='3',usageCount,null)) march,
	max(if(month='4',usageCount,null)) april,
	max(if(month='5',usageCount,null)) may,
	max(if(month='6',usageCount,null)) june,
	max(if(month='7',usageCount,null)) july,
	max(if(month='8',usageCount,null)) august,
	max(if(month='9',usageCount,null)) september,
	max(if(month='10',usageCount,null)) october,
	max(if(month='11',usageCount,null)) november,
	max(if(month='12',usageCount,null)) december,
	max(if(month='1',outlierID,0)) january_outlier,
	max(if(month='2',outlierID,0)) february_outlier,
	max(if(month='3',outlierID,0)) march_outlier,
	max(if(month='4',outlierID,0)) april_outlier,
	max(if(month='5',outlierID,0)) may_outlier,
	max(if(month='6',outlierID,0)) june_outlier,
	max(if(month='7',outlierID,0)) july_outlier,
	max(if(month='8',outlierID,0)) august_outlier,
	max(if(month='9',outlierID,0)) september_outlier,
	max(if(month='10',outlierID,0)) october_outlier,
	max(if(month='11',outlierID,0)) november_outlier,
	max(if(month='12',outlierID,0)) december_outlier,
	max(mergeInd) mergeInd
	FROM title t, title_stats_monthly tsm, publisher_platform pp, publisher, platform
	where t.titleID = tsm.titleID
	and tsm.publisherPlatformID = pp.publisherPlatformID
	and pp.publisherID = publisher.publisherID
	and pp.platformID = platform.platformID
	" . $pubPlatSwitch . "
	and tsm.year='" . $year . "'
	and tsm.archiveInd = '" . $archiveInd . "'
	group by t.titleID, t.title
	order by t.title;";

$result = mysql_query($query);





while ($row = mysql_fetch_assoc($result)) {
	if ($row['mergeInd'] == "1") {
		echo "<tr bgcolor='lightgrey'>";
	}else{
		echo "<tr>";
	}
	echo "<td>" . $row['title'] . "</td>";
	echo "<td>" . $row['publisher'] . "</td>";
	echo "<td>" . $row['platform'] . "</td>";


	//get print issn
	$issn_result = mysql_query("select issn from title_issn where ISSNType='print' and titleID = '" . $row['titleID'] . "';");
	$print_issn='';

	while ($issn_row = mysql_fetch_assoc($issn_result)) {
		$print_issn = substr($issn_row['issn'],0,4) . "-" . substr($issn_row['issn'],4,4);
	}
	mysql_free_result($issn_result);

	echo "<td>" . $print_issn . "</td>";

	//get online issn
	$issn_result = mysql_query("select issn from title_issn where ISSNType='online' and titleID = '" . $row['titleID'] . "';");
	$online_issn='';

	while ($issn_row = mysql_fetch_assoc($issn_result)) {
		$online_issn = substr($issn_row['issn'],0,4) . "-" . substr($issn_row['issn'],4,4);
	}
	mysql_free_result($issn_result);

	echo "<td>" . $online_issn . "</td>";

	echo "<td bgcolor='" . $outlier[$row['january_outlier']]['color'] . "'>" . $row['january'] . "</td>";
	echo "<td bgcolor='" . $outlier[$row['february_outlier']]['color'] . "'>" . $row['february'] . "</td>";
	echo "<td bgcolor='" . $outlier[$row['march_outlier']]['color'] . "'>" . $row['march'] . "</td>";
	echo "<td bgcolor='" . $outlier[$row['april_outlier']]['color'] . "'>" . $row['april'] . "</td>";
	echo "<td bgcolor='" . $outlier[$row['may_outlier']]['color'] . "'>" . $row['may'] . "</td>";
	echo "<td bgcolor='" . $outlier[$row['june_outlier']]['color'] . "'>" . $row['june'] . "</td>";
	echo "<td bgcolor='" . $outlier[$row['july_outlier']]['color'] . "'>" . $row['july'] . "</td>";
	echo "<td bgcolor='" . $outlier[$row['august_outlier']]['color'] . "'>" . $row['august'] . "</td>";
	echo "<td bgcolor='" . $outlier[$row['september_outlier']]['color'] . "'>" . $row['september'] . "</td>";
	echo "<td bgcolor='" . $outlier[$row['october_outlier']]['color'] . "'>" . $row['october'] . "</td>";
	echo "<td bgcolor='" . $outlier[$row['november_outlier']]['color'] . "'>" . $row['november'] . "</td>";
	echo "<td bgcolor='" . $outlier[$row['december_outlier']]['color'] . "'>" . $row['december'] . "</td>";



	//get ytd data if available
	$ytd_result = mysql_query("select totalCount, HTMLCount, PDFCount from title_stats_ytd where titleID = '" . $row['titleID'] . "' and archiveInd ='" . $archiveInd . "' and year='" . $year . "' and publisherPlatformID = '" . $row['publisherPlatformID'] . "';");
	$totalCount='';
	$htmlCount='';
	$pdfCount='';

	while ($ytd_row = mysql_fetch_assoc($ytd_result)) {
		$totalCount = $ytd_row['totalCount'];
		$htmlCount = $ytd_row['HTMLCount'];
		$pdfCount = $ytd_row['PDFCount'];
	}
	mysql_free_result($ytd_result);

	echo "<td>" . $totalCount . "</td>";
	echo "<td>" . $htmlCount . "</td>";
	echo "<td>" . $pdfCount . "</td>";


	echo "</tr>";
}

mysql_free_result($result);


?>

</tr>
</table>

</body>
</html>
