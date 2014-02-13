<?php
require "includes/db.php";
include "includes/common.php";

$action = $_REQUEST['action'];

switch ($action) {

    case 'getAddressTable':

		#get updated email address info
		$result = mysql_query("select * from log_email_address order by logEmailAddressID;");
		$emailAddresses = array();

		echo "<b>Current Email Addresses</b>";
		echo "<table class='dataTable' style='width:400px'>";

		while ($row = mysql_fetch_assoc($result)) {
			echo "<tr><td>" . $row['emailAddress'] . "</td>";
			echo "<td><a href='ajax_forms.php?action=getAddressForm&height=122&width=248&logEmailAddressID=" . $row['logEmailAddressID'] . "&modal=true' class='thickbox'>edit</a></td>";
			echo "<td><a href='javascript:deleteEmailAddress(" . $row['logEmailAddressID'] . ");'>delete</a></td></tr>";
		}
		echo "</table>";
		echo "<br />";

		mysql_free_result($result);

        break;


    case 'getOutlierTable':


		#get current outlier info for display
		$result = mysql_query("select * from outlier order by outlierLevel;");


		echo "<b>Current Outlier Parameters</b><br />";

		while ($row = mysql_fetch_assoc($result)) {
			echo "Level " . $row['outlierLevel'] . ": " . $row['overageCount'] . " over plus " .  $row['overagePercent'] . "% over - displayed " . $row['color'];
			echo "&nbsp;&nbsp;<a href='ajax_forms.php?action=getOutlierForm&height=162&width=308&outlierID=" . $row['outlierID'] . "&modal=true' class='thickbox'>edit</a>";
			echo "<br />";
		}

		mysql_free_result($result);

        break;





    case 'getLoginTable':
		$platformID = $_GET['platformID'];
		$publisherPlatformID = $_GET['publisherPlatformID'];

		if ($platformID) {
			$result = mysql_query("select * from interface_login where platformID='" . $platformID . "';");
		}else{
			$result = mysql_query("select * from interface_login where publisherPlatformID='" . $publisherPlatformID . "';");
		}

		$currentRows = mysql_num_rows($result);

		if ($currentRows > 0){
		?>

		<table border="1">
		<tr>
		<th>Interface Login</th>
		<th>Password</th>
		<th>URL</th>
		<th>Login Notes</th>
		<th>&nbsp;</th>
		</tr>

		<?php

		while ($row = mysql_fetch_assoc($result)) {
			echo "<tr>";
			echo "<td><input name='loginID_" . $row['interfaceLoginID'] . "' id='loginID_" . $row['interfaceLoginID'] . "' type='text' size='10' value='" . $row['loginID'] . "' /></td>";
			echo "<td><input name='password_" . $row['interfaceLoginID'] . "' id='password_" . $row['interfaceLoginID'] . "' type='text' size='10' value='" . $row['password'] . "' /></td>";
			echo "<td><input name='url_" . $row['interfaceLoginID'] . "' id='url_" . $row['interfaceLoginID'] . "' type='text' size='40' value='" . $row['url'] . "' /></td>";
			echo "<td><textarea name='loginNotes_" . $row['interfaceLoginID'] . "' id='loginNotes_" . $row['interfaceLoginID'] . "' cols='35' rows='2'>" . $row['notes'] . "</textarea></td>";
			echo "<td><a href='javascript:updateLogin(" . $row['interfaceLoginID'] . ");'>update</a><br /><a href='javascript:deleteLogin(" . $row['interfaceLoginID'] . ");'>delete</a></td>";
			echo "</tr>";

		}
		mysql_free_result($result);

		?>
		</table>

		<?php
		}else{
			echo "None Found";
		}


        break;



    case 'getOutliersTable':
		$publisherPlatformID = $_GET['publisherPlatformID'];
		$platformID = $_GET['platformID'];
		$archiveInd = $_GET['archiveInd'];
		$year = $_GET['year'];
		$month = $_GET['month'];

		if ($publisherPlatformID) {
			$query = "select titleStatsMonthlyID, title, archiveInd, usageCount, overrideUsageCount, color
				from title_stats_monthly tsm, title t, outlier o
				where tsm.titleID = t.titleID
				and o.outlierID = tsm.outlierID
				and publisherPlatformID='" . $publisherPlatformID . "'
				and archiveInd='" . $archiveInd . "'
				and year='" . $year . "'
				and month='" . $month . "' and ignoreOutlierInd = 0;";
		}else{
			$query = "select titleStatsMonthlyID, title, archiveInd, usageCount, overrideUsageCount, color
				from title_stats_monthly tsm, title t, outlier o, publisher_platform pp
				where tsm.titleID = t.titleID
				and o.outlierID = tsm.outlierID
				and pp.publisherPlatformID = tsm.publisherPlatformID
				and platformID='" . $platformID . "'
				and archiveInd='" . $archiveInd . "'
				and year='" . $year . "'
				and month='" . $month . "' and ignoreOutlierInd = 0
				order by 1,2,3;";
		}

		$result = mysql_query($query);


		$totalRows = mysql_num_rows($result);


		echo "<table border='0' style='width:400px'>";

		if ($totalRows == 0){
			echo "<tr><td>None currently</td></tr>";
		}else{
			while ($row = mysql_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td style='width:150px;'>" . $row['title']. "</td>";
				echo "<td style='width:50px;text-align:right;background-color:" . $row['color'] . "'>" . $row['usageCount'] . "</td>";
				echo "<td style='width:100px;'><input type='text' name = 'overrideUsageCount_" . $row['titleStatsMonthlyID'] . "' id = 'overrideUsageCount_" . $row['titleStatsMonthlyID'] . "' value='" . $row['overrideUsageCount'] . "' style='width:50px'></td>";
				echo "<td style='width:50px;'><a href=\"javascript:updateOverride('" . $row['titleStatsMonthlyID'] . "');\">update override</a></td>";
				echo "<td style='width:50px;'><a href=\"javascript:ignoreOutlier('" . $row['titleStatsMonthlyID'] . "');\">ignore outlier</a></td>";
				echo "</tr>";
			}
		}

		echo "</table>";



		mysql_free_result($result);


        break;




    case 'getOverridesTable':

		$publisherPlatformID  = $_GET['publisherPlatformID'];
		$platformID  = $_GET['platformID'];
		$archiveInd  = $_GET['archiveInd'];
		$year  = $_GET['year'];

		if ($publisherPlatformID) {
			$query = "select distinct titleStatsYTDID, title, totalCount, HTMLCount, PDFCount, overrideTotalCount, overrideHTMLCount, overridePDFCount
			from title_stats_ytd tsy, title_stats_monthly tsm, title t
			where tsy.titleID = t.titleID
			and tsm.publisherPlatformID = tsy.publisherPlatformID
			and tsm.titleID = tsy.titleID
			and tsm.year = tsy.year
			and tsm.archiveInd = tsy.archiveInd
			and tsm.outlierID > 0
			and tsy.publisherPlatformID='" . $publisherPlatformID . "'
			and tsy.archiveInd='" . $archiveInd . "'
			and tsy.year='" . $year . "' and ignoreOutlierInd = 0;";
		}else{
			$query = "select distinct titleStatsYTDID, title, totalCount, HTMLCount, PDFCount, overrideTotalCount, overrideHTMLCount, overridePDFCount
			from title_stats_ytd tsy, title_stats_monthly tsm, title t, publisher_platform pp
			where tsy.titleID = t.titleID
			and tsm.publisherPlatformID = tsy.publisherPlatformID
			and tsm.titleID = tsy.titleID
			and tsm.year = tsy.year
			and tsm.archiveInd = tsy.archiveInd
			and tsm.outlierID > 0
			and pp.publisherPlatformID = tsm.publisherPlatformID
			and pp.platformID='" . $platformID . "'
			and tsy.archiveInd='" . $archiveInd . "'
			and tsy.year='" . $year . "' and ignoreOutlierInd = 0;";
		}


		$result = mysql_query($query);

		?>

		<table border='0' style='width:400px'>

		<?php

		while ($ytd_row = mysql_fetch_assoc($result)) {
		?>
			<tr>
			<td width="149"><?php echo $ytd_row['title']; ?></td>
			<td width="40">Total<td>
			<td width="40" ><?php echo $ytd_row['totalCount']; ?></td>
			<td width="40"><input name="overrideTotalCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" id="overrideTotalCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" type="text"value="<?php echo $ytd_row['overrideTotalCount']; ?>" size="6" maxlength="6"/></td>
			<td width="40"><a href="javascript:updateYTDOverride('<?php echo $ytd_row['titleStatsYTDID']; ?>', 'overrideTotalCount')">update</a></td>
			</tr>
			<tr>
			<td width="149">&nbsp;</td>
			<td width="40">PDF<td>
			<td width="40"><?php echo $ytd_row['PDFCount']; ?></td>
			<td width="40"><input name="overridePDFCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" id="overridePDFCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" type="text"value="<?php echo $ytd_row['overridePDFCount']; ?>" size="6" maxlength="6"/></td>
			<td width="40"><a href="javascript:updateYTDOverride('<?php echo $ytd_row['titleStatsYTDID']; ?>', 'overridePDFCount')">update</a></td>
			</tr>
			<tr>
			<td width="149">&nbsp;</td>
			<td width="40">HTML<td>
			<td width="40"><?php echo $ytd_row['HTMLCount']; ?></td>
			<td width="40"><input name="overrideHTMLCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" id="overrideHTMLCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" type="text"value="<?php echo $ytd_row['overrideHTMLCount']; ?>" size="6" maxlength="6"/></td>
			<td width="40"><a href="javascript:updateYTDOverride('<?php echo $ytd_row['titleStatsYTDID']; ?>', 'overrideHTMLCount')">update</a></td>
			</tr>
		<?php

		}
		mysql_free_result($ytd_result);

		?>

		</table>

		<?php


        break;


	case 'getStatsTable':

		$publisherPlatformID = $_GET['publisherPlatformID'];
		$platformID = $_GET['platformID'];
		$year = $_GET['year'];
		$archiveInd = $_GET['archiveInd'];

		if ($publisherPlatformID){
			$result = mysql_query("select distinct year, month, archiveInd from title_stats_monthly where publisherPlatformID='" . $publisherPlatformID . "' and year = '" . $year . "' and archiveInd = '" . $archiveInd . "' order by year, archiveInd, month;");
		}else{
			$result = mysql_query("select distinct year, month, archiveInd from title_stats_monthly tsm, publisher_platform pp where pp.publisherPlatformID = tsm.publisherPlatformID and pp.platformID = '" . $platformID . "' and year = '" . $year . "' and archiveInd = '" . $archiveInd . "'  order by year, archiveInd, month;");
		}


		while ($row = mysql_fetch_assoc($result)) {

			if ($row['archiveInd'] == "1") {$archive = '&nbsp;(archive)';}else{$archive='';}

			echo "<label for='month' class='month'><b>" . numberToMonth($row['month']) . " " . $row['year'] . "</b> " . $archive . "</label>";
			echo "<label for='deleteStats' class='deleteStats'><a href=\"javascript:deleteMonth('" . $row['month'] . "','" . $row['year'] . "','" . $row['archiveInd'] . "', '" . $publisherPlatformID . "', '" . $platformID . "')\">delete entire month</a></label>";

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
				echo "<label for='outliers' class='outliers'><a href=\"javascript:popUp('outliers.php?publisherPlatformID=" . $publisherPlatformID . "&platformID=" . $platformID . "&archiveInd=" . $row['archiveInd'] . "&month=" . $row['month'] . "&year=" . $row['year'] . "');\">view outliers for this month</a></label>";
			}else{
				echo "<label for='outliers' class='outliers'>&nbsp;</label>";
			}

			mysql_free_result($outlier_result);
			echo "<br />";
		}

		mysql_free_result($result);

	break;



    case 'getPlatformEdit':
    	$platformID = $_GET['platformID'];

		$result = mysql_query("select distinct platform.platformID, platform.name platform, platform.reportDisplayName reportPlatform, platform.reportDropDownInd from publisher_platform pp, platform where pp.platformID = platform.platformID and platform.platformID = '" . $platformID . "';");


		while ($row = mysql_fetch_assoc($result)) {
			if ($row['reportDropDownInd'] == '1') { $reportDropDownInd = 'checked';}else{$reportDropDownInd = '';}

			echo "<input type='checkbox' id='chk_platform_" . $row['platformID']  . "' onclick='javascript:updatePlatformDropDown(" . $row['platformID']  . ");' $reportDropDownInd>";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<span class='platformText'>" . $row['platform'] . "</span>";


			echo "&nbsp;&nbsp;<input type='textbox' id='txt_" . $platformID . "' value='" . $row['reportPlatform'] . "'>";
			echo "&nbsp;&nbsp;<a href='javascript:updateDisplayPlatform(" . $platformID . ");'>update</a><br />";


		}


        break;


    case 'getPlatformDisplay':
    	$platformID = $_GET['platformID'];

		$result = mysql_query("select distinct platform.platformID, platform.name platform, platform.reportDisplayName reportPlatform, platform.reportDropDownInd from publisher_platform pp, platform where pp.platformID = platform.platformID and platform.platformID = '" . $platformID . "';");

		while ($row = mysql_fetch_assoc($result)) {
			if ($row['reportDropDownInd'] == '1') { $reportDropDownInd = 'checked';}else{$reportDropDownInd = '';}

			echo "<input type='checkbox' id='chk_platform_" . $row['platformID']  . "' onclick='javascript:updatePlatformDropDown(" . $row['platformID']  . ");' $reportDropDownInd>";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<span class='platformText'>" . $row['platform'] . "</span>";

			if ($row['reportPlatform'])  echo "&nbsp;&nbsp;(<i>" . $row['reportPlatform'] . "</i>)";
			echo "&nbsp;&nbsp;<a href='ajax_forms.php?action=getReportDisplayForm&height=122&width=248&type=platform&updateID=" . $row['platformID'] . "&modal=true' class='thickbox'>edit report display name</a><br />";


		}



        break;





    case 'getPublisherDisplay':
    	$publisherPlatformID = $_GET['publisherPlatformID'];

		$result = mysql_query("select distinct pp.publisherPlatformID, publisher.name publisher, pp.reportDisplayName reportPublisher, pp.reportDropDownInd from publisher_platform pp, publisher where pp.publisherID = publisher.publisherID and pp.publisherPlatformID = '" . $publisherPlatformID . "';");

		while ($row = mysql_fetch_assoc($result)) {
			if ($row['reportDropDownInd'] == '1') { $reportDropDownInd = 'checked';}else{$reportDropDownInd = '';}

			echo "<table><tr valign='top'><td><input type='checkbox' id='chk_publisher_" . $row['publisherPlatformID']  . "' onclick='javascript:updatePublisherDropDown(" . $row['publisherPlatformID']  . ");' $reportDropDownInd></td>";


			echo "<td>" . $row['publisher'];
			if ($row['reportPublisher'])  echo "&nbsp;&nbsp;(<i>" . $row['reportPublisher'] . "</i>)";
			echo "&nbsp;&nbsp;<a href='ajax_forms.php?action=getReportDisplayForm&height=122&width=248&type=publisher&updateID=" . $row['publisherPlatformID'] . "&modal=true' class='thickbox'>edit report display name</a></td></tr></table>";


		}



        break;




    case 'getNotesLoginDetails':
		if (isset($_GET['publisherPlatformID']) && ($_GET['publisherPlatformID'] != '')){
			$publisherPlatformID = $_GET['publisherPlatformID'];
		}else{
			$platformID = $_GET['platformID'];
		}

		if ($publisherPlatformID != ''){

			?>

			<h3>Publisher Notes</h3>

			<div id="div_notes">

			<?php


			$result = mysql_query("select * from publisher_notes where publisherPlatformID='" . $publisherPlatformID . "';");


			$currentRows = mysql_num_rows($result);

			if ($currentRows > 0){
			?>

			<table class='verticalFormTable'>
			<tr>
			<th>Start Year</th>
			<th>End Year</th>
			<th>Notes</th>
			<th>&nbsp;</th>
			</tr>

			<?php

			while ($row = mysql_fetch_assoc($result)) {
				if (($row['endYear'] == '0') || ($row['endYear'] =='')) $endYear = 'Present'; else $endYear = $row['endYear'];

				echo "<tr>";
				echo "<td>" . $row['startYear'] . "</td>";
				echo "<td>" . $endYear . "</td>";
				echo "<td>" . $row['notes'] . "</td>";
				echo "<td><a href='ajax_forms.php?action=getPublisherNotesForm&publisherNotesID=" . $row['publisherNotesID'] . "&height=225&width=313&modal=true' class='thickbox' style='font-size:100%;'>edit</a><br /><a href='javascript:deletePublisherNote(" . $row['publisherNotesID'] . ");' style='font-size:100%;'>remove</a></td>";
				echo "</tr>";

			}
			mysql_free_result($result);

			?>
			</table>

			<?php }else{ echo "(none found)"; } ?>
			</div>

			<br />

			<a href='ajax_forms.php?action=getPublisherNotesForm&publisherNotesID=&publisherPlatformID=<?php echo $publisherPlatformID; ?>&height=225&width=313&modal=true' class='thickbox' id='uploadDocument'>add new publisher notes</a>


			<br />
			<br />



			<h3>Logins</h3>

			<div id="div_logins">

			<?php


			$result = mysql_query("select * from interface_login where platformID='" . $platformID . "';");


			$currentRows = mysql_num_rows($result);

			if ($currentRows > 0){
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

			while ($row = mysql_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>" . $row['loginID'] . "</td>";
				echo "<td>" . $row['password'] . "</td>";
				echo "<td>" . $row['url'] . "</td>";
				echo "<td>" . $row['notes'] . "</td>";
				echo "<td><a href='ajax_forms.php?action=getLoginForm&interfaceLoginID=" . $row['interfaceLoginID'] . "&height=250&width=325&modal=true' class='thickbox' style='font-size:100%;'>edit</a><br /><a href='javascript:deleteLogin(" . $row['interfaceLoginID'] . ");' style='font-size:100%;'>remove</a></td>";
				echo "</tr>";

			}
			mysql_free_result($result);

			?>
			</table>

			<?php
			}else{
				echo "(none found)";
			}
			?>


			</div>

			<br />
			<a href='ajax_forms.php?action=getLoginForm&publisherPlatformID=<?php echo $publisherPlatformID; ?>&height=250&width=325&modal=true' class='thickbox' id='uploadDocument'>add new login</a>


		<?php
		//platform record
		}else{

			?>

			<h3>Interface Notes</h3>

			<div id="div_interfaces">

			<?php


			$result = mysql_query("select * from platform_interface where platformID='" . $platformID . "';");


			$currentRows = mysql_num_rows($result);

			if ($currentRows > 0){
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

			while ($row = mysql_fetch_assoc($result)) {
				if ($row['counterCompliantInd'] == "1") $counterCompliantInd = 'Y'; else $counterCompliantInd = 'N';
				if (($row['endYear'] == '0') || ($row['endYear'] =='')) $endYear = 'Present'; else $endYear = $row['endYear'];


				echo "<tr>";
				echo "<td>" . $row['startYear'] . "</td>";
				echo "<td>" . $endYear . "</td>";
				echo "<td>" . $counterCompliantInd . "</td>";
				echo "<td>" . $row['interfaceNotes'] . "</td>";
				echo "<td><a href='ajax_forms.php?action=getInterfaceForm&platformInterfaceID=" . $row['platformInterfaceID'] . "&height=255&width=408&modal=true' class='thickbox' style='font-size:100%;'>edit</a><br /><a href='javascript:deleteInterfaceNotes(" . $row['platformInterfaceID'] . ");' style='font-size:100%;'>remove</a></td>";
				echo "</tr>";

			}
			mysql_free_result($result);

			?>
			</table>

			<?php }else{ echo "(none found)"; } ?>
			</div>

			<br />

			<a href='ajax_forms.php?action=getInterfaceForm&platformInterfaceID=&platformID=<?php echo $platformID; ?>&height=255&width=408&modal=true' class='thickbox' id='addInterface'>add new interface note</a>

			<br />
			<br />



			<h3>Logins</h3>

			<div id="div_logins">

			<?php


			$result = mysql_query("select * from interface_login where platformID='" . $platformID . "';");


			$currentRows = mysql_num_rows($result);

			if ($currentRows > 0){
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

			while ($row = mysql_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>" . $row['loginID'] . "</td>";
				echo "<td>" . $row['password'] . "</td>";
				echo "<td>" . $row['url'] . "</td>";
				echo "<td>" . $row['notes'] . "</td>";

				echo "<td><a href='ajax_forms.php?action=getLoginForm&interfaceLoginID=" . $row['interfaceLoginID'] . "&height=250&width=325&modal=true' class='thickbox' style='font-size:100%;'>edit</a><br /><a href='javascript:deleteLogin(" . $row['interfaceLoginID'] . ");' style='font-size:100%;'>remove</a></td>";
				echo "</tr>";

			}
			mysql_free_result($result);

			?>
			</table>

			<?php
			}else{
				echo "(none found)";
			}
			?>


			</div>

			<br />
			<a href='ajax_forms.php?action=getLoginForm&interfaceLoginID=&platformID=<?php echo $platformID; ?>&height=250&width=325&modal=true' class='thickbox' id='uploadDocument'>add new login</a>

		<?php
		}

        break;


    case 'getFullStatsDetails':
    	echo "<h3>Update Statistics</h3>";

		if (isset($_GET['publisherPlatformID']) && ($_GET['publisherPlatformID'] != '')){
			$publisherPlatformID = $_GET['publisherPlatformID'];
		}else{
			$platformID = $_GET['platformID'];
		}

		if ($publisherPlatformID != ''){
			$query = "SELECT DISTINCT year, GROUP_CONCAT(DISTINCT concat(month, '|', if(ignoreOutlierInd=0,outlierID,0)) ORDER BY month, 1 SEPARATOR ',') months, archiveInd, MAX(month) max_month, MIN(month) min_month, MAX(IF(ignoreOutlierInd=0,outlierID,null)) outlierID
									FROM title_stats_monthly tsm
									WHERE tsm.publisherPlatformID = '" . $publisherPlatformID . "'
									GROUP BY year, archiveInd
									ORDER BY year desc, archiveInd, month;";
		}else{
			$query = "SELECT DISTINCT year, GROUP_CONCAT(DISTINCT concat(month, '|', if(ignoreOutlierInd=0,outlierID,0)) ORDER BY month, 1 SEPARATOR ',') months, archiveInd, MAX(month) max_month, MIN(month) min_month, MAX(IF(ignoreOutlierInd=0,outlierID,null)) outlierID
									FROM publisher_platform pp, title_stats_monthly tsm
									WHERE pp.platformID = '" . $platformID . "'
									AND pp.publisherPlatformID = tsm.publisherPlatformID
									GROUP BY year, archiveInd
									ORDER BY year desc, archiveInd, month;";

		}

		$result = mysql_query($query);

		echo "<table class='verticalFormTable' style='width:450px;'>";

		while ($row = mysql_fetch_assoc($result)) {
			if ($row['archiveInd'] == "1") {$archive = '&nbsp;(archive)';}else{$archive='';}

			echo "<tr>";
			echo "<th><span style='font-weight:bold; font-size:120%;'>" . $row['year'] . $archive . "</span></td>";
			echo "<th><a target='_blank' href='spreadsheet.php?publisherPlatformID=" .  $publisherPlatformID . "&platformID=" . $platformID . "&year=" . $row['year'] . "&archiveInd=" . $row['archiveInd'] . "' style='font-size:110%;'>view spreadsheet</a></td>";
			echo "</tr>";

			//loop through each month
			$monthArray = array();
			$queryMonthArray = array();
			$queryMonthArray = explode(",",$row['months']);


			//we need to eliminate duplicates - mysql doesnt allow group inside group_concats
			foreach ($queryMonthArray as $resultMonth){
				$infoArray=array();
				$infoArray=explode("|",$resultMonth);

				$monthArray[$infoArray[0]] = $infoArray[1];
			}

			foreach ($monthArray as $month => $outlier){

				echo "<tr id='tr_" . $platformID . "_" . $publisherPlatformID . "_" . $row['year'] . "_" . $month . "_" . $row['archiveInd'] . "'>";
				echo "<td>&nbsp;</td>";
				echo "<td style='padding:0px;'>";
				echo "<table class='noBorderTable' style='width:340px;'>";
				echo "<tr>";
				echo "<td style='width:70px;font-weight:bold;'>" . numberToMonth($month) . " " . $row['year'] . "</td>";
				echo "<td><a href=\"javascript:deleteMonth('" . $month . "','" . $row['year'] . "','" . $row['archiveInd'] . "', '" . $publisherPlatformID . "', '" . $platformID . "')\" style='font-size:100%;'>delete entire month</a>";

				//print out prompt for outliers if outlierID is > 0
				if ($outlier > 0){
					echo "&nbsp;&nbsp;<a href='ajax_forms.php?action=getMonthlyOutlierForm&publisherPlatformID=" . $publisherPlatformID . "&platformID=" . $platformID . "&archiveInd=" . $row['archiveInd'] . "&month=" . $month . "&year=" . $row['year'] . "&height=340&width=415&modal=true' class='thickbox' style='font-size:100%;'>view outliers for this month</a>";
				}

				echo "</td></tr>";
				echo "</table>";

				echo "</td>";
				echo "</tr>";

			}

			echo "<tr>";
			echo "<td>&nbsp;</td>";
			echo "<td><span style='font-weight:bold;'>YTD " . $row['year'] . "</span>";

			if ($row['outlierID'] > 0){
				echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='ajax_forms.php?action=getYearlyOverrideForm&publisherPlatformID=" . $publisherPlatformID . "&platformID=" . $platformID . "&archiveInd=" . $row['archiveInd'] . "&year=" . $row['year'] . "&height=340&width=415&modal=true' class='thickbox' style='font-size:100%;'>update overrides for this year</a>";
			}else{
				echo "&nbsp;&nbsp;&nbsp;&nbsp;(no outliers found for this year)";
			}

			echo "</td>";
			echo "</tr>";


			echo "<tr><td colspan='2'>&nbsp;</td></tr>";


		}

		echo "</table>";

		break;

    case 'getTitleDetails':

		if (isset($_GET['publisherPlatformID']) && ($_GET['publisherPlatformID'] != '')){
			$publisherPlatformID = $_GET['publisherPlatformID'];
		}else{
			$platformID = $_GET['platformID'];
		}


		//determine config settings for link resolver
		//$config = new Configuration();

		//if ($config->settings->useSFXTermsToolFunctionality == "Y"){
			$baseURL = "http://findtext.library.nd.edu:8889/ndu_local?url_ver=Z39.88-2004&ctx_ver=Z39.88-2004&ctx_enc=info:ofi/enc:UTF-8&rfr_id=info:sid/ND_ejl_stat&url_ctx_fmt=info:ofi/fmt:kev:mtx:ctx&svc_val_fmt=info:ofi/fmt:kev:mtx:sch_svc&sfx.ignore_date_threshold=1&rft.issn=";
		//}

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

			<table class='verticalFormTable'>
			<tr>
				<th>&nbsp;</th>
				<th style='width:90px;'><b>ISSN Type</b></th>
				<th style='width:90px;'><b>ISSN</b></th>
				<th style='width:110px;'>&nbsp;</th>
			</tr>



			<?php

			while ($row = mysql_fetch_assoc($result)) {

				echo "\n<tr>";
				//echo "\n<div id ='div_title_" .  $row['titleID'] . "'>";

				//get the first ISSN to use for the SFX lookup
				$issn_result = mysql_query("select issn from title_issn ti where ti.titleID = '" . $row['titleID'] . "' order by issnType desc LIMIT 1;");
				$issn_row = mysql_fetch_assoc($issn_result);

				if (($issn_row['issn']) && ($baseURL)){
					echo "\n<td><b>" . $row['title'] . "</b><br /><a href='ajax_forms.php?action=getRelatedTitlesForm&titleID=" . $row['titleID'] . "&height=240&width=258&modal=true' class='thickbox'>view related titles</a>&nbsp;&nbsp;<a href='" . $baseURL . $issn_row['issn']  . "' target='_blank'>view in findtext</a><br /><br /><span id='span_" . $row['titleID'] . "_feedback'></span></td>";
				}else{
					echo "\n<td><b>" . $row['title'] . "</b><br /><a href='ajax_forms.php?action=getRelatedTitlesForm&titleID=" . $row['titleID'] . "&height=240&width=258&modal=true' class='thickbox'>view related titles</a></td>";
				}

				echo "<td colspan='4'>";
				echo "<table class='noBorderTable'>";

				$issn_result = mysql_query("select titleissnid, issn, issntype from title_issn ti where ti.titleID = '" . $row['titleID'] . "' order by issnType desc;");
				while ($issn_row = mysql_fetch_assoc($issn_result)) {
					$displayISSN = substr($issn_row['issn'],0,4) . "-" . substr($issn_row['issn'],4,4);

					echo "<tr id='tr_" . $issn_row['titleissnid'] . "'>";

					echo "\n<td style='width:90px;' class='rightBorder'>" . $issn_row['issntype'] . "</td>";
					echo "\n<td style='width:90px;' class='rightBorder'>" . $displayISSN . "</td>";
					echo "\n<td style='width:105px;' class='rightBorder'><a href=\"javascript:deleteISSN('" . $issn_row['titleissnid'] . "');\" style='font-size:100%;'>remove this issn</a></td>";
					echo "\n</tr>";
				}

				echo "\n<tr><td colspan='3'><a href='ajax_forms.php?action=getAddISSNForm&publisherPlatformID=" . $publisherPlatformID . "&platformID=" . $platformID . "&titleID=" . $row['titleID'] . "&height=140&width=205&modal=true' class='thickbox' style='font-size:100%;'>add issn</a>&nbsp;</td></tr>";



				echo "</table></td></tr>";
			#end title loop
			}
			echo "</table>";
		}

		mysql_free_result($result);

		echo "<br /><br />";
		break;






    case 'getImportTable':


		$pageStart = $_GET['pageStart'];
		$numberOfRecords = 20;
		$limit = $pageStart-1 . ", " . $numberOfRecords;

		$result = mysql_query("select netID, date_format(importDateTime, \"%m/%d/%Y %h:%i %p\") dateTime, fileName, archiveFileURL, logFileURL, details from import_log order by importDateTime desc");
		$totalRecords = mysql_num_rows($result);

		$result = mysql_query("select netID, date_format(importDateTime, \"%m/%d/%Y %h:%i %p\") dateTime, fileName, archiveFileURL, logFileURL, details from import_log order by importDateTime desc LIMIT " . $limit . ";");

		$recordCount = mysql_num_rows($result);

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


			echo "<table class='dataTable'>";
			echo "<tr>";
			echo "<th>Import Time</th>";
			echo "<th>File Name</th>";
			echo "<th>&nbsp;</th>";
			echo "<th>&nbsp;</th>";
			echo "</tr>";

			$i=0;

			while ($row = mysql_fetch_assoc($result)) {
				$i++;
				if ($i % 2 == 0){
					$classAdd="";
				}else{
					$classAdd="class='alt'";
				}

				echo "<tr>";
				echo "<td $classAdd><b>" . $row['dateTime'] . "</b></td>";
				echo "<td $classAdd>" . $row['fileName'] . "</td>";
				echo "<td $classAdd><a href='" . $row['logFileURL'] . "'>view log</a></td>";
				echo "<td $classAdd><a href='" . $row['archiveFileURL'] . "'>view archive</a></td>";
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


	default:
       echo "Function " . $_REQUEST['function'] . " not set up!";
       break;


}



?>