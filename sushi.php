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
$pageTitle='SUSHI Import';
include 'templates/header.php';

	?>

	<script type="text/javascript" src="js/sushi.js"></script>

	<table class="headerTable" style="background-image:url('images/header.gif');background-repeat:no-repeat;">
		<tr><td>
			<table style='width:897px;'>
			<tr style='vertical-align:top'>
			<td><span class="headerText">SUSHI Administration</span><br /></td>
			<td style='text-align:right;'>&nbsp;</td>
			</tr>
			</table>


			<a href='ajax_forms.php?action=getAddPlatformForm&height=150&width=325&modal=true' class='thickbox' id='uploadDocument'>Add new platform for SUSHI</a>
			<br /><br /><div id="div_run_feedback"></div><br />

			<div class="headerText" style='margin-bottom:9px;'>Outstanding Import Queue&nbsp;&nbsp;&nbsp;<span id='span_outstanding_feedback'></span></div>

			<div id="div_OutstandingSushiImports"></div>

			<br />

			<div class="headerText" style='margin-bottom:9px;'>Upcoming SUSHI Imports&nbsp;&nbsp;&nbsp;<span id='span_upcoming_feedback'></span></div>
			

			<div id="div_UpcomingSushiImports"></div>



			<br /><br /><br />

			<div class="headerText" style='margin-bottom:9px;'>Unscheduled SUSHI Imports&nbsp;&nbsp;&nbsp;<span id='span_unscheduled_feedback'></span></div>
			<div id="div_run_feedback"></div>

			<div id="div_UnscheduledSushiImports"></div>






		</td></tr>
	</table>



<?php
  //print footer
  include 'templates/footer.php';

?>