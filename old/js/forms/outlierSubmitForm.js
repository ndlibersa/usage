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

	  	 
 });
 





function updateOverride(titleStatsMonthlyIDValue){

  if (validateForm(titleStatsMonthlyIDValue) === true) {

	  $.ajax({
		 type:       "POST",
		 url:        "ajax_processing.php?action=updateOverride",
		 cache:      false,
		 data:       { overrideUsageCount: $("#overrideUsageCount_" + titleStatsMonthlyIDValue).val(), titleStatsMonthlyID: titleStatsMonthlyIDValue },
		 success:    function(html) {
		 	$('#span_' + titleStatsMonthlyIDValue + '_response').html("<br />" + html);
		 	

			 // close the span in 3 secs
			 setTimeout("emptyResponse('" + titleStatsMonthlyIDValue + "');",3000); 		 	
		 }


	 });
	 
   }

}





function ignoreOutlier(titleStatsMonthlyIDValue){


	  $.ajax({
		 type:       "POST",
		 url:        "ajax_processing.php?action=ignoreOutlier",
		 cache:      false,
		 data:       { titleStatsMonthlyID: titleStatsMonthlyIDValue },
		 success:    function(html) {
		 	updateImportTable();
		 }


	 });
	 

}


 function updateImportTable(){
       $.ajax({
          type:       "GET",
          url:        "ajax_forms.php",
          cache:      false,
          data:       "action=getMonthlyOutlierForm&platformID=" + $("#platformID").val() + "&publisherPlatformID=" + $("#publisherPlatformID").val() + "&archiveInd=" + $("#archiveInd").val() + "&year=" + $("#year").val() + "&month=" + $("#month").val(),
          success:    function(html) { 
          	$('#div_outlierForm').html(html);
          }
       });

 }




//validates fields
function validateForm (titleStatsMonthlyIDValue){
	myReturn=0;
	if (!validateRequired('overrideUsageCount_' + titleStatsMonthlyIDValue, 'Count is required.')) myReturn="1";
	if (myReturn == "1"){
		return false;
	}else{
		return true;
	}
}




 function emptyResponse(spanname){
 	$('#span_' + spanname + '_response').html("");
 }
 