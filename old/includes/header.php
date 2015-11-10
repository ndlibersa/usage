<?php
/*
**************************************************************************************************************************
** CORAL Organizations Module v. 1.0
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
*/

include_once 'directory.php';

$util = new Utility();
$config = new Configuration();

//get the current page to determine which menu button should be depressed
$currentPage = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentPage);
$currentPage = $parts[count($parts) - 1];


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CORAL Usage Statistics - <?php echo $pageTitle; ?></title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen" />
<link rel="SHORTCUT ICON" href="images/favicon.ico" />
<script type="text/javascript" src="js/plugins/jquery.js"></script>
<script type="text/javascript" src="js/plugins/ajaxupload.3.5.js"></script>
<script type="text/javascript" src="js/plugins/thickbox.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>
<noscript><font face=arial>JavaScript must be enabled in order for you to use CORAL. However, it seems JavaScript is either disabled or not supported by your browser. To use CORAL, enable JavaScript by changing your browser options, then <a href="">try again</a>. </font></noscript>
<center>
<div class="wrapper">
<center>
<table>
<tr>
<td style='vertical-align:top;'>
<div style="text-align:left;">

<center>
<table id="titleTable" style="background-image:url('images/usagetitle.gif');background-repeat:no-repeat;width:900px;text-align:left;">
<tr style='vertical-align:top;'>
<td style='height:53px;'>
&nbsp;
</td>
<td style='text-align:right;height:53px;'>
<div style='margin-top:1px;'>
<span style='color:red;font-size:90%;'>&nbsp;</span>
</div>
</td>
</tr>

<tr style='vertical-align:top'>
<td style='width:870px;height:19px;'>
<a href='index.php'><img src="images/menu/menu-home<?php if ($currentPage == 'index.php') { echo "-on"; } ?>.gif" hover="images/menu/menu-home-over.gif" class="rollover" /></a><img src='images/menu/menu-bar.gif'><a href='fileupload.php'><img src='images/menu/menu-fileimport<?php if ($currentPage == 'fileupload.php') { echo "-on"; } ?>.gif' hover="images/menu/menu-fileimport-over.gif" class="rollover"></a><img src='images/menu/menu-bar.gif'><a href='publishersPlatforms.php'><img src="images/menu/menu-publisherplatformupdate<?php if ($currentPage == 'publishersPlatforms.php') { echo "-on"; } ?>.gif" hover="images/menu/menu-publisherplatformupdate-over.gif" class="rollover" /></a><img src='images/menu/menu-bar.gif'><a href='admin.php'><img src='images/menu/menu-admin<?php if ($currentPage == 'admin.php') { echo "-on"; } ?>.gif' hover="images/menu/menu-admin-over.gif" class="rollover" /></a><img src='images/menu/menu-bar.gif'><a href='reporting.php'><img src="images/menu/menu-reportingoptions<?php if ($currentPage == 'reporting.php') { echo "-on"; } ?>.gif" hover="images/menu/menu-reportingoptions-over.gif" class="rollover" /></a>
</td>
<td style='width:130px;height:19px;' align='right'>

<?php
//only show the 'Change Module' if there are other modules installed or if there is an index to the main CORAL page

if ((file_exists($util->getCORALPath() . "index.php")) || ($config->settings->organizationsModule == 'Y') || ($config->settings->resourcesModule == 'Y') || ($config->settings->cancellationModule == 'Y') || ($config->settings->usageModule == 'Y')) {

	//get CORAL URL for 'Change Module'
	$coralURL = $util->getCORALURL();

	?>

	<div style='text-align:left;'>
		<ul class="tabs">
		<li style="background: url('images/change/coral-change.gif') no-repeat right;">&nbsp;
			<ul class="coraldropdown">
				<?php if (file_exists($util->getCORALPath() . "index.php")) {?>
				<li><a href="<?php echo $coralURL; ?>" target='_blank'><img src='images/change/coral-main.gif'></a></li>
				<?php
				}
				if ($config->settings->licensingModule == 'Y') {
				?>
				<li><a href="<?php echo $coralURL; ?>licensing/" target='_blank'><img src='images/change/coral-licensing.gif'></a></li>
				<?php
				}
				if ($config->settings->organizationsModule == 'Y') {
				?>
				<li><a href="<?php echo $coralURL; ?>organizations/" target='_blank'><img src='images/change/coral-organizations.gif'></a></li>
				<?php
				}
				if ($config->settings->resourcesModule == 'Y') {
				?>
				<li><a href="<?php echo $coralURL; ?>resources/" target='_blank'><img src='images/change/coral-resources.gif'></a></li>
				<?php
				}
				if ($config->settings->cancellationModule == 'Y') {
				?>
				<li><a href="<?php echo $coralURL; ?>cancellation/" target='_blank'><img src='images/change/coral-cancellation.gif'></a></li>
				<? } ?>
			</ul>
		</li>
		</ul>

	</div>
	<?php

} else {
	echo "&nbsp;";
}

?>

</td>
</tr>
</table>
<span id='span_message' style='color:red;text-align:left;'><?php if (isset($err)) echo $err; ?></span>