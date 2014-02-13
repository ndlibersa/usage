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

 $(function(){


	 $("#submitLoginForm").click(function () {
	 	submitLogin();
	 });



	//do submit if enter is hit
	$('#loginID').keyup(function(e) {
	      if(e.keyCode == 13) {
		submitLogin();
	      }
	}); 


	//do submit if enter is hit
	$('#password').keyup(function(e) {
	      if(e.keyCode == 13) {
		submitLogin();
	      }
	}); 

	//do submit if enter is hit
	$('#url').keyup(function(e) {
	      if(e.keyCode == 13) {
		submitLogin();
	      }
	}); 

	  	 
 });
 





function submitLogin(){

	$('#submitLoginForm').attr("disabled", "disabled"); 
	  $.ajax({
		 type:       "POST",
		 url:        "ajax_processing.php?action=submitLogin",
		 cache:      false,
		 data:       { interfaceLoginID: $("#editInterfaceLoginID").val(), publisherPlatformID: $("#publisherPlatformID").val(), platformID: $("#platformID").val(), loginID: $("#loginID").val(), password: $("#password").val(), loginURL: $("#loginURL").val(), notes: $("#notes").val() },
		 success:    function(html) {
				window.parent.tb_remove();
				window.parent.updateNotesLoginDetails();
				return false;
		 }


	 });

}