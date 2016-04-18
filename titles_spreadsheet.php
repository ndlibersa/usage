<?php
include_once 'directory.php';
$titleArray = array();
$resourceType = $_GET['resourceType'];

if (isset($_GET['publisherPlatformID']) && ($_GET['publisherPlatformID'] != '')){
	$publisherPlatformID = $_GET['publisherPlatformID'];
	$platformID = '';
	$obj = new PublisherPlatform(new NamedArguments(array('primaryKey' => $_GET['publisherPlatformID'])));
}else{
	$platformID = $_GET['platformID'];
	$publisherPlatformID = '';
	$obj = new Platform(new NamedArguments(array('primaryKey' => $_GET['platformID'])));
}


$display_name = $obj->reportDisplayName;


$excelfile = $display_name . "_" . $resourceType . "_Titles";


$excelfile = str_replace (' ','_',$excelfile) . '.xls';

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=" . $excelfile);

?>

<html>
<head>
</head>
<body>

<h2> <?php echo $display_name . " " . $resourceType . _(" Titles ");?></h2>

<?php
if ($resourceType == "Journal"){
	$titleArray = $obj->getJournalTitles;
}else if ($resourceType == "Book"){
	$titleArray = $obj->getBookTitles;
}
?>

<table border='1'>
<tr>
<th>Title</th>
<?php if ($resourceType == "Journal"){ ?>
	<th><?php echo _("DOI");?></th>
	<th><?php echo _("Print ISSN");?></th>
	<th><?php echo _("Online ISSN");?></th>
<?php } else if ($resourceType == "Book") {?>
	<th><?php echo _("DOI");?></th>
	<th><?php echo _("ISBN");?></th>
	<th><?php echo _("ISSN");?></th>
<?php }

echo "</tr>";

if ($resourceType == "Journal"){

	foreach($titleArray as $title) {

		echo "\n<tr>";
		echo "\n<td>" . $title['title'] . "</td>";

		//get the first Identifier to use for the terms tool lookup
		$doi = $title['doi'];
		$issn = $title['issn'];
		$eissn = $title['eissn'];

		echo "\n<td>" . $doi . "</td>";
		echo "\n<td>" . $issn . "</td>";
		echo "\n<td>" . $eissn . "</td>";
		

		echo "</tr>";

	#end Title loop
	}
}else{

	foreach($titleArray as $title) {

		echo "\n<tr>";

		echo "\n<td>" . $title['title'] . "</td>";

		//get the first Identifier to use for the terms tool lookup
		$doi = $title['doi'];
		$isbn = $title['isbn'];
		$issn = $title['issn'];

		echo "\n<td>" . $doi . "</td>";
		echo "\n<td>'" . $isbn . "</td>";
		echo "\n<td>" . $issn . "</td>";


		echo "</tr>";

	#end Title loop
	}

}
?>

</table>
</body>
</html>
