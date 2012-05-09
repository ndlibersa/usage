<?php
/*
**************************************************************************************************************************
** CORAL Usage Statistics Module v. 1.0
**
** Copyright (c) 2010 University of Notre Dame
**
** This file is part of CORAL.
**
** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
**
** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
**
**************************************************************************************************************************
**************************************************************************************************************************
** ajax_htmldata.php formats display data for all pages - tables and search results
**
** when ajax_htmldata.php is called through ajax, 'action' parm is required to dictate which data will be returned
**
**************************************************************************************************************************
*/


include_once 'directory.php';
include_once 'user.php';
include "common.php";

$action = $_REQUEST['action'];

switch ($action) {



    case 'getLoginDetails':

		if (isset($_GET['publisherPlatformID']) && ($_GET['publisherPlatformID'] != '')){
	    	$publisherPlatform = new PublisherPlatform(new NamedArguments(array('primaryKey' => $_GET['publisherPlatformID'])));

			?>

			<h3>Publisher Logins</h3>

			<div id="div_logins">

			<?php


			$externalLoginArray = array();
			$externalLoginArray = $publisherPlatform->getExternalLogins();
			$externalLogin = new ExternalLogin();


			if (count($externalLoginArray) > 0){
			?>

			<table class='verticalFormTable'>
			<tr>
			<th>Interface Login</th>
			<th>Password</th>
			<th>URL</th>
			<th>Login Notes</th>
			<th>&nbsp;</th>
			</tr>

			<?php

			foreach($externalLoginArray as $externalLogin) {
				echo "<tr>";
				echo "<td>" . $externalLogin->username . "</td>";
				echo "<td>" . $externalLogin->password . "</td>";
				echo "<td>" . $externalLogin->loginURL . "</td>";
				echo "<td>" . $externalLogin->noteText . "</td>";
				echo "<td><a href='ajax_forms.php?action=getLoginForm&externalLoginID=" . $externalLogin->externalLoginID . "&height=250&width=325&modal=true' class='thickbox' style='font-size:100%;'>edit</a><br /><a href='javascript:deleteExternalLogin(" . $externalLogin->externalLoginID . ");' style='font-size:100%;'>remove</a></td>";
				echo "</tr>";

			}

			?>
			</table>

			<?php
			}else{
				echo "(none found)";
			}
			?>


			</div>

			<br />
			<a href='ajax_forms.php?action=getLoginForm&publisherPlatformID=<?php echo $publisherPlatform->publisherPlatformID; ?>&height=250&width=325&modal=true' class='thickbox' id='uploadDocument'>add new login</a>


		<?php
		//Platform record
		}else{
			$platform = new Platform(new NamedArguments(array('primaryKey' => $_GET['platformID'])));

			?>


			<h3>Interface Logins</h3>

			<div id="div_logins">

			<?php


			$externalLoginArray = array();
			$externalLoginArray = $platform->getExternalLogins();
			$externalLogin = new ExternalLogin();


			if (count($externalLoginArray) > 0){
			?>

			<table class='verticalFormTable'>
			<tr>
			<th>Interface Login</th>
			<th>Password</th>
			<th>URL</th>
			<th>Login Notes</th>
			<th>&nbsp;</th>
			</tr>

			<?php

			foreach($externalLoginArray as $externalLogin) {
				echo "<tr>";
				echo "<td>" . $externalLogin->username . "</td>";
				echo "<td>" . $externalLogin->password . "</td>";
				echo "<td>" . $externalLogin->loginURL . "</td>";
				echo "<td>" . $externalLogin->noteText . "</td>";
				echo "<td><a href='ajax_forms.php?action=getLoginForm&externalLoginID=" . $externalLogin->externalLoginID . "&height=250&width=325&modal=true' class='thickbox' style='font-size:100%;'>edit</a><br /><a href='javascript:deleteExternalLogin(" . $externalLogin->externalLoginID . ");' style='font-size:100%;'>remove</a></td>";
				echo "</tr>";

			}

			?>
			</table>

			<?php
			}else{
				echo "(none found)";
			}
			?>


			</div>

			<br />
			<a href='ajax_forms.php?action=getLoginForm&platformID=<?php echo $platform->platformID; ?>&height=250&width=325&modal=true' class='thickbox' id='uploadDocument'>add new login</a>

		<?php
		}

    	$config = new Configuration();
    	$util = new Utility();

		//both publishers and platforms will have organizations lookup
		if ($config->settings->organizationsModule == 'Y'){
			echo "<br /><br /><br /><h3>Organization Accounts</h3>";

			if (isset($_GET['publisherPlatformID']) && ($_GET['publisherPlatformID'] != '')){
				$publisherPlatformID = $_GET['publisherPlatformID'];
				$platformID = '';
				$obj = new PublisherPlatform(new NamedArguments(array('primaryKey' => $_GET['publisherPlatformID'])));
			}else{
				$publisherPlatformID = '';
				$platformID = $_GET['platformID'];
				$obj = new Platform(new NamedArguments(array('primaryKey' => $_GET['platformID'])));
			}


			//if this publisher platform is already set up with an organization
			if (($obj->organizationID != '') && ($obj->organizationID != 0)){

				$externalLoginArray = $obj->getOrganizationExternalLogins();

				if (count($externalLoginArray) > 0){
					?>
					For <?php echo $obj->getOrganizationName() . "&nbsp;&nbsp;<a href='" . $util->getOrganizationURL() . $obj->organizationID . "' target='_blank'>view organization</a>"; ?>
					<table class='verticalFormTable'>
					<tr>
					<th>Login Type</th>
					<th>Username</th>
					<th>Password</th>
					<th>URL</th>
					<th>Notes</th>
					</tr>

					<?php
					foreach ($externalLoginArray as $externalLogin){
						echo "<tr>";
						echo "<td>" . $externalLogin['externalLoginType'] . "</td>";
						echo "<td>" . $externalLogin['username'] . "</td>";
						echo "<td>" . $externalLogin['password'] . "</td>";
						echo "<td>" . $externalLogin['loginURL'] . "</td>";
						echo "<td>" . $externalLogin['noteText'] . "</td>";
						echo "</tr>";

					}
					echo "</table>";

				}else{
					echo "<i>No login information stored for " . $obj->getOrganizationName . "</i>&nbsp;&nbsp;<a href='" . $util->getOrganizationURL() . $obj->organizationID . "' target='_blank'>view organization</a>";
				}

				?>
				<br />
				<a href='ajax_forms.php?action=getOrganizationForm&platformID=<?php echo $platformID; ?>&publisherPlatformID=<?php echo $publisherPlatformID; ?>&height=150&width=285&modal=true' class='thickbox'>change associated organization</a>
				<br />
				<?php

			//display form for adding organizations
			}else{
				?>

					<br />
					<a href='ajax_forms.php?action=getOrganizationForm&platformID=<?php echo $platformID; ?>&publisherPlatformID=<?php echo $publisherPlatformID; ?>&height=150&width=285&modal=true' class='thickbox'>link to associated organization</a>


				<?php
			}


			//additionally, display any login records belonging to publishers below this platform
			if (isset($_GET['platformID']) && ($_GET['platformID'] != '')){


				$pubArray = array();
				foreach ($platform->getPublisherPlatforms() as $publisherPlatform){
					$orgArray = $publisherPlatform->getOrganizationExternalLogins();
					$externalLoginArray = $publisherPlatform->getExternalLogins();

					if ((count($orgArray) > 0) || (count($externalLoginArray) > 0)){
						$pub = new Publisher(new NamedArguments(array('primaryKey' => $publisherPlatform->publisherID)));
						$pubArray[$publisherPlatform->publisherID] = $pub->name;
					}

				}

				if (count($pubArray) > 0){
					echo "<br />";
					echo "Login Credentials are also available for the following publishers:<br />";

					foreach ($pubArray as $pubID => $pubName){
						echo "<a href='publisherPlatform.php?publisherPlatformID=" . $pubID . "'>" . $pubName . "</a><br />";
					}

				}


			}

		}


        break;





    case 'getNotesDetails':

		if (isset($_GET['publisherPlatformID']) && ($_GET['publisherPlatformID'] != '')){
	    	$publisherPlatform = new PublisherPlatform(new NamedArguments(array('primaryKey' => $_GET['publisherPlatformID'])));

			?>

			<h3>Publisher Notes</h3>

			<div id="div_noteText">

			<?php

			$publisherPlatformNoteArray = array();
			$publisherPlatformNoteArray = $publisherPlatform->getPublisherPlatformNotes();
			$publisherPlatformNote = new PublisherPlatformNote();

			if (count($publisherPlatformNoteArray) > 0){
			?>

			<table class='verticalFormTable'>
			<tr>
			<th>Start Year</th>
			<th>End Year</th>
			<th>Notes</th>
			<th>&nbsp;</th>
			</tr>

			<?php

			foreach($publisherPlatformNoteArray as $publisherPlatformNote) {
				if (($publisherPlatformNote->endYear == '0') || ($publisherPlatformNote->endYear =='')) $endYear = 'Present'; else $endYear = $publisherPlatformNote->endYear;

				echo "<tr>";
				echo "<td>" . $publisherPlatformNote->startYear . "</td>";
				echo "<td>" . $endYear . "</td>";
				echo "<td>" . $publisherPlatformNote->noteText . "</td>";
				echo "<td><a href='ajax_forms.php?action=getPublisherNoteForm&publisherPlatformNoteID=" . $publisherPlatformNote->publisherPlatformNoteID . "&height=225&width=313&modal=true' class='thickbox' style='font-size:100%;'>edit</a><br /><a href='javascript:deletePublisherNote(" . $publisherPlatformNote->publisherPlatformNoteID . ");' style='font-size:100%;'>remove</a></td>";
				echo "</tr>";

			}

			?>
			</table>

			<?php }else{ echo "(none found)"; } ?>
			</div>

			<br />

			<a href='ajax_forms.php?action=getPublisherNoteForm&publisherPlatformNoteID=&publisherPlatformID=<?php echo $publisherPlatform->publisherPlatformID; ?>&height=225&width=313&modal=true' class='thickbox' id='uploadDocument'>add new publisher notes</a>


			<br />
			<br />

		<?php
		//Platform record
		}else{
			$platform = new Platform(new NamedArguments(array('primaryKey' => $_GET['platformID'])));

			?>

			<h3>Interface Notes</h3>

			<div id="div_interfaces">

			<?php



			$platformNoteArray = array();
			$platformNoteArray = $platform->getPlatformNotes();
			$platformNote = new PlatformNote();

			if (count($platformNoteArray) > 0){

			?>

			<table class='verticalFormTable'>
			<tr>
			<th>Start Year</th>
			<th>End Year</th>
			<th>Counter<br />Compliant?</th>
			<th>Interface Notes</th>
			<th>&nbsp;</th>
			</tr>

			<?php

			foreach($platformNoteArray as $platformNote) {
				if ($platformNote->counterCompliantInd == "1") {
					$counterCompliantInd = 'Y';
				}elseif ($platformNote->counterCompliantInd == "0"){
					$counterCompliantInd = 'N';
				}else{
					$counterCompliantInd = '';
				}
				if (($platformNote->endYear == '0') || ($platformNote->endYear =='')) $endYear = 'Present'; else $endYear = $platformNote->endYear;


				echo "<tr>";
				echo "<td>" . $platformNote->startYear . "</td>";
				echo "<td>" . $endYear . "</td>";
				echo "<td>" . $counterCompliantInd . "</td>";
				echo "<td>" . $platformNote->noteText . "</td>";
				echo "<td><a href='ajax_forms.php?action=getPlatformNoteForm&platformNoteID=" . $platformNote->platformNoteID . "&height=255&width=408&modal=true' class='thickbox' style='font-size:100%;'>edit</a><br /><a href='javascript:deletePlatformNote(" . $platformNote->platformNoteID . ");' style='font-size:100%;'>remove</a></td>";
				echo "</tr>";

			}

			?>
			</table>

			<?php }else{ echo "(none found)"; } ?>
			</div>

			<br />

			<a href='ajax_forms.php?action=getPlatformNoteForm&platformNoteID=&platformID=<?php echo $platform->platformID; ?>&height=255&width=408&modal=true' class='thickbox' id='addInterface'>add new interface note</a>

			<br />
			<br />

		<?php
		}

        break;


	case 'getStatsTable':

		$publisherPlatformID = $_GET['publisherPlatformID'];
		$platformID = $_GET['platformID'];
		$year = $_GET['year'];
		$archiveInd = $_GET['archiveInd'];

		$monthArray = array();
		if ($publisherPlatformID){
			$publisherPlatform = new PublisherPlatform(new NamedArguments(array('primaryKey' => $publisherPlatformID)));
			$monthArray = $publisherPlatform->getAvailableMonths($archiveInd, $year);
		}else{
			$platform = new Platform(new NamedArguments(array('primaryKey' => $platformID)));
			$monthArray = $platform->getAvailableMonths($archiveInd, $year);
		}

		foreach($monthArray as $month){

			if ($month['archiveInd'] == "1") {$archive = '&nbsp;(archive)';}else{$archive='';}

			echo "<label for='month' class='month'><b>" . numberToMonth($month['month']) . " " . $month['year'] . "</b> " . $archive . "</label>";
			echo "<label for='deleteStats' class='deleteStats'><a href=\"javascript:deleteMonth('" . $month['month'] . "','" . $month['year'] . "','" . $month['archiveInd'] . "', '" . $publisherPlatformID . "', '" . $platformID . "')\">delete entire month</a></label>";

			//monthly ouliers
			if ($publisherPlatformID){
				$outlierCount = count($publisherPlatform->getMonthlyOutliers($month['archiveInd'], $month['year'], $month['month']));
			}else{
				$outlierCount = count($platform->getMonthlyOutliers($month['archiveInd'], $month['year'], $month['month']));
			}


			if ($outlierCount != 0) {
				echo "<label for='outliers' class='outliers'><a href=\"javascript:popUp('outliers.php?publisherPlatformID=" . $publisherPlatformID . "&platformID=" . $platformID . "&archiveInd=" . $month['archiveInd'] . "&month=" . $month['month'] . "&year=" . $month['year'] . "');\">view outliers for this month</a></label>";
			}else{
				echo "<label for='outliers' class='outliers'>&nbsp;</label>";
			}

			echo "<br />";
		}


	break;


    case 'getFullStatsDetails':
		//determine config settings for outlier usage
		$config = new Configuration();

    	echo "<h3>Update Statistics</h3>";

		if (isset($_GET['publisherPlatformID']) && ($_GET['publisherPlatformID'] != '')){
			$publisherPlatformID = $_GET['publisherPlatformID'];
			$platformID = '';
		}else{
			$platformID = $_GET['platformID'];
			$publisherPlatformID = '';
		}

		$statsArray = array();
		if ($publisherPlatformID){
			$publisherPlatform = new PublisherPlatform(new NamedArguments(array('primaryKey' => $publisherPlatformID)));
			$statsArray = $publisherPlatform->getFullStatsDetails();
		}else{
			$platform = new Platform(new NamedArguments(array('primaryKey' => $platformID)));
			$statsArray = $platform->getFullStatsDetails();
		}


		echo "<table class='verticalFormTable' style='width:450px;'>";

		foreach($statsArray as $statArray){
			if ($statArray['archiveInd'] == "1") {$archive = '&nbsp;(archive)';}else{$archive='';}

			echo "<tr>";
			echo "<th><span style='font-weight:bold; font-size:120%;'>" . $statArray['year'] . $archive . "</span></td>";
			echo "<th><a target='_blank' href='spreadsheet.php?publisherPlatformID=" .  $publisherPlatformID . "&platformID=" . $platformID . "&year=" . $statArray['year'] . "&archiveInd=" . $statArray['archiveInd'] . "' style='font-size:110%;'>view spreadsheet</a></td>";
			echo "</tr>";

			//loop through each month
			$monthArray = array();
			$queryMonthArray = array();
			$queryMonthArray = explode(",",$statArray['months']);


			//we need to eliminate duplicates - mysql doesnt allow group inside group_concats
			foreach ($queryMonthArray as $resultMonth){
				$infoArray=array();
				$infoArray=explode("|",$resultMonth);

				$monthArray[$infoArray[0]] = $infoArray[1];
			}

			foreach ($monthArray as $month => $outlier){

				echo "<tr id='tr_" . $platformID . "_" . $publisherPlatformID . "_" . $statArray['year'] . "_" . $month . "_" . $statArray['archiveInd'] . "'>";
				echo "<td>&nbsp;</td>";
				echo "<td style='padding:0px;'>";
				echo "<table class='noBorderTable' style='width:340px;'>";
				echo "<tr>";
				echo "<td style='width:70px;font-weight:bold;'>" . numberToMonth($month) . " " . $statArray['year'] . "</td>";
				echo "<td><a href=\"javascript:deleteMonth('" . $month . "','" . $statArray['year'] . "','" . $statArray['archiveInd'] . "', '" . $publisherPlatformID . "', '" . $platformID . "')\" style='font-size:100%;'>delete entire month</a>";

				//print out prompt for outliers if outlierID is > 0
				if ($outlier > 0){
					echo "&nbsp;&nbsp;<a href='ajax_forms.php?action=getMonthlyOutlierForm&publisherPlatformID=" . $publisherPlatformID . "&platformID=" . $platformID . "&archiveInd=" . $statArray['archiveInd'] . "&month=" . $month . "&year=" . $statArray['year'] . "&height=340&width=415&modal=true' class='thickbox' style='font-size:100%;'>view outliers for this month</a>";
				}

				echo "</td></tr>";
				echo "</table>";

				echo "</td>";
				echo "</tr>";

			}

			if ($config->settings->useOutliers == "Y"){
				echo "<tr>";
				echo "<td>&nbsp;</td>";
				echo "<td><span style='font-weight:bold;'>YTD " . $statArray['year'] . "</span>";


				if ($statArray['outlierID'] > 0){
					echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='ajax_forms.php?action=getYearlyOverrideForm&publisherPlatformID=" . $publisherPlatformID . "&platformID=" . $platformID . "&archiveInd=" . $statArray['archiveInd'] . "&year=" . $statArray['year'] . "&height=340&width=415&modal=true' class='thickbox' style='font-size:100%;'>update overrides for this year</a>";
				}else{
					echo "&nbsp;&nbsp;&nbsp;&nbsp;(no outliers found for this year)";
				}

				echo "</td>";
				echo "</tr>";
			}

			echo "<tr><td colspan='2'>&nbsp;</td></tr>";


		}

		echo "</table>";

		break;

    case 'getTitleDetails':
		$titleArray = array();

		if (isset($_GET['publisherPlatformID']) && ($_GET['publisherPlatformID'] != '')){
			$publisherPlatformID = $_GET['publisherPlatformID'];
			$platformID = '';
			$publisherPlatform = new PublisherPlatform(new NamedArguments(array('primaryKey' => $_GET['publisherPlatformID'])));
			$titleArray = $publisherPlatform->getTitles();
		}else{
			$platformID = $_GET['platformID'];
			$publisherPlatformID = '';
			$platform = new Platform(new NamedArguments(array('primaryKey' => $_GET['platformID'])));
			$titleArray = $platform->getTitles();
		}


		//determine config settings for link resolver
		$config = new Configuration();
		$baseURL = $config->settings->baseURL;

		if (count($titleArray) == '0'){
			echo "(none found)";
		}else{
			?>

			<h3>Associated Titles and ISSNs</h3>

			<table class='verticalFormTable'>
			<tr>
				<th style='max-width:350px;'><b>Title</b></th>
				<th style='width:90px;'><b>Print ISSN</b></th>
				<th style='width:90px;'><b>Online ISSN</b></th>
				<th style='width:110px;'>&nbsp;</th>
			</tr>



			<?php
			foreach($titleArray as $title) {

				echo "\n<tr>";

				echo "\n<td>" . $title['title'] . "</td>";

				//get the first ISSN to use for the terms tool lookup
				$printISSN = $title['print_issn'];
				$onlineISSN = $title['online_issn'];

				echo "\n<td>" . $printISSN . "</td>";
				echo "\n<td>" . $onlineISSN . "</td>";


				if ((($printISSN) || ($onlineISSN)) && ($baseURL)){
					if (($printISSN) && !($onlineISSN)){
						$urlAdd = "&rft.issn=" . $printISSN;
					}else if (($printISSN) && ($onlineISSN)){
						$urlAdd = "&rft.issn=" . $printISSN . "&rft.eissn=" . $onlineISSN;
					}else{
						$urlAdd = "&rft.eissn=" . $onlineISSN;
					}


					$resolverURL = $config->settings->baseURL;

					//check if there is already a ? in the URL so that we don't add another when appending the parms
					if (strpos($resolverURL, "?") > 0){
						$resolverURL .= "&";
					}else{
						$resolverURL .= "?";
					}

					$resolverURL .= $urlAdd;

					echo "\n<td><span style='float:left;'><a href='ajax_forms.php?action=getRelatedTitlesForm&titleID=" . $title['titleID'] . "&height=240&width=258&modal=true' class='thickbox'>view related titles</a><br /><a href='" . $resolverURL  . "' target='_blank'>view in link resolver</a></span></td>";
				}else{
					echo "\n<td>&nbsp;</td>";
				}

				echo "</tr>";

				//////////////////////////////////////////////////////////////////////////////////////////////////////////
				//The following code is for modifying ISSNs - we decided against it but keep the code here in case we re-add it
				//echo "<td colspan='4'>";
				//echo "<table class='noBorderTable'>";
				//$titleISSN = new TitleISSN();
				//foreach($title->getISSNs as $titleISSN) {
				//	$displayISSN = substr($titleISSN->issn, 0, 4) . "-" . substr($titleISSN->issn, 4, 4);
				//	echo "<tr id='tr_" . $titleISSN->titleISSNID . "'>";
				//	echo "\n<td style='width:90px;' class='rightBorder'>" . $titleISSN->issnType . "</td>";
				//	echo "\n<td style='width:90px;' class='rightBorder'>" . $displayISSN . "</td>";
				//	echo "\n<td style='width:105px;' class='rightBorder'><a href=\"javascript:deleteISSN('" . $titleISSN->titleISSNID . "');\" style='font-size:100%;'>remove this issn</a></td>";
				//	echo "\n</tr>";
				//}
				//echo "\n<tr><td colspan='3'><a href='ajax_forms.php?action=getAddISSNForm&publisherPlatformID=" . $publisherPlatformID . "&platformID=" . $platformID . "&titleID=" . $title->titleID . "&height=140&width=205&modal=true' class='thickbox' style='font-size:100%;'>add issn</a>&nbsp;</td></tr>";
				//echo "</table></td></tr>";
				//////////////////////////////////////////////////////////////////////////////////////////////////////////

			#end Title loop
			}
			echo "</table>";
		}


		echo "<br /><br />";
		break;




    case 'getLogEmailAddressTable':

		$logEmailAddress = array();
		$logEmailAddresses = new LogEmailAddress();

		echo "<b>Current Email Addresses</b>";
		echo "<table class='dataTable' style='width:400px'>";

		foreach($logEmailAddresses->allAsArray as $logEmailAddress) {
			echo "<tr><td>" . $logEmailAddress['emailAddress'] . "</td>";
			echo "<td><a href='ajax_forms.php?action=getLogEmailAddressForm&height=122&width=248&logEmailAddressID=" . $logEmailAddress['logEmailAddressID'] . "&modal=true' class='thickbox'>edit</a></td>";
			echo "<td><a href='javascript:deleteLogEmailAddress(" . $logEmailAddress['logEmailAddressID'] . ");'>delete</a></td></tr>";
		}

		echo "</table>";
		echo "<br />";

        break;


    case 'getOutlierTable':

		//determine config settings for outlier usage
		$config = new Configuration();

		if ($config->settings->useOutliers == "Y"){

			$outlier = array();
			$outliers = new Outlier();

			echo "<b>Current Outlier Parameters</b><br />";

			foreach($outliers->allAsArray as $outlier) {
				echo "Level " . $outlier['outlierLevel'] . ": " . $outlier['overageCount'] . " over plus " .  $outlier['overagePercent'] . "% over - displayed " . $outlier['color'];
				echo "&nbsp;&nbsp;<a href='ajax_forms.php?action=getOutlierForm&height=162&width=308&outlierID=" . $outlier['outlierID'] . "&modal=true' class='thickbox'>edit</a>";
				echo "<br />";
			}
		}else{
			echo "Outliers are currently disabled in the configuration file.  Contact your technical support to enable them.";

		}

        break;




    case 'getMonthlyOutlierStatsTable':
		$publisherPlatformID = $_GET['publisherPlatformID'];
		$platformID = $_GET['platformID'];
		$archiveInd = $_GET['archiveInd'];
		$year = $_GET['year'];
		$month = $_GET['month'];

		$statsArray = array();
		if ($publisherPlatformID) {
			$publisherPlatform = new PublisherPlatform(new NamedArguments(array('primaryKey' => $publisherPlatformID)));
			$statsArray = $publisherPlatform->getMonthlyOutliers($archiveInd, $year, $month);
		}else{
			$platform = new Platform(new NamedArguments(array('primaryKey' => $platformID)));
			$statsArray = $platform->getMonthlyOutliers($archiveInd, $year, $month);
		}



		$totalRows = count($statsArray);

		echo "<table border='0' style='width:400px'>";

		if ($totalRows == 0){
			echo "<tr><td>None currently</td></tr>";
		}else{
			foreach($statsArray as $monthlyStat){
				echo "<tr>";
				echo "<td style='width:150px;'>" . $monthlyStat['Title']. "</td>";
				echo "<td style='width:50px;text-align:right;background-color:" . $monthlyStat['color'] . "'>" . $monthlyStat['usageCount'] . "</td>";
				echo "<td style='width:100px;'><input type='text' name = 'overrideUsageCount_" . $monthlyStat['monthlyUsageSummaryID'] . "' id = 'overrideUsageCount_" . $monthlyStat['monthlyUsageSummaryID'] . "' value='" . $monthlyStat['overrideUsageCount'] . "' style='width:50px'></td>";
				echo "<td style='width:50px;'><a href=\"javascript:updateOverride('" . $monthlyStat['monthlyUsageSummaryID'] . "');\">update override</a></td>";
				echo "<td style='width:50px;'><a href=\"javascript:ignoreOutlier('" . $monthlyStat['monthlyUsageSummaryID'] . "');\">ignore outlier</a></td>";
				echo "</tr>";
			}
		}

		echo "</table>";


        break;




    case 'getYearlyOverrideStatsTable':

		$publisherPlatformID  = $_GET['publisherPlatformID'];
		$platformID  = $_GET['platformID'];
		$archiveInd  = $_GET['archiveInd'];
		$year  = $_GET['year'];

		$statsArray = array();
		if ($publisherPlatformID) {
			$publisherPlatform = new PublisherPlatform(new NamedArguments(array('primaryKey' => $publisherPlatformID)));
			$statsArray = $publisherPlatform->getYearlyOverrides($archiveInd, $year);
		}else{
			$platform = new Platform(new NamedArguments(array('primaryKey' => $platformID)));
			$statsArray = $platform->getYearlyOverrides($archiveInd, $year);
		}



		?>

		<table border='0' style='width:400px'>

		<?php

		foreach($statsArray as $yearlyStat){
		?>
			<tr>
			<td width="149"><?php echo $yearly_stat['Title']; ?></td>
			<td width="40">Total<td>
			<td width="40" ><?php echo $yearly_stat['totalCount']; ?></td>
			<td width="40"><input name="overrideTotalCount_<?php echo $yearly_stat['yearlyUsageSummaryID']; ?>" id="overrideTotalCount_<?php echo $yearly_stat['yearlyUsageSummaryID']; ?>" type="text"value="<?php echo $yearly_stat['overrideTotalCount']; ?>" size="6" maxlength="6"/></td>
			<td width="40"><a href="javascript:updateYTDOverride('<?php echo $yearly_stat['yearlyUsageSummaryID']; ?>', 'overrideTotalCount')">update</a></td>
			</tr>
			<tr>
			<td width="149">&nbsp;</td>
			<td width="40">PDF<td>
			<td width="40"><?php echo $yearly_stat['ytdPDFCount']; ?></td>
			<td width="40"><input name="overridePDFCount_<?php echo $yearly_stat['yearlyUsageSummaryID']; ?>" id="overridePDFCount_<?php echo $yearly_stat['yearlyUsageSummaryID']; ?>" type="text"value="<?php echo $yearly_stat['overridePDFCount']; ?>" size="6" maxlength="6"/></td>
			<td width="40"><a href="javascript:updateYTDOverride('<?php echo $yearly_stat['yearlyUsageSummaryID']; ?>', 'overridePDFCount')">update</a></td>
			</tr>
			<tr>
			<td width="149">&nbsp;</td>
			<td width="40">HTML<td>
			<td width="40"><?php echo $yearly_stat['ytdHTMLCount']; ?></td>
			<td width="40"><input name="overrideHTMLCount_<?php echo $yearly_stat['yearlyUsageSummaryID']; ?>" id="overrideHTMLCount_<?php echo $yearly_stat['yearlyUsageSummaryID']; ?>" type="text"value="<?php echo $yearly_stat['overrideHTMLCount']; ?>" size="6" maxlength="6"/></td>
			<td width="40"><a href="javascript:updateYTDOverride('<?php echo $yearly_stat['yearlyUsageSummaryID']; ?>', 'overrideHTMLCount')">update</a></td>
			</tr>
		<?php

		}

		?>

		</table>

		<?php


        break;





    case 'getPlatformReportDisplay':
    	$platform = new Platform(new NamedArguments(array('primaryKey' => $_GET['platformID'])));

		if ($platform->reportDropDownInd == '1') { $reportDropDownInd = 'checked';}else{$reportDropDownInd = '';}

		echo "<input type='checkbox' id='chk_Platform_" . $platform->platformID  . "' onclick='javascript:updatePlatformDropDown(" . $platform->platformID  . ");' $reportDropDownInd>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<span class='PlatformText'>" . $platform->name . "</span>";

		if ($platform->reportDisplayName)  echo "&nbsp;&nbsp;(<i>" . $platform->reportDisplayName . "</i>)";
		echo "&nbsp;&nbsp;<a href='ajax_forms.php?action=getReportDisplayForm&height=122&width=248&type=Platform&updateID=" . $platform->platformID . "&modal=true' class='thickbox'>edit report display name</a><br />";



        break;





    case 'getPublisherReportDisplay':
    	$publisherPlatformID = $_GET['publisherPlatformID'];

    	$publisherPlatform = new PublisherPlatform(new NamedArguments(array('primaryKey' => $_GET['publisherPlatformID'])));
    	$publisher = new Publisher(new NamedArguments(array('primaryKey' => $publisherPlatform->publisherID)));


		$result = mysql_query("select distinct pp.publisherPlatformID, Publisher.name Publisher, pp.reportDisplayName reportPublisher, pp.reportDropDownInd from Publisher_Platform pp, Publisher where pp.publisherID = Publisher.publisherID and pp.publisherPlatformID = '" . $publisherPlatformID . "';");

		if ($publisherPlatform->reportDropDownInd == '1') { $reportDropDownInd = 'checked';}else{$reportDropDownInd = '';}

		echo "<table><tr valign='top'><td><input type='checkbox' id='chk_Publisher_" . $publisherPlatform->publisherPlatformID  . "' onclick='javascript:updatePublisherDropDown(" . $publisherPlatform->publisherPlatformID  . ");' $reportDropDownInd></td>";


		echo "<td>" . $publisher->name;
		if ($publisherPlatform->reportDisplayName)  echo "&nbsp;&nbsp;(<i>" . $publisherPlatform->reportDisplayName . "</i>)";
		echo "&nbsp;&nbsp;<a href='ajax_forms.php?action=getReportDisplayForm&height=122&width=248&type=Publisher&updateID=" . $publisherPlatform->publisherPlatformID . "&modal=true' class='thickbox'>edit report display name</a></td></tr></table>";


        break;





    case 'getImportTable':


		$pageStart = $_GET['pageStart'];
		$numberOfRecords = 20;
		$limit = $pageStart-1 . ", " . $numberOfRecords;

		$importLog = new ImportLog();

		$totalRecords = count($importLog->getImportLogRecords(''));


		$importLogArray = $importLog->getImportLogRecords($limit);

		$recordCount = count($importLogArray);

		if ($totalRecords == 0){
			echo "<i>No imports found.</i>";

		}else{

			$thisPageNum = $recordCount + $pageStart - 1;

			echo "<span style='font-weight:bold;'>Displaying " . $pageStart . " to " . $thisPageNum . " of " . $totalRecords . " Records</span><br />";

			//print out page selectors
			if ($totalRecords > $numberOfRecords){
				if ($pageStart == "1"){
					echo "<span class='smallText'><<</span>&nbsp;";
				}else{
					echo "<a href='javascript:setPageStart(1);'><<</a>&nbsp;";
				}

				for ($i=1; $i<($totalRecords/$numberOfRecords)+1; $i++){

					$nextPageStarts = ($i-1) * $numberOfRecords + 1;
					if ($nextPageStarts == "0") $nextPageStarts = 1;


					if ($pageStart == $nextPageStarts){
						echo "<span class='smallText'>" . $i . "</span>&nbsp;";
					}else{
						echo "<a href='javascript:setPageStart(" . $nextPageStarts  .");'>" . $i . "</a>&nbsp;";
					}
				}

				if ($pageStart == $nextPageStarts){
					echo "<span class='smallText'>>></span>&nbsp;";
				}else{
					echo "<a href='javascript:setPageStart(" . $nextPageStarts  .");'>>></a>&nbsp;";
				}
			}else{
				echo "<br />";
			}

			//making table larger so it fills the page more
			echo "<table class='dataTable'>";
			echo "<tr>";
			echo "<th style='padding:3px;'>Import Time</th>";
			echo "<th style='padding:3px;'>File</th>";
			echo "<th style='padding:3px;'>Imported By</th>";
			echo "<th style='padding:3px;'>&nbsp;</th>";
			echo "<th style='padding:3px;'>&nbsp;</th>";
			echo "<th style='padding:3px;'>&nbsp;</th>";
			echo "</tr>";

			$i=0;

			foreach($importLogArray as $importLog) {
				$i++;
				if ($i % 2 == 0){
					$classAdd="";
				}else{
					$classAdd="class='alt'";
				}

				echo "<tr>";
				echo "<td $classAdd style='padding:3px;'>" . format_date($importLog['dateTime']) . "</td>";
				echo "<td $classAdd style='padding:3px;'>" . $importLog['fileName'] . "</td>";
				echo "<td $classAdd style='padding:3px;'>" . $importLog['loginID'] . "</td>";
				echo "<td $classAdd style='padding:3px;'>" . $importLog['details'] . "</td>";
				echo "<td $classAdd style='padding:3px;'><a href='" . $importLog['logFileURL'] . "'>view log</a></td>";
				echo "<td $classAdd style='padding:3px;'><a href='" . $importLog['archiveFileURL'] . "'>view archive</a></td>";
				echo "</tr>";
			}


			?>

			</table>

			<?php

			//print out page selectors
			if ($totalRecords > $numberOfRecords){
				if ($pageStart == "1"){
					echo "<span class='smallText'><<</span>&nbsp;";
				}else{
					echo "<a href='javascript:setPageStart(1);'><<</a>&nbsp;";
				}

				for ($i=1; $i<($totalRecords/$numberOfRecords)+1; $i++){

					$nextPageStarts = ($i-1) * $numberOfRecords + 1;
					if ($nextPageStarts == "0") $nextPageStarts = 1;


					if ($pageStart == $nextPageStarts){
						echo "<span class='smallText'>" . $i . "</span>&nbsp;";
					}else{
						echo "<a href='javascript:setPageStart(" . $nextPageStarts  .");'>" . $i . "</a>&nbsp;";
					}
				}

				if ($pageStart == $nextPageStarts){
					echo "<span class='smallText'>>></span>&nbsp;";
				}else{
					echo "<a href='javascript:setPageStart(" . $nextPageStarts  .");'>>></a>&nbsp;";
				}
			}else{
				echo "<br />";
			}
		}


		break;



	//display user info for admin screen
	case 'getAdminUserList':

		$instanceArray = array();
		$user = new User();
		$tempArray = array();
		$config = new Configuration();

		if (count($user->allAsArray()) > 0){

			?>
			<table class='dataTable' style='width:550px'>
				<tr>
				<th>Login ID</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Privilege</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<?php

				foreach($user->allAsArray() as $instance) {
					$privilege = new Privilege(new NamedArguments(array('primaryKey' => $instance['privilegeID'])));

					echo "<tr>";
					echo "<td>" . $instance['loginID'] . "</td>";
					echo "<td>" . $instance['firstName'] . "</td>";
					echo "<td>" . $instance['lastName'] . "</td>";
					echo "<td>" . $privilege->shortName . "</td>";
					echo "<td style='width:30px'><a href='ajax_forms.php?action=getAdminUserUpdateForm&loginID=" . $instance['loginID'] . "&height=196&width=248&modal=true' class='thickbox' id='expression'>update</a></td>";
					echo "<td style='width:50px'><a href='javascript:deleteUser(\"" . $instance['loginID'] . "\")'>remove</a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)";
		}

		break;



	default:
       echo "Function " . $_REQUEST['function'] . " not set up!";
       break;


}



?>