function isEmpty(value) {
    var error = "";
 
    if (value.length == 0) {
        return true;
    }else{    
    	return false;  
    }
}


function isISSN (value) {
  
  value = value.replace('-', '');
  
  if (value.length == 8) {
  	var objRegExp  = /^[a-zA-Z0-9]*$/;
		
  	return (objRegExp.test(value));
  }else{
  	return false;
  }

}



function isYear (value) {
    
  if (value.length == 4) {
  	var objRegExp  = /^19|20[0-9][0-9]$/;
		
  	return (objRegExp.test(value));
  }else{
  	return false;
  }

}


function isNumber (value) {

  var objRegExp  = /^[0-9]*$/;
  return (objRegExp.test(value));
  
}