<?php
include_once 'directory.php';
include_once 'user.php';

$year = $_GET['year'];
$publisherPlatformID = $_GET['publisherPlatformID'];
$platformID = $_GET['platformID'];
$archiveInd = $_GET['archiveInd'];

if ($archiveInd == '1') {
	$archive='Archive';
}else{
	$archive='';
}

//determine config settings for outlier usage
$config = new Configuration();
$outlier = array();
$outlier[0]['color']='';

if ($config->settings->useOutliers == "Y"){
	$outliers = new Outlier();
	$outlierArray = array();

	foreach($outliers->allAsArray as $outlierArray) {
		$outlier[$outlierArray['outlierID']]['color'] = $outlierArray['color'];
	}
}



if ($publisherPlatformID){
	$obj = new PublisherPlatform(new NamedArguments(array('primaryKey' => $publisherPlatformID)));
}else if ($platformID){
	$obj = new Platform(new NamedArguments(array('primaryKey' => $platformID)));
}

$display_name = $obj->reportDisplayName;


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


//Add a line for totals at top (to mimic counter compliant reports)
$totalArray = array();
$totalArray = $obj->getStatMonthlyTotals($archiveInd, $year);


?>

<tr>
<td><b>Total for all journals</b></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<?php

echo "<td><b>" . $totalArray['january'] . "</b></td>";
echo "<td><b>" . $totalArray['february'] . "</b></td>";
echo "<td><b>" . $totalArray['march'] . "</b></td>";
echo "<td><b>" . $totalArray['april'] . "</b></td>";
echo "<td><b>" . $totalArray['may'] . "</b></td>";
echo "<td><b>" . $totalArray['june'] . "</b></td>";
echo "<td><b>" . $totalArray['july'] . "</b></td>";
echo "<td><b>" . $totalArray['august'] . "</b></td>";
echo "<td><b>" . $totalArray['september'] . "</b></td>";
echo "<td><b>" . $totalArray['october'] . "</b></td>";
echo "<td><b>" . $totalArray['november'] . "</b></td>";
echo "<td><b>" . $totalArray['december'] . "</b></td>";



//get ytd data if available
$totalArray = array();
$totalArray = $obj->getStatYearlyTotals($archiveInd, $year);

if (isset($totalArray['totalCount'])) $totalCount = $totalArray['totalCount']; else $totalCount = '';
if (isset($totalArray['ytdHTMLCount'])) $ytdHTMLCount = $totalArray['ytdHTMLCount']; else $ytdHTMLCount = '';
if (isset($totalArray['ytdPDFCount'])) $ytdPDFCount = $totalArray['ytdPDFCount']; else $ytdPDFCount = '';

echo "<td><b>" . $totalCount . "</b></td>";
echo "<td><b>" . $ytdHTMLCount . "</b></td>";
echo "<td><b>" . $ytdPDFCount . "</b></td>";




$titleStatArray = array();
foreach($obj->getMonthlyStats($archiveInd, $year) as $titleStatArray) {
	$title = new Title(new NamedArguments(array('primaryKey' => $titleStatArray['titleID'])));

	if ($titleStatArray['mergeInd'] == "1") {
		echo "<tr bgcolor='lightgrey'>";
	}else{
		echo "<tr>";
	}
	echo "<td>" . $titleStatArray['Title'] . "</td>";
	echo "<td>" . $titleStatArray['Publisher'] . "</td>";
	echo "<td>" . $titleStatArray['Platform'] . "</td>";

	//get print issn
	echo "<td>" . $title->getPrintISSN() . "</td>";

	//get online issn
	echo "<td>" . $title->getOnlineISSN() . "</td>";


	echo "<td bgcolor='" . $outlier[$titleStatArray['january_outlier']]['color'] . "'>" . $titleStatArray['january'] . "</td>";
	echo "<td bgcolor='" . $outlier[$titleStatArray['february_outlier']]['color'] . "'>" . $titleStatArray['february'] . "</td>";
	echo "<td bgcolor='" . $outlier[$titleStatArray['march_outlier']]['color'] . "'>" . $titleStatArray['march'] . "</td>";
	echo "<td bgcolor='" . $outlier[$titleStatArray['april_outlier']]['color'] . "'>" . $titleStatArray['april'] . "</td>";
	echo "<td bgcolor='" . $outlier[$titleStatArray['may_outlier']]['color'] . "'>" . $titleStatArray['may'] . "</td>";
	echo "<td bgcolor='" . $outlier[$titleStatArray['june_outlier']]['color'] . "'>" . $titleStatArray['june'] . "</td>";
	echo "<td bgcolor='" . $outlier[$titleStatArray['july_outlier']]['color'] . "'>" . $titleStatArray['july'] . "</td>";
	echo "<td bgcolor='" . $outlier[$titleStatArray['august_outlier']]['color'] . "'>" . $titleStatArray['august'] . "</td>";
	echo "<td bgcolor='" . $outlier[$titleStatArray['september_outlier']]['color'] . "'>" . $titleStatArray['september'] . "</td>";
	echo "<td bgcolor='" . $outlier[$titleStatArray['october_outlier']]['color'] . "'>" . $titleStatArray['october'] . "</td>";
	echo "<td bgcolor='" . $outlier[$titleStatArray['november_outlier']]['color'] . "'>" . $titleStatArray['november'] . "</td>";
	echo "<td bgcolor='" . $outlier[$titleStatArray['december_outlier']]['color'] . "'>" . $titleStatArray['december'] . "</td>";



	//get ytd data if available
	$totalCount='';
	$ytdHTMLCount='';
	$ytdPDFCount='';


	$titleYearlyStatArray = array();
	foreach($title->getYearlyStats($archiveInd, $year, $titleStatArray['publisherPlatformID']) as $titleYearlyStatArray) {
		$totalCount = $titleYearlyStatArray['totalCount'];
		$ytdHTMLCount = $titleYearlyStatArray['ytdHTMLCount'];
		$ytdPDFCount = $titleYearlyStatArray['ytdPDFCount'];
	}

	echo "<td>" . $totalCount . "</td>";
	echo "<td>" . $ytdHTMLCount . "</td>";
	echo "<td>" . $ytdPDFCount . "</td>";


	echo "</tr>";
}



?>

</tr>
</table>

</body>
</html>
