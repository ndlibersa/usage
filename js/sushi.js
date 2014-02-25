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
	
	updateOutstandingSushiImports(); 

  updateUpcomingSushiImports();    

	updateUnscheduledSushiImports();     
                   
});



 function updateOutstandingSushiImports(){

       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getOutstandingSushiImports",
          success:    function(html) { $('#div_OutstandingSushiImports').html(html);
          	tb_reinit();
          }
      });
      
 }

 function updateUpcomingSushiImports(){

       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getUpcomingSushiImports",
          success:    function(html) { $('#div_UpcomingSushiImports').html(html);
          	tb_reinit();
          }
      });
      
 }
 

 function updateUnscheduledSushiImports(){

       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getUnscheduledSushiImports",
          success:    function(html) { $('#div_UnscheduledSushiImports').html(html);
            tb_reinit();
          }
      });
      
 }


 function deleteImportLog(importLogID){
 
  if (confirm("Do you really want to delete this import?") == true) {

         $('#span_outstanding_feedback').html('<img src = "images/circle.gif">&nbsp;&nbsp;Processing...');
         $.ajax({
      type:       "GET",
      url:        "ajax_processing.php",
      cache:      false,
      data:       "action=deleteImportLog&importLogID=" + importLogID,
      success:    function(html) { 
      $('#span_outstanding_feedback').html(html);  

      // close the span in 5 secs
      setTimeout("emptyResponse('span_outstanding_feedback');",5000); 

      updateOutstandingSushiImports();  
      tb_reinit();
      }
        });

  }
 }


function runService(sushiServiceID){
	$('#div_run_feedback').html('<img src = "images/circle.gif">&nbsp;&nbsp;Running...<br />');

	$.ajax({
          type:       "GET",
          url:        "ajax_processing.php",
          cache:      false,
          data:       "action=runSushiService&sushiServiceID=" + sushiServiceID,
          success:    function(html) { 
		    
		    $('#div_run_feedback').html("<br />" + html + "<br />");      

			updateOutstandingSushiImports(); 
			updateUpcomingSushiImports(); 
          }
       });
		
	
}


