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
      
                   
});
 


function showPublisherList(platformID){
	divID = 'div_' + platformID;
	
	if (typeof displayInds[divID] == "undefined") displayInds[divID] = 1;

	toggleDivState(divID, displayInds[divID]);

	if (displayInds[divID] == 0) {
		$('#image_' + platformID).attr('src', "images/arrowright.gif");
		$('#link_' + platformID).text('show Publisher list');
		displayInds[divID]=1; 
	} else {
		$('#image_' + platformID).attr('src', "images/arrowdown.gif");
		$('#link_' + platformID).text('hide Publisher list');
		displayInds[divID]=0;
	}
		
	
}


