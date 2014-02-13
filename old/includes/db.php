<?php


//****************************************************
// Configuration Section Begin
//****************************************************

// Some global variables needed.
$MySQLServer = "mysql.library.nd.edu";
$MySQLUser = "coral";
$MySQLPassword = "dXoFvW4F";
$MySQLDbName = 'coral_usage_prod';
$Base_URL = 'https://coral.library.nd.edu/usage/';


// Error messages

$contact = "Contact <a href=\"mailto:rmalott@nd.edu\">Robin Malott</a> regarding this problem.<br/>";
$error_text = "Please include the error text above in your email.<br/><br/>";


//****************************************************
// Configuration Section End
//****************************************************


//Creates the connection result string ($link) used throughout the php scripts

if(!$link = mysql_connect("$MySQLServer", "$MySQLUser", "$MySQLPassword")){
	handle_error(mysql_error(), $contact, $error_text);
}



if(!mysql_select_db("$MySQLDbName")){
	handle_error("Could not connect to E-Resources Database.", $contact, $error_text);
}


// Error handling function used in all scripts

function handle_error ($msg, $contact, $error_text){
  	echo "<H3>There is a problem with the database:<br/>\n$msg</H3>";
	echo "<br/>\n$contact $error_text \n";
	mysql_free_result($result);
	mysql_close($link);
  	exit();
}


error_reporting(E_ERROR | E_WARNING | E_PARSE);


?>