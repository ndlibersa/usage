<?php
require "includes/db.php";
include "includes/common.php";

$action = $_REQUEST['action'];

switch ($action) {


	//log email addresses (on admin page)
	case 'getAddressForm':
		if (isset($_GET['logEmailAddressID'])) $logEmailAddressID = $_GET['logEmailAddressID']; else $logEmailAddressID = '';

		if ($logEmailAddressID) $addUpdate = 'Update'; else $addUpdate = 'Add';

		$result = mysql_query("select * from log_email_address where logEmailAddressID = '" . $logEmailAddressID . "';");
		$row = mysql_fetch_assoc($result);

		?>
		<div id='div_updateForm'>
		<input type='hidden' id='updateLogEmailAddressID' name='updateLogEmailAddressID' value='<?php echo $logEmailAddressID; ?>'>
		<table class="thickboxTable" style="width:230px;">
		<tr>
		<td colspan='2'><br /><span class='headerText'><?php echo $addUpdate; ?> Email Address</span><br /></td>
		</tr>
		<tr>
		<td>
		<?php
		echo "<input type='text' id='emailAddress' name='emailAddress' value='" . $row['emailAddress'] . "' style='width:190px;'/></td><td><a href='javascript:processEmailAddress();'>" . strtolower($addUpdate) . "</a>";
		?>


		</td>
		</tr>
		<tr>
		<td colspan='2'><p><a href='#' onclick='window.parent.tb_remove(); return false'>close</a></td>
		</tr>
		</table>
		</div>


		<script type="text/javascript">
		   //attach enter key event to new input and call add data when hit
		   $('#emailAddress').keyup(function(e) {

				   if(e.keyCode == 13) {
					   processEmailAddress();
				   }
        	});

        </script>


		<?php

		break;

	case 'getOutlierForm':
		if (isset($_GET['outlierID'])) $outlierID = $_GET['outlierID']; else $outlierID = '';

		$result = mysql_query("select * from outlier where outlierID = " . $outlierID . ";");
		$row = mysql_fetch_assoc($result);

		?>
		<div id='div_updateForm'>
		<input type='hidden' id='updateOutlierID' name='updateOutlierID' value='<?php echo $outlierID; ?>'>
		<table class="thickboxTable" style="width:300px;padding:2px;">
		<tr><td colspan='2'><span class='headerText'>Update Outlier</span><br /><br /></td></tr>
		<tr><td style='vertical-align:top;text-align:right;width:135px;'><label for='outlierLevel'><b>Level</b></label</td><td><?php echo $row['outlierLevel']; ?></td></tr>
		<tr><td style='vertical-align:top;text-align:right;'><label for='overageCount'><b>Count Over</b></label</td><td><input type='text' id='overageCount' name='overageCount' value="<?php echo $row['overageCount']; ?>" style='width:140px;' /><span id='span_error_overageCount' style='color:red'></span></td></tr>
		<tr><td style='vertical-align:top;text-align:right;'><label for='overagePercent'><b>% Over prior 12 months</b></label</td><td><input type='text' id='overagePercent' name='overagePercent' value="<?php echo $row['overagePercent']; ?>" style='width:140px;' /><span id='span_error_overagePercent' style='color:red'></span></td></tr>

		<tr style="vertical-align:middle;">
		<td style="padding-top:8px;text-align:right;">&nbsp;</td>
		<td style="padding-top:18px;padding-right:8px;text-align:left;"><input type='button' value='Update' onclick='javascript:window.parent.updateOutlier();'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='cancel' onclick="window.parent.tb_remove(); return false"></td>
		</tr>

		</table>

		</div>


		<?php

		break;




	//reporting display name (on reporting page)
	case 'getReportDisplayForm':
		if (isset($_GET['updateID'])) $updateID = $_GET['updateID']; else $updateID = '';

		if ($_GET['type'] == 'platform'){
			$result = mysql_query("select * from platform where platformID = '" . $updateID . "';");
		}else{
			$result = mysql_query("select * from publisher_platform where publisherPlatformID = '" . $updateID . "';");
		}


		$row = mysql_fetch_assoc($result);

		?>
		<div id='div_updateForm'>
		<input type='hidden' id='updateID' name='updateID' value='<?php echo $updateID; ?>'>
		<input type='hidden' id='type' name='type' value='<?php echo $_GET['type']; ?>'>
		<table class="thickboxTable" style="width:230px;">
		<tr>
		<td colspan='2'><br /><span class='headerText'>Update Report Display Name</span><br /></td>
		</tr>
		<tr>
		<td>
		<?php
		echo "<input type='text' id='reportDisplayName' name='reportDisplayName' value='" . $row['reportDisplayName'] . "' style='width:190px;'/></td><td><a href='javascript:updateReportDisplayName();'>update</a>";
		?>


		</td>
		</tr>
		<tr>
		<td colspan='2'><p><a href='#' onclick='window.parent.tb_remove(); return false'>close</a></td>
		</tr>
		</table>
		</div>


		<script type="text/javascript">
		   //attach enter key event to new input and call add data when hit
		   $('#reportDisplayName').keyup(function(e) {

				   if(e.keyCode == 13) {
					   updateReportDisplayName();
				   }
        	});

        </script>


		<?php

		break;


	case 'getInterfaceForm':
		if (isset($_GET['platformInterfaceID'])) $platformInterfaceID = $_GET['platformInterfaceID']; else $platformInterfaceID = '';
 		if (isset($_GET['platformID'])) $platformID = $_GET['platformID'];


		if ($platformInterfaceID) $addUpdate = 'Update'; else $addUpdate = 'Add';

		if ($platformInterfaceID){
			$result = mysql_query("select * from platform_interface where platformInterfaceID = " . $platformInterfaceID . ";");
			$row = mysql_fetch_assoc($result);

			$platformID = $row['platformID'];

			if ($row['counterCompliantInd'] == '1'){
				$counterCompliant = 'checked';
			}else{
				$counterCompliant = '';
			}

			if ($row['notCounterCompliantInd'] == '1'){
				$notCounterCompliant = 'checked';
			}else{
				$notCounterCompliant = '';
			}

			if (($row['endYear'] == '0') || ($row['endYear'] =='')) $endYear = ''; else $endYear = $row['endYear'];


		}

		?>
		<div id='div_updateForm'>
		<input type='hidden' id='editPlatformInterfaceID' name='editPlatformInterfaceID' value='<?php echo $platformInterfaceID; ?>'>
		<input type='hidden' id='platformID' name='platformID' value='<?php echo $platformID; ?>'>
		<table class="thickboxTable" style="width:400px;padding:2px;">
			<tr>
				<td colspan='2'><span class='headerText'><?php echo $addUpdate; ?> Interface Notes</span><span id='span_errors' style='color:red;'><br /></span><br /></td>
			</tr>
			<tr>
				<td style='vertical-align:top;text-align:right;width:135px;'><label for='startYear'><b>Start Year:</b></label</td>
				<td><input type='text' id='startYear' name='startYear' value="<?php echo $row['startYear']; ?>" style='width:90px;' /><span id='span_error_startYear' style='color:red'></span></td>
			</tr>
			<tr>
				<td style='vertical-align:top;text-align:right;'><label for='endYear'><b>End Year:</b></label</td>
				<td><input type='text' id='endYear' name='endYear' value="<?php echo $endYear; ?>" style='width:90px;' /><span id='span_error_endYear' style='color:red'></span></td>
			</tr>
			<tr>
				<td style='vertical-align:top;text-align:right;'><label for='counterCompliantInd'><b>Counter Compliant?</b></label</td>
				<td><input type='checkbox' id='counterCompliantInd' name='counterCompliantInd' <?php echo $counterCompliant; ?> /></td>
			</tr>
			<tr>
				<td style='vertical-align:top;text-align:right;'><label for='notCounterCompliantInd'><b>Not Counter Compliant?</b></label></td>
				<td><input type='checkbox' id='notCounterCompliantInd' name='notCounterCompliantInd' <?php echo $notCounterCompliant; ?> /></td>
			</tr>

			<tr>
				<td style='vertical-align:top;text-align:right;'><label for='interfaceNotes'><b>Interface Notes:</b></label></td>
				<td><textarea cols='36' rows='4' id='interfaceNotes' name='interfaceNotes' style='width:250px;'><?php echo $row['interfaceNotes']; ?></textarea></td>
			</tr>

			<tr style="vertical-align:middle;">
				<td style="padding-top:8px;text-align:right;">&nbsp;</td>
				<td style="padding-top:8px;padding-right:8px;">
					<table class='noBorderTable' style='width:100%;'>
						<tr>
							<td style='text-align:left'><input type='button' value='submit' name='submitInterfaceForm' id ='submitInterfaceForm'></td>
							<td style='text-align:right'><input type='button' value='cancel' onclick="tb_remove()"></td>
						</tr>
					</table>
				</td>
			</tr>

		</table>

		</div>

		<script type="text/javascript" src="js/forms/interfaceSubmitForm.js"></script>

		<?php

		break;








	case 'getPublisherNotesForm':
		if (isset($_GET['publisherNotesID'])) $publisherNotesID = $_GET['publisherNotesID']; else $publisherNotesID = '';
 		if (isset($_GET['publisherPlatformID'])) $publisherPlatformID = $_GET['publisherPlatformID'];


		if ($publisherNotesID) $addUpdate = 'Update'; else $addUpdate = 'Add';

		if ($publisherNotesID){
			$result = mysql_query("select * from publisher_notes where publisherNotesID = " . $publisherNotesID . ";");
			$row = mysql_fetch_assoc($result);

			$publisherPlatformID = $row['publisherPlatformID'];

			if (($row['endYear'] == '0') || ($row['endYear'] =='')) $endYear = ''; else $endYear = $row['endYear'];

		}

		?>
		<div id='div_updateForm'>
		<input type='hidden' id='editPublisherNotesID' name='editPublisherNotesID' value='<?php echo $publisherNotesID; ?>'>
		<input type='hidden' id='publisherPlatformID' name='publisherPlatformID' value='<?php echo $publisherPlatformID; ?>'>
		<table class="thickboxTable" style="width:310px;padding:2px;">
			<tr>
				<td colspan='2'><span class='headerText'><?php echo $addUpdate; ?> Publisher Notes</span><span id='span_errors' style='color:red;'><br /></span><br /></td>
			</tr>
			<tr>
				<td style='vertical-align:top;text-align:right;width:135px;'><label for='startYear'><b>Start Year:</b></label</td>
				<td><input type='text' id='startYear' name='startYear' value="<?php echo $row['startYear']; ?>" style='width:90px;' /><span id='span_error_startYear' style='color:red'></span></td>
			</tr>
			<tr>
				<td style='vertical-align:top;text-align:right;'><label for='endYear'><b>End Year:</b></label</td>
				<td><input type='text' id='endYear' name='endYear' value="<?php echo $endYear; ?>" style='width:90px;' /><span id='span_error_endYear' style='color:red'></span></td>
			</tr>
			<tr>
				<td style='vertical-align:top;text-align:right;'><label for='notes'><b>Publisher Notes:</b></label></td>
				<td><textarea cols='36' rows='4' id='notes' name='notes' style='width:200px;'><?php echo $row['notes']; ?></textarea></td>
			</tr>

			<tr style="vertical-align:middle;">
				<td style="padding-top:8px;text-align:right;">&nbsp;</td>
				<td style="padding-top:8px;padding-right:8px;">
					<table class='noBorderTable' style='width:100%;'>
						<tr>
							<td style='text-align:left'><input type='button' value='submit' name='submitPublisherNotesForm' id ='submitPublisherNotesForm'></td>
							<td style='text-align:right'><input type='button' value='cancel' onclick="tb_remove()"></td>
						</tr>
					</table>
				</td>
			</tr>

		</table>

		</div>

		<script type="text/javascript" src="js/forms/publisherNotesSubmitForm.js"></script>

		<?php

		break;






	case 'getLoginForm':
		if (isset($_GET['interfaceLoginID'])) $interfaceLoginID = $_GET['interfaceLoginID']; else $interfaceLoginID = '';
 		if (isset($_GET['platformID'])) $platformID = $_GET['platformID']; else $platformID = '';
 		if (isset($_GET['publisherPlatformID'])) $publisherPlatformID = $_GET['publisherPlatformID']; else $publisherPlatformID = '';


		if ($interfaceLoginID) $addUpdate = 'Update'; else $addUpdate = 'Add';

		if ($interfaceLoginID){
			$result = mysql_query("select * from interface_login where interfaceLoginID = " . $interfaceLoginID . ";");
			$row = mysql_fetch_assoc($result);

			$publisherPlatformID = $row['publisherPlatformID'];
			$platformID = $row['platformID'];
		}

		?>
		<div id='div_updateForm'>
		<input type='hidden' id='editInterfaceLoginID' name='editInterfaceLoginID' value='<?php echo $interfaceLoginID; ?>'>
		<input type='hidden' id='platformID' name='platformID' value='<?php echo $platformID; ?>'>
		<input type='hidden' id='publisherPlatformID' name='publisherPlatformID' value='<?php echo $publisherPlatformID; ?>'>
		<table class="thickboxTable" style="width:320px;padding:2px;">
			<tr>
				<td colspan='2'><span class='headerText'><?php echo $addUpdate; ?> Login</span><span id='span_errors' style='color:red;'><br /></span><br /></td>
			</tr>
			<tr>
				<td style='vertical-align:top;text-align:right;width:135px;'><label for='loginID'><b>Interface Login ID:</b></label</td>
				<td><input type='text' id='loginID' name='loginID' value="<?php echo $row['loginID']; ?>" style='width:200px;' /><span id='span_error_loginID' style='color:red'></span></td>
			</tr>
			<tr>
				<td style='vertical-align:top;text-align:right;'><label for='password'><b>Password:</b></label</td>
				<td><input type='text' id='password' name='password' value="<?php echo $row['password']; ?>" style='width:200px;' /><span id='span_error_password' style='color:red'></span></td>
			</tr>
			<tr>
				<td style='vertical-align:top;text-align:right;'><label for='loginURL'><b>URL:</b></label</td>
				<td><input type='text' id='loginURL' name='loginURL' value="<?php echo $row['url']; ?>" style='width:200px;' /><span id='span_error_url' style='color:red'></span></td>
			</tr>
			<tr>
				<td style='vertical-align:top;text-align:right;'><label for='notes'><b>Login Notes:</b></label></td>
				<td><textarea cols='36' rows='4' id='notes' name='notes' style='width:200px;'><?php echo $row['notes']; ?></textarea></td>
			</tr>

			<tr style="vertical-align:middle;">
				<td style="padding-top:8px;text-align:right;">&nbsp;</td>
				<td style="padding-top:8px;padding-right:8px;">
					<table class='noBorderTable' style='width:100%;'>
						<tr>
							<td style='text-align:left'><input type='button' value='submit' name='submitLoginForm' id ='submitLoginForm'></td>
							<td style='text-align:right'><input type='button' value='cancel' onclick="tb_remove()"></td>
						</tr>
					</table>
				</td>
			</tr>

		</table>

		</div>

		<script type="text/javascript" src="js/forms/loginSubmitForm.js"></script>

		<?php

		break;








	case 'getMonthlyOutlierForm':
		if (isset($_GET['platformID'])) $platformID = $_GET['platformID']; else $platformID = '';
 		if (isset($_GET['publisherPlatformID'])) $publisherPlatformID = $_GET['publisherPlatformID'];

		$archiveInd = $_GET['archiveInd'];
		$year = $_GET['year'];
		$month = $_GET['month'];


		if ($publisherPlatformID) {
			$result = mysql_query("select publisher.name publisher, platform.name platform from publisher_platform pp, publisher, platform where pp.platformID = platform.platformID and pp.publisherID = publisher.publisherID and publisherPlatformID='" . $publisherPlatformID . "';");
		}else{
			$result = mysql_query("select platform.name platform from platform where platformID='" . $platformID . "';");
		}

		while ($row = mysql_fetch_assoc($result)) {
			if ($publisherPlatformID){
				$nameDisplay = $row['publisher'] . " / " . $row['platform'];
			}else{
				$nameDisplay = $row['platform'];
			}
		}
		mysql_free_result($result);


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



		?>

		<div id='div_outlierForm'>
		<table class="thickboxTable" style="background-image:url('images/tbtitle.gif');width:410px;">
		<tr>
		<td><span class='headerText'><?php echo $nameDisplay; ?></span><br /></td>
		</tr>
		<tr>

		<table class='dataTable' style='width:408px;margin-left:2px;'>

		<?php

			if ($totalRows == 0){
				echo "<tr><td>None currently</td></tr>";
			}else{
				while ($row = mysql_fetch_assoc($result)) {
					echo "<tr>";
					echo "<td style='width:170px;'>" . $row['title']. "<span id='span_" . $row['titleStatsMonthlyID'] . "_response' style='color:red;'></span></td>";
					echo "<td style='width:50px;text-align:center;background-color:" . $row['color'] . "'>" . $row['usageCount'] . "</td>";
					echo "<td style='width:55px;'><input type='text' name = 'overrideUsageCount_" . $row['titleStatsMonthlyID'] . "' id = 'overrideUsageCount_" . $row['titleStatsMonthlyID'] . "' value='" . $row['overrideUsageCount'] . "' style='width:50px'></td>";
					echo "<td style='width:80px;'><a href=\"javascript:updateOverride('" . $row['titleStatsMonthlyID'] . "');\" style='font-size:100%;'>update override</a><br /><a href=\"javascript:ignoreOutlier('" . $row['titleStatsMonthlyID'] . "');\" style='font-size:100%;'>ignore outlier</a></td>";
					echo "</tr>";
				}
			}

		?>

		</table>
		</td>
		</tr>
		<tr><td style='text-align:center;width:100%;'><br /><br /><a href='#' onclick='window.parent.tb_remove(); return false'>Close</a></td></tr>
		</table>
		<input type="hidden" id='platformID' name='platformID' value='<?php echo $platformID; ?>'>
		<input type="hidden" id='publisherPlatformID' name='publisherPlatformID' value='<?php echo $publisherPlatformID; ?>'>
		<input type="hidden" id='archiveInd' name='archiveInd' value='<?php echo $archiveInd; ?>'>
		<input type="hidden" id='year' name='year' value='<?php echo $year; ?>'>
		<input type="hidden" id='month' name='month' value='<?php echo $month; ?>'>

		<script type="text/javascript" src="js/forms/outlierSubmitForm.js"></script>
		</div>


		<?php

		break;





	case 'getYearlyOverrideForm':
		if (isset($_GET['platformID'])) $platformID = $_GET['platformID']; else $platformID = '';
 		if (isset($_GET['publisherPlatformID'])) $publisherPlatformID = $_GET['publisherPlatformID'];

		$archiveInd = $_GET['archiveInd'];
		$year = $_GET['year'];


		if ($publisherPlatformID) {
			$result = mysql_query("select publisher.name publisher, platform.name platform from publisher_platform pp, publisher, platform where pp.platformID = platform.platformID and pp.publisherID = publisher.publisherID and publisherPlatformID='" . $publisherPlatformID . "';");
		}else{
			$result = mysql_query("select platform.name platform from platform where platformID='" . $platformID . "';");
		}

		while ($row = mysql_fetch_assoc($result)) {
			if ($publisherPlatformID){
				$nameDisplay = $row['publisher'] . " / " . $row['platform'];
			}else{
				$nameDisplay = $row['platform'];
			}
		}
		mysql_free_result($result);


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

		$totalRows = mysql_num_rows($result);



		?>

		<div id='div_overrideForm'>
		<table class="thickboxTable" style="background-image:url('images/tbtitle.gif');width:410px;">
		<tr>
		<td><span class='headerText'><?php echo $nameDisplay; ?></span><br />(showing only titles for which there were outliers during the year) </td>
		</tr>
		<tr>

		<table class='dataTable' style='width:408px;margin-left:2px;'>

		<?php

			if ($totalRows == 0){
				echo "<tr><td>None currently</td></tr>";
			}else{
				while ($ytd_row = mysql_fetch_assoc($result)) {
				?>
					<tr>
					<td width="149" class='alt'><?php echo $ytd_row['title']; ?></td>
					<td width="40" class='alt'>Total</td>
					<td width="40" class='alt'><?php echo $ytd_row['totalCount']; ?></td>
					<td width="40" class='alt'><input name="overrideTotalCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" id="overrideTotalCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" type="text"value="<?php echo $ytd_row['overrideTotalCount']; ?>" size="6" maxlength="6"/></td>
					<td width="40" class='alt'><a href="javascript:updateYTDOverride('<?php echo $ytd_row['titleStatsYTDID']; ?>', 'overrideTotalCount')">update</a></td>
					</tr>
					<tr>
					<td width="149"><span id="span_<?php echo $ytd_row['titleStatsYTDID']; ?>_response" style='color:red;'></span></td>
					<td width="40">PDF</td>
					<td width="40"><?php echo $ytd_row['PDFCount']; ?></td>
					<td width="40"><input name="overridePDFCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" id="overridePDFCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" type="text"value="<?php echo $ytd_row['overridePDFCount']; ?>" size="6" maxlength="6"/></td>
					<td width="40"><a href="javascript:updateYTDOverride('<?php echo $ytd_row['titleStatsYTDID']; ?>', 'overridePDFCount')">update</a></td>
					</tr>
					<tr>
					<td width="149">&nbsp;</td>
					<td width="40">HTML</td>
					<td width="40"><?php echo $ytd_row['HTMLCount']; ?></td>
					<td width="40"><input name="overrideHTMLCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" id="overrideHTMLCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" type="text"value="<?php echo $ytd_row['overrideHTMLCount']; ?>" size="6" maxlength="6"/></td>
					<td width="40"><a href="javascript:updateYTDOverride('<?php echo $ytd_row['titleStatsYTDID']; ?>', 'overrideHTMLCount')">update</a></td>
					</tr>
				<?php

				}
				mysql_free_result($result);
			}

		?>

		</table>
		</td>
		</tr>
		<tr><td style='text-align:center;width:100%;'><br /><br /><a href='#' onclick='window.parent.tb_remove(); return false'>Close</a></td></tr>
		</table>
		<input type="hidden" id='platformID' name='platformID' value='<?php echo $platformID; ?>'>
		<input type="hidden" id='publisherPlatformID' name='publisherPlatformID' value='<?php echo $publisherPlatformID; ?>'>
		<input type="hidden" id='archiveInd' name='archiveInd' value='<?php echo $archiveInd; ?>'>
		<input type="hidden" id='year' name='year' value='<?php echo $year; ?>'>

		<script type="text/javascript" src="js/forms/overrideSubmitForm.js"></script>
		</div>


		<?php

		break;






	//Add ISSNs
	case 'getAddISSNForm':
		if (isset($_GET['platformID'])) $platformID = $_GET['platformID']; else $platformID = '';
 		if (isset($_GET['publisherPlatformID'])) $publisherPlatformID = $_GET['publisherPlatformID'];
 		if (isset($_GET['titleID'])) $titleID = $_GET['titleID'];


		?>
		<div id='div_addISSNForm'>
		<table class="thickboxTable" style="width:200px;padding:2px;">
		<tr><td colspan='2'><span class='headerText'>Add ISSN</span><br /><br /></td></tr>
		<tr><td style='vertical-align:top;text-align:right;'><label for='ISSNType'><b>ISSN Type</b></label</td>
			<td>
			<select id='ISSNType' name='ISSNType' style='width:90px;'>
			<option value='alt'>alt</option>
			<option value='print'>print</option>
			<option value='online'>online</option>
			</select>
			</td>
		</tr>
		<tr><td style='vertical-align:top;text-align:right;'><label for='ISSN'><b>ISSN</b></label</td><td><input type='text' id='ISSN' name='ISSN' value="" style='width:90px;' /><span id='span_error_ISSN' style='color:red'></span></td></tr>


		<tr style="vertical-align:middle;">
			<td style="padding-top:8px;text-align:right;">&nbsp;</td>
			<td style="padding-top:8px;padding-right:8px;">
				<table class='noBorderTable' style='width:100%;'>
					<tr>
						<td style='text-align:left'><input type='button' value='submit' name='submitISSNForm' id ='submitISSNForm'></td>
						<td style='text-align:right'><input type='button' value='cancel' onclick="tb_remove()"></td>
					</tr>
				</table>
			</td>
		</tr>

		</table>

		</div>

		<input type="hidden" id='titleID' name='titleID' value='<?php echo $titleID; ?>'>
		<input type="hidden" id='platformID' name='platformID' value='<?php echo $platformID; ?>'>
		<input type="hidden" id='publisherPlatformID' name='publisherPlatformID' value='<?php echo $publisherPlatformID; ?>'>

		<script type="text/javascript" src="js/forms/issnSubmitForm.js"></script>


		<?php

		break;











	//Related Titles (this form is display only)
	case 'getRelatedTitlesForm':
 		if (isset($_GET['titleID'])) $titleID = $_GET['titleID'];

		?>
		<div id='div_relatedTitles'>
		<table class="thickboxTable" style="width:250px;padding:2px;">
		<tr><td><span class='headerText'>Associated Titles and ISSNs</span><br /></td></tr>
		<tr>
		<td>

		<table border="0" style="width:246px">
		<?php



			$result = mysql_query("SELECT distinct title, t.titleID titleID FROM title_issn ti, title t, title_issn ti2 where ti.titleID = t.titleID and ti.issn = ti2.issn and ti2.titleID = '" . $titleID . "';");

			while ($row = mysql_fetch_assoc($result)) {

				echo "<tr>";
				echo "<td colspan = '2' style='width:250px'><b>" . $row['title'] . "</b></td>";
				echo "</tr>";


				$issn_result = mysql_query("select issn, issntype from title_issn where titleID = '" . $row['titleID'] . "' order by issnType desc;");
				$totalISSNs = mysql_num_rows($issn_result);
				$currentRow=1;

				while ($issn_row = mysql_fetch_assoc($issn_result)) {
					$displayISSN = substr($issn_row['issn'],0,4) . "-" . substr($issn_row['issn'],4,4);


					echo "<tr>";
					echo "<td style='width:40px'>" . $issn_row['issntype'] . "</td>";
					echo "<td style='width:210px'>" . $displayISSN . "</td>";
					echo "</tr>";

					$currentRow++;
				}


			}
			mysql_free_result($result);
		?>
		</table>

		</td>
		</tr>

		<tr>
		<td style='text-align:center;width:100%;'><br /><br /><a href='#' onclick='window.parent.tb_remove(); return false'>Close</a>
		</td>
		</tr>

		</table>

		</div>


		<?php

		break;












	default:
       echo "Function " . $_REQUEST['function'] . " not set up!";
       break;


}



?>