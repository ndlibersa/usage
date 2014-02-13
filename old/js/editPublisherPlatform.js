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

 $(document).ready(function(){
	updateNotesLoginDetails();
	updateFullStatsDetails();
	updateTitleDetails();
	    
 });

 viewAll=0;


 $(".showNotes").click(function () {
 	if (viewAll == "0"){
		$('#div_displayNotes').show();
		$('#div_displayStats').hide();
		$('#div_displayTitles').hide();
	}
	return false;
 });
 
 
 
  $(".showStats").click(function () {
  
  	if (viewAll == "0"){
 		$('#div_displayNotes').hide();
 		$('#div_displayStats').show();
 		$('#div_displayTitles').hide();
 	}
 	
 	return false;
 });
 
 
 
  $(".showTitles").click(function () {
  	if (viewAll == "0"){
 		$('#div_displayNotes').hide();
 		$('#div_displayStats').hide();
 		$('#div_displayTitles').show();
 	}
 	return false;
 });





 function updateNotesLoginDetails(){

       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getNotesLoginDetails&publisherPlatformID=" + $('#publisherPlatformID').val() + "&platformID=" + $('#platformID').val(),
          success:    function(html) { 
          	$('#div_notesLoginDetails').html(html);
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
          	$('#div_statsDetails').html(html);
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
          data:       "action=getTitleDetails&publisherPlatformID=" + $('#publisherPlatformID').val() + "&platformID=" + $('#platformID').val(),
          success:    function(html) { 
          	$('#div_titleDetails').html(html);
          	tb_reinit();
          }
      });

 }


  function deleteInterfaceNotes(platformInterfaceID){

     if (confirm("Do you really want to remove this interface note?") == true) {
	$.ajax({
          type:       "GET",
          url:        "ajax_processing.php",
          cache:      false,
          data:       "action=deleteInterfaceNotes&interfaceID=" + platformInterfaceID,
          success:    function(html) { 
		  updateNotesLoginDetails(); 
          }
       });
     }

  }



  function deletePublisherNote(publisherNoteID){

     if (confirm("Do you really want to remove this publisher note?") == true) {
	$.ajax({
          type:       "GET",
          url:        "ajax_processing.php",
          cache:      false,
          data:       "action=deletePublisherNotes&notesID=" + publisherNoteID,
          success:    function(html) { 
		  updateNotesLoginDetails(); 
          }
       });
     }

  }




  function deleteLogin(interfaceLoginID){
     if (confirm("Do you really want to remove this login information?") == true) {
	$.ajax({
          type:       "GET",
          url:        "ajax_processing.php",
          cache:      false,
          data:       "action=deleteLogin&interfaceLoginID=" + interfaceLoginID,
          success:    function(html) { 
		  updateNotesLoginDetails(); 
          }
       });
     }
  }


  function deleteMonth(month, year, archiveInd, publisherPlatformID, platformID){
	if (confirm("Do you really want to delete this month?") == true) {

		$.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=deleteMonth&publisherPlatformID=" + publisherPlatformID + "&platformID=" + platformID + "&month=" + month + "&year=" + year + "&archiveInd=" + archiveInd,
		  success:    function(html) { 
			  $("#tr_" + platformID + "_" + publisherPlatformID + "_" + year + "_" + month + "_" + archiveInd).remove();
		  }
	         });


	}
  }

  function deleteISSN(titleISSNID){
	if (confirm("Do you really want to delete this ISSN?") == true) {

		$.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=removeISSN&titleISSNID=" + titleISSNID,
		  success:    function(html) { 
			  $("#tr_" + titleISSNID).remove();
		  }
	         });


	}
  }