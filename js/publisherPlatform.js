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
*/

 $(document).ready(function(){


 });

 viewAll=0;

 $(".showImports").click(function () {
 	if (viewAll == 0){
		$('.usage_tab_content').hide();
    		$('#div_imports').show();
    		updateImportDetails();
	}
	return false;
 });

 $(".showLogins").click(function () {
 	if (viewAll == 0){
    $('.usage_tab_content').hide();
    $('#div_logins').show();
    updateLoginDetails();
	}
	return false;
 });
 
 
  $(".showStatistics").click(function () {
  
  	if (viewAll == 0){
    		$('.usage_tab_content').hide();
    		$('#div_statistics').show();
    		updateFullStatsDetails();
 	}
 	
 	return false;
 });
 
 
 
  $(".showTitles").click(function () {
    if (viewAll == 0){
    $('.usage_tab_content').hide();
    $('#div_titles').show();
    updateTitleDetails();
  }
  return false;
 });

 
  $(".showSushi").click(function () {
  	if (viewAll == 0){
    $('.usage_tab_content').hide();
    $('#div_sushi').show();
    updateSushiDetails();
 	}
 	return false;
 });





 function updateImportDetails(){

       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getImportDetails&publisherPlatformID=" + $('#publisherPlatformID').val() + "&platformID=" + $('#platformID').val(),
          success:    function(html) { 
          	$(".div_mainContent").html(html);
          	tb_reinit();
          }
      });

 }



 function updateLoginDetails(){

       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getLoginDetails&publisherPlatformID=" + $('#publisherPlatformID').val() + "&platformID=" + $('#platformID').val(),
          success:    function(html) { 
          	$(".div_mainContent").html(html);
          	tb_reinit();
          }
      });

 }



 function updateFullStatsDetails(){

       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getFullStatsDetails&publisherPlatformID=" + $('#publisherPlatformID').val() + "&platformID=" + $('#platformID').val(),
          success:    function(html) { 
          	$(".div_mainContent").html(html);
          	tb_reinit();
          }
      });

 }




 function updateTitleDetails(titleID){
 	if (titleID != ''){
 		$('#span_' + titleID + '_feedback').html('&nbsp;&nbsp;<img src = "images/circle.gif">Loading...');
 	}
       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getTitleSpreadsheets&publisherPlatformID=" + $('#publisherPlatformID').val() + "&platformID=" + $('#platformID').val(),
          success:    function(html) { 
          	$(".div_mainContent").html(html);
          	tb_reinit();
          }
      });

 }

 function updateSushiDetails(){

       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getSushiDetails&publisherPlatformID=" + $('#publisherPlatformID').val() + "&platformID=" + $('#platformID').val(),
          success:    function(html) { 
            $(".div_mainContent").html(html);
            tb_reinit();
          }
      });

 }




  function deletePlatformNote(platformNoteID){

     if (confirm("Do you really want to remove this interface note?") == true) {
	$.ajax({
          type:       "GET",
          url:        "ajax_processing.php",
          cache:      false,
          data:       "action=deletePlatformNote&platformNoteID=" + platformNoteID,
          success:    function(html) { 
		      updateNotesDetails(); 
          }
       });
     }

  }



  function deletePublisherNote(publisherPlatformNoteID){

     if (confirm("Do you really want to remove this Publisher note?") == true) {
	$.ajax({
          type:       "GET",
          url:        "ajax_processing.php",
          cache:      false,
          data:       "action=deletePublisherNote&publisherPlatformNoteID=" + publisherPlatformNoteID,
          success:    function(html) { 
		  updateNotesDetails(); 
          }
       });
     }

  }




  function deleteExternalLogin(externalLoginID){
     if (confirm("Do you really want to remove this login information?") == true) {
	$.ajax({
          type:       "GET",
          url:        "ajax_processing.php",
          cache:      false,
          data:       "action=deleteExternalLogin&externalLoginID=" + externalLoginID,
          success:    function(html) { 
		  updateLoginDetails(); 
          }
       });
     }
  }


  function deleteMonth(resourceType, month, year, archiveInd, publisherPlatformID, platformID){
	if (confirm("Do you really want to delete this month?") == true) {

		$.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=deleteMonth&publisherPlatformID=" + publisherPlatformID + "&platformID=" + platformID + "&resourceType=" + resourceType + "&month=" + month + "&year=" + year + "&archiveInd=" + archiveInd,
		  success:    function(html) { 
			  updateFullStatsDetails();
		  }
	         });


	}
  }

  function deleteIdentifier(titleIdentifierID, identifierType){
	if (confirm("Do you really want to delete this " + identifierType + "?") == true) {

		$.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=removeIdentifier&titleIdentifierID=" + titleIdentifierID,
		  success:    function(html) { 
			  $("#tr_" + titleIdentifierID).remove();
		  }
	         });


	}
  }

  function toggle(el, dID){
    console.log($(el));
    $(el).find('#' + dID).show();
  }
