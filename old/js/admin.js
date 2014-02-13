/*
**************************************************************************************************************************
** CORAL Licensing Module v. 1.0
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

      updateEmailAddressTable();
      updateOutlierTable();
      
      


	//do submit if enter is hit
	$('#overageCount').keyup(function(e) {
	      if(e.keyCode == 13) {
		updateOutlier();
	      }
	});


	//do submit if enter is hit
	$('#overagePercent').keyup(function(e) {
	      if(e.keyCode == 13) {
		updateOutlier();
	      }
	});	
	
 });




 function updateEmailAddressTable(){

       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getAddressTable",
          success:    function(html) { 
          	$('#div_emailAddresses').html(html);
          	tb_reinit();
          }
      });

 }



 function processEmailAddress(){
	if ($('#updateLogEmailAddressID').val() != '') {
       		updateEmailAddress();
       	}else{
       		addEmailAddress();
       	}

 }
 


 function addEmailAddress(){

       $('#span_EmailAddress_response').html('<img src = "images/circle.gif">&nbsp;&nbsp;Processing...');

       $.ajax({
	  type:       "GET",
	  url:        "ajax_processing.php",
	  cache:      false,
	  data:       "action=addEmailAddress&emailAddress=" + escape($('#emailAddress').val()),
	  success:    function(html) { 
		  $('#span_EmailAddress_response').html(html);  

		  // close the span in 3 secs
		  setTimeout("emptyResponse('EmailAddress');",3000); 

		  updateEmailAddressTable(); 
		  
		  window.parent.tb_remove();

	  }
      });

 }

 function updateEmailAddress(){
	$.ajax({
          type:       "GET",
          url:        "ajax_processing.php",
          cache:      false,
          data:       "action=updateEmailAddress&logEmailAddressID=" + $('#updateLogEmailAddressID').val() + "&emailAddress=" + escape($('#emailAddress').val()),
          success:    function(html) { 
		  updateEmailAddressTable(); 
		  window.parent.tb_remove();
          }
       });

 }

 
 


  function deleteEmailAddress(addressID){

     if (confirm("Do you really want to delete this email address?") == true) {
	$.ajax({
          type:       "GET",
          url:        "ajax_processing.php",
          cache:      false,
          data:       "action=deleteEmailAddress&emailAddressID=" + addressID,
          success:    function(html) { 
		  updateEmailAddressTable(); 
          }
       });
     }

  }




 function updateOutlierTable(){

       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getOutlierTable",
          success:    function(html) { 
          	$('#div_outliers').html(html);
          	tb_reinit();
          }
      });

 }


 
 function emptyResponse(tableName){
 	$('#span_' + tableName + "_response").html("");
 }
 
 
 

  function updateOutlier(){

	  if (validateForm() === true) {
		$.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=updateOutlier&outlierID=" + $('#updateOutlierID').val() + "&overageCount=" + $('#overageCount').val() + "&overagePercent=" + $('#overagePercent').val(),
		  success:    function(html) { 
			  updateOutlierTable(); 
			  window.parent.tb_remove();
		  }
	       });

	  }

 }
 
 
 
 //validates fields for outlier form
 function validateForm (){
 	myReturn=0;
 	if (!validateNumber('overageCount','Count over must be a number.')) myReturn="1";
 	if (!validateNumber('overagePercent','% over must be a number.')) myReturn="1";
 
 	if (myReturn == "1"){
 		return false;
 	}else{
 		return true;
 	}
}


