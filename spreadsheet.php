<?php
include_once 'directory.php';

$year = $_GET['year'];
$publisherPlatformID = $_GET['publisherPlatformID'];
$platformID = $_GET['platformID'];
$archiveInd = $_GET['archiveInd'];
$resourceType = $_GET['resourceType'];

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


$excelfile = $display_name . "_" . $resourceType . "_" . $year;


$excelfile = str_replace (' ','_',$excelfile) . '.xls';

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=" . $excelfile);

?>

<html>
<head>
</head>
<body>

<h2> <?php echo $display_name . " " . $resourceType . " " . $archive . " - " . $year; ?> </h2>
<table border='1'>
<tr>
<th>&nbsp;</th>
<th><?php echo _("Publisher");?></th>
<th><?php echo _("Platform");?></th>
<?php if ($resourceType == "Journal"){ ?>
	<th><?php echo _("DOI");?></th>
	<th><?php echo _("Proprietary ID");?></th>
	<th><?php echo _("Print ISSN");?></th>
	<th><?php echo _("Online ISSN");?></th>
	<th><?php echo _("YTD Total");?></th>
	<th><?php echo _("YTD HTML");?></th>
	<th><?php echo _("YTD PDF");?></th>
	<th><?php echo _("Jan-") . $year;?></th>
	<th><?php echo _("Feb-") . $year;?></th>
	<th><?php echo _("Mar-") . $year;?></th>
	<th><?php echo _("Apr-") . $year;?></th>
	<th><?php echo _("May-") . $year;?></th>
	<th><?php echo _("Jun-") . $year;?></th>
	<th><?php echo _("Jul-") . $year;?></th>
	<th><?php echo _("Aug-") . $year;?></th>
	<th><?php echo _("Sep-") . $year;?></th>
	<th><?php echo _("Oct-") . $year;?></th>
	<th><?php echo _("Nov-") . $year;?></th>
	<th><?php echo _("Dec-") . $year;?></th>
<?php } else if ($resourceType == "Book") {?>
	<th><?php echo _("DOI");?></th>
	<th><?php echo _("Proprietary ID");?></th>
	<th><?php echo _("ISBN");?></th>
	<th><?php echo _("ISSN");?></th>
	<th><?php echo _("YTD Total");?></th>
	<th><?php echo _("Jan-") . $year;?></th>
	<th><?php echo _("Feb-") . $year;?></th>
	<th><?php echo _("Mar-") . $year;?></th>
	<th><?php echo _("Apr-") . $year;?></th>
	<th><?php echo _("May-") . $year;?></th>
	<th><?php echo _("Jun-") . $year;?></th>
	<th><?php echo _("Jul-") . $year;?></th>
	<th><?php echo _("Aug-") . $year;?></th>
	<th><?php echo _("Sep-") . $year;?></th>
	<th><?php echo _("Oct-") . $year;?></th>
	<th><?php echo _("Nov-") . $year;?></th>
	<th><?php echo _("Dec-") . $year;?></th>
<?php } else if ($resourceType == "Database") {?>
	<th><?php echo _("User Activity");?></th>
	<th><?php echo _("YTD Total");?></th>
	<th><?php echo _("Jan-") . $year;?></th>
	<th><?php echo _("Feb-") . $year;?></th>
	<th><?php echo _("Mar-") . $year;?></th>
	<th><?php echo _("Apr-") . $year;?></th>
	<th><?php echo _("May-") . $year;?></th>
	<th><?php echo _("Jun-") . $year;?></th>
	<th><?php echo _("Jul-") . $year;?></th>
	<th><?php echo _("Aug-") . $year;?></th>
	<th><?php echo _("Sep-") . $year;?></th>
	<th><?php echo _("Oct-") . $year;?></th>
	<th><?php echo _("Nov-") . $year;?></th>
	<th><?php echo _("Dec-") . $year;?></th>
<?php 
}

echo "</tr>";


//Add a line for totals at top (to mimic counter compliant reports)
echo "<tr>";
if ($resourceType == 'Journal'){
	echo "<td colspan = '7'><b>" . _("Total for all Journals") . "</b></td>";
}else if ($resourceType == 'Book'){
	echo "<td colspan = '7'><b>" . _("Total for all Books") . "</b></td>";
}else if ($resourceType == 'Database'){
	echo "<td colspan = '4'><b>" . _("Total for all Databases") . "</b></td>";
}

//get ytd data if available
$totalArray = array();
$totalArray = $obj->getStatYearlyTotals($resourceType, $archiveInd, $year);

if (isset($totalArray['totalCount'])) $totalCount = $totalArray['totalCount']; else $totalCount = '';
if (isset($totalArray['ytdHTMLCount'])) $ytdHTMLCount = $totalArray['ytdHTMLCount']; else $ytdHTMLCount = '';
if (isset($totalArray['ytdPDFCount'])) $ytdPDFCount = $totalArray['ytdPDFCount']; else $ytdPDFCount = '';

if ($resourceType == 'Journal'){
	echo "<td><b>" . $totalCount . "</b></td>";
	echo "<td><b>" . $ytdHTMLCount . "</b></td>";
	echo "<td><b>" . $ytdPDFCount . "</b></td>";
}else if (strpos($resourceType,'Book') == "1"){
	echo "<td><b>" . $totalCount . "</b></td>";
}else{
	echo "<td><b>" . $totalCount . "</b></td>";
}

$totalArray = array();
$totalArray = $obj->getStatMonthlyTotals($resourceType, $archiveInd, $year);

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






$titleStatArray = array();
foreach($obj->getMonthlyStats($resourceType, $archiveInd, $year) as $titleStatArray) {
	$title = new Title(new NamedArguments(array('primaryKey' => $titleStatArray['titleID'])));

	//get ytd data if available
	$totalCount='';
	$ytdHTMLCount='';
	$ytdPDFCount='';

	$titleYearlyStatArray = array();
	foreach($title->getYearlyStats($archiveInd, $year, $titleStatArray['publisherPlatformID'], $titleStatArray['activityType']) as $titleYearlyStatArray) {
		$totalCount = $titleYearlyStatArray['totalCount'];
		$ytdHTMLCount = $titleYearlyStatArray['ytdHTMLCount'];
		$ytdPDFCount = $titleYearlyStatArray['ytdPDFCount'];
	}

	if ($titleStatArray['mergeInd'] == "1") {
		echo "<tr bgcolor='lightgrey'>";
	}else{
		echo "<tr>";
	}
	echo "<td>" . $titleStatArray['Title'] . "</td>";
	echo "<td>" . $titleStatArray['Publisher'] . "</td>";
	echo "<td>" . $titleStatArray['Platform'] . "</td>";

	if ($resourceType == 'Journal'){
		echo "<td>" . $title->getIdentifier('DOI') . "</td>";
		echo "<td>" . $title->getIdentifier('Proprietary Identifier') . "</td>";
		echo "<td>" . $title->getIdentifier('ISSN') . "</td>";
		echo "<td>" . $title->getIdentifier('eISSN') . "</td>";
		echo "<td>" . $totalCount . "</td>";
		echo "<td>" . $ytdHTMLCount . "</td>";
		echo "<td>" . $ytdPDFCount . "</td>";
	}else if ($resourceType == 'Book'){
		echo "<td>" . $title->getIdentifier('DOI') . "</td>";
		echo "<td>" . $title->getIdentifier('Proprietary Identifier') . "</td>";
		echo "<td>" . $title->getIdentifier('ISBN') . "</td>";
		echo "<td>" . $title->getIdentifier('ISSN') . "</td>";
		echo "<td>" . $totalCount . "</td>";
	}else if ($resourceType == 'Database'){
		echo "<td>" . $titleStatArray['activityType'] . "</td>";
		echo "<td>" . $totalCount . "</td>";
	}

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

	echo "</tr>";
}



?>

</tr>
</table>

</body>
</html>
