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

$pageTitle=_('Not Available');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo _("Usage Statistics Module") . " - " . $pageTitle;?></title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
</head>
<body>
<center>
<div class="wrapper">
<center>
<table>
<tr>
<td style='vertical-align:top;'>
<div style="text-align:left;">

<center>
<table class="titleTable" style="background-image:url('images/usagetitle.jpg');background-repeat:no-repeat;width:900px;height:120px;text-align:left;">
<tr style='vertical-align:bottom'>
<td>
&nbsp;
</td>

<td style='text-align:right;'>
&nbsp;
</td>

</tr>
</table>

<table class="headerTable">
<tr><td>
<?php
if ($_GET['errorMessage']){
	echo "<h3>" . $_GET['errorMessage'] . "</h3>";
}else{
	echo "<h3>" . _("Please contact your Administrator for access to the Usage Statistics Module.") . "</h3>";
}
?>

</td></tr>
</table>

<br />
<br />
<br />
</div>


</td>
</tr>
</table>
<br />
</center>

</div>

</body>
</html>
