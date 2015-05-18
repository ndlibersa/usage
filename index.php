
<?php

/*
**************************************************************************************************************************
** CORAL Usage Statistics v. 1.1
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


session_start();

include_once 'directory.php';

//print header
$pageTitle=_('Home');
include 'templates/header.php';

//used for creating a "sticky form" for back buttons
//except we don't want it to retain if they press the 'index' button
//check what referring script is

if ($_SESSION['ref_script'] != "publisherPlatform.php"){
	$reset = "Y";
}

$_SESSION['ref_script']=$currentPage;


?>

<div style='text-align:left;'>
<table class="headerTable" style="background-image:url('images/header.gif');background-repeat:no-repeat;">
<tr style='vertical-align:top;'>
<td style="width:155px;padding-right:10px;">

	<table class='noBorder'>
	<tr><td style='text-align:left;width:75px;' align='left'>
	<span style='font-size:130%;font-weight:bold;'><?php echo _("Search");?></span><br />
	<a href='javascript:void(0)' class='newSearch'><?php echo _("new search");?></a>
	</td>
	<td><div id='div_feedback'>&nbsp;</div>
	</td></tr>
	</table>

	<table class='borderedFormTable' style="width:150px">

	<tr>
	<td class='searchRow'><label for='searchName'><b><?php echo _("Name (contains)");?></b></label>
	<br />
	<input type='text' name='searchName' id='searchName' style='width:145px' value="<?php if ($reset != 'Y') echo $_SESSION['plat_searchName']; ?>" /><br />
	<div id='div_searchName' style='<?php if ((!$_SESSION['plat_searchName']) || ($reset == 'Y')) echo "display:none;"; ?>margin-left:118px;margin-top:5px'><input type='button' name='btn_searchName' value='<?php echo _("go!");?>' class='searchButton' /></div>
	<br />
	</td>
	</tr>


	<tr>
	<td class='searchRow'><label for='searchFirstLetter'><b><?php echo _("Starts with");?></b></label>
	<br />
	<?php
	$platform = new Platform();

	$alphArray = range('A','Z');
	$pAlphArray = $platform->getAlphabeticalList;

	foreach ($alphArray as $letter){
		if ((isset($pAlphArray[$letter])) && ($pAlphArray[$letter] > 0)){
			echo "<span class='searchLetter' id='span_letter_" . $letter . "'><a href='javascript:setStartWith(\"" . $letter . "\")'>" . $letter . "</a></span>";
			if ($letter == "N") echo "<br />";
		}else{
			echo "<span class='searchLetter'>" . $letter . "</span>";
			if ($letter == "N") echo "<br />";
		}
	}
	?>
	<br />
	</td>
	</tr>

	</table>

</td>
<td>
<div id='div_searchResults'></div>
</td></tr>
</table>
</div>
<br />
<script type="text/javascript" src="js/index.js"></script>
<script type='text/javascript'>
<?php
  //used to default to previously selected values when back button is pressed
  //if the startWith is defined set it so that it will default to the first letter picked
  if ((isset($_SESSION['plat_startWith'])) && ($reset != 'Y')){
	  echo "startWith = '" . $_SESSION['plat_startWith'] . "';";
	  echo "$(\"#span_letter_" . $_SESSION['plat_startWith'] . "\").removeClass('searchLetter').addClass('searchLetterSelected');";
  }

  if ((isset($_SESSION['plat_pageStart'])) && ($reset != 'Y')){
	  echo "pageStart = '" . $_SESSION['plat_pageStart'] . "';";
  }

  if ((isset($_SESSION['plat_recordsPerPage'])) && ($reset != 'Y')){
	  echo "recordsPerPage = '" . $_SESSION['plat_recordsPerPage'] . "';";
  }

  if ((isset($_SESSION['plat_orderBy'])) && ($reset != 'Y')){
	  echo "orderBy = \"" . $_SESSION['plat_orderBy'] . "\";";
  }

  echo "</script>";

  //print footer
  include 'templates/footer.php';
?>