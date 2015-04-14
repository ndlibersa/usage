<?php
/*
**************************************************************************************************************************
** CORAL Usage Statistics Module
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
include_once 'user.php';


$util = new Utility();
$config = new Configuration();

//get the current page to determine which menu button should be depressed
$currentPage = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentPage);
$currentPage = $parts[count($parts) - 1];

//get CORAL URL for 'Change Module' and logout link
$coralURL = $util->getCORALURL();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CORAL Usage Statistics - <?php echo $pageTitle; ?></title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/jquery.tooltip.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/jquery.autocomplete.css" type="text/css" media="screen" />
<link rel="SHORTCUT ICON" href="images/seahorsefavicon.ico" />
<script type="text/javascript" src="js/plugins/jquery.js"></script>
<script type="text/javascript" src="js/plugins/ajaxupload.3.5.js"></script>
<script type="text/javascript" src="js/plugins/thickbox.js"></script>
<script type="text/javascript" src="js/plugins/jquery.tooltip.js"></script>
<script type="text/javascript" src="js/plugins/jquery.autocomplete.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>
<noscript><font face='arial'><?= _("JavaScript must be enabled in order for you to use CORAL. However, it seems JavaScript is either disabled or not supported by your browser. To use CORAL, enable JavaScript by changing your browser options, then ");?><a href=""><?= _("try again");?></a>. </font></noscript>
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
<span class='smallText' style='color:#526972;'>
<?php
	echo _("Hello, ");
	//user may not have their first name / last name set up
	if ($user->lastName){
		echo $user->firstName . " " . $user->lastName;
	}else{
		echo $user->loginID;
	}
?>
</span>
<br /><?php if($config->settings->authModule == 'Y'){ echo "<a href='" . $coralURL . "auth/?logout'>"._("logout")."</a>"; } ?>
</div>
</td>
</tr>

<tr style='vertical-align:top;'>
<td style='width:870px;height:19px;line-height:18px;vertical-align:top;'>



<a href='index.php'><span class="menubtn<?php if ($currentPage == 'index.php') { echo " active"; } ?>" id="firstmenubtn"><?= _("Home");?></span></a><a href='import.php'><span class="menubtn<?php if ($currentPage == 'import.php') { echo " active"; } ?>"><?= _("File Import");?></span></a><a href='sushi.php'><span class="menubtn<?php if ($currentPage == 'sushi.php') { echo " active"; } ?>">SUSHI</span></a><?php if ($user->isAdmin()) { ?><a href='admin.php'><span class="menubtn<?php if ($currentPage == 'admin.php') { echo " active"; } ?>">Admin</span></a><?php } ?><a href='reporting.php'><span class="menubtn<?php if ($currentPage == 'reporting.php') { echo " active"; } ?>" id="lastmenubtn"><?= _("Report Options");?></span></a>

<?php if ($config->settings->reportingModule == "Y") echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='../reports/' target='_blank' class='menubtn' id='onlyButton'><img src='images/seahorse.png' style='vertical-align:middle; height:100%;'>&nbsp;&nbsp;<b>"._("Usage")."</b> "._("Reports")."</a>"; ?>


</td>
<td style='width:230px;height:19px;' align='right'>

<?php
//only show the 'Change Module' if there are other modules installed or if there is an index to the main CORAL page

if ((file_exists($util->getCORALPath() . "index.php")) || ($config->settings->organizationsModule == 'Y') || ($config->settings->resourcesModule == 'Y') || ($config->settings->cancellationModule == 'Y') || ($config->settings->usageModule == 'Y')) {

	?>

	<div style='text-align:left;'>
		<ul class="tabs">
		<li class="changeMod"><?= _("Change Module");?>&nbsp;▼
			<ul class="coraldropdown">
				<?php if (file_exists($util->getCORALPath() . "index.php")) {?>
				<li><a href="<?php echo $coralURL; ?>" target='_blank'><img src='images/change/coral-main.gif'></a></li>
				<?php
				}
				if ($config->settings->resourcesModule == 'Y') {
				?>
				<li><a href="<?php echo $coralURL; ?>resources/" target='_blank'><img src='images/change/coral-resources.gif'></a></li>
				<?php
				}
				if ($config->settings->organizationsModule == 'Y') {
				?>
				<li><a href="<?php echo $coralURL; ?>organizations/" target='_blank'><img src='images/change/coral-organizations.gif'></a></li>
				<?php
				}
				if ($config->settings->licensingModule == 'Y') {
				?>
				<li><a href="<?php echo $coralURL; ?>licensing/" target='_blank'><img src='images/change/coral-licensing.gif'></a></li>
				<?php
				}
				if ($config->settings->cancellationModule == 'Y') {
				?>
				<li><a href="<?php echo $coralURL; ?>cancellation/" target='_blank'><img src='images/change/coral-cancellation.gif'></a></li>
				<?php } ?>
			</ul>
		</li>
		</ul>
        <select name="lang" id="lang" class="dropDownLang">
               <?php
                $fr="<option value='fr' selected='selected'>"._("French")."</option><option value='en'>"._("English")."</option>";
                $en="<option value='fr'>"._("French")."</option><option value='en' selected='selected'>"._("English")."</option>";
                if(isset($_COOKIE["lang"])){
                    if($_COOKIE["lang"]=='fr'){
                        echo $fr;
                    }else{
                        echo $en;
                    }
                }else{
                    $defLang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
                    if($defLang=='fr'){
                        echo $fr;
                    }else{
                        echo $en;
                    }
                }
                ?>

            </select>
        </div>
        <script>
            $("#lang").change(function() {
                setLanguage($("#lang").val());
                location.reload();
            });

            function setLanguage(lang) {
                var wl = window.location, now = new Date(), time = now.getTime();
                var cookievalid=86400000; // 1 jour (1000*60*60*24)
                time += cookievalid;
                now.setTime(time);
                document.cookie ='lang='+lang+';path=/'+';domain='+wl.host+';expires='+now;
            }
        </script>
	<?php

} else {
	echo "&nbsp;";
}

?>

</td>
</tr>
</table>
<span id='span_message' style='color:red;text-align:left;'><?php if (isset($err)) echo $err; ?></span>