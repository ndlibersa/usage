<?php
require "includes/db.php";
include "includes/common.php";

$action = $_REQUEST['action'];

switch ($action) {

    case 'addEmailAddress':
    	if (mysql_query("insert into log_email_address (emailAddress) values ('" . $_GET['emailAddress'] . "');")){
    		echo "Address has been added";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;


    case 'updateEmailAddress':
    	if (mysql_query("update log_email_address set emailAddress = '" . $_GET['emailAddress'] . "' where logEmailAddressID = '" . $_GET['logEmailAddressID'] . "';")){
    		echo "Address has been updated";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;

    case 'deleteEmailAddress':
    	if (mysql_query("delete from log_email_address where logEmailAddressID = " . $_GET['emailAddressID'])){
    		echo "Address has been deleted";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;


    case 'updateOutlier':
    	$outlierID = $_GET['outlierID'];
    	$overageCount = $_GET['overageCount'];
    	$overagePercent = $_GET['overagePercent'];

		if (mysql_query("update outlier set overageCount = '" . $overageCount . "', overagePercent='" . $overagePercent . "' where outlierID='" . $outlierID . "';")){
    		echo "Outlier has been updated";
    	}else{
    		echo "Error processing your request - please verify data looks correct and contact support!";
    	}

        break;



    case 'submitInterface':
    	$notes = str_replace("'","''",$_POST['interfaceNotes']);

    	//if this is an update
    	if ((isset($_POST['platformInterfaceID'])) && ($_POST['platformInterfaceID'] != '')){

			if (mysql_query("update platform_interface set startYear='" . $_POST['startYear'] . "',
				endYear='" . $_POST['endYear'] . "',
				counterCompliantInd='" . $_POST['counterCompliantInd'] . "',
				notCounterCompliantInd='" . $_POST['notCounterCompliantInd'] . "',
				interfaceNotes='" . $notes . "' where platformInterfaceID = '" . $_POST['platformInterfaceID']. "';")){

				echo "Interface Notes have been updated";
			}else{
				echo "Error processing your request - please contact support!";
			}

    	}else{

			if (mysql_query("insert into platform_interface (platformID, startYear, endYear, counterCompliantInd, notCounterCompliantInd, interfaceNotes)
				values ('" . $_POST['platformID'] . "', '" . $_POST['startYear'] . "', '" . $_POST['endYear'] . "', '" . $_POST['counterCompliantInd'] . "', '" . $_POST['notCounterCompliantInd'] . "', '" . $notes . "');")){

				echo "New Interface Notes have been added";
			}else{
				echo "Error processing your request - please contact support!";
			}
		}

        break;


    case 'deleteInterfaceNotes':
    	if (mysql_query("delete from platform_interface where platformInterfaceID = " . $_GET['interfaceID'])){
    		echo "Interface Notes have been deleted";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;




    case 'submitPublisherNotes':
    	$notes = str_replace("'","''",$_POST['notes']);


    	//update
    	if ((isset($_POST['publisherNotesID'])) && ($_POST['publisherNotesID'] != '')){

			if (mysql_query("update publisher_notes set startYear='" . $_POST['startYear'] . "',
				endYear='" . $_POST['endYear'] . "',
				notes='" . $notes . "' where publisherNotesID = '" . $_POST['publisherNotesID']. "';")){

				echo "Notes have been updated";
			}else{
				echo "Error processing your request - please contact support!";
			}

		}else{
			if (mysql_query("insert into publisher_notes (publisherPlatformID, startYear, endYear, notes)
				values ('" . $_POST['publisherPlatformID'] . "', '" . $_POST['startYear'] . "', '" . $_POST['endYear'] . "', '" . $notes . "');")){

				echo "New Notes have been added";
			}else{
				echo "Error processing your request - please contact support!";
			}
		}


        break;


    case 'deletePublisherNotes':
    	if (mysql_query("delete from publisher_notes where publisherNotesID = " . $_GET['notesID'])){
    		echo "Notes have been deleted";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;



    case 'submitLogin':
    	$notes = str_replace ("'","''",$_POST['notes']);
    	$loginURL = str_replace ("'","",$_POST['loginURL']);


    	//update
    	if ((isset($_POST['interfaceLoginID'])) && ($_POST['interfaceLoginID'] != '')){
			if (mysql_query("update interface_login set loginID='" . $_POST['loginID'] . "',
				password='" . $_POST['password'] . "',
				url='" . $loginURL . "',
				notes='" . $notes . "'
				where interfaceLoginID = '" . $_POST['interfaceLoginID']. "';")){

				echo "Login Notes have been updated";
			}else{
				echo "Error processing your request - please contact support!";
			}

    	}else{
			if (mysql_query("insert into interface_login (platformID, publisherPlatformID, loginID, password, url, notes)
				values ('" . $_POST['platformID'] . "', '" . $_POST['publisherPlatformID'] . "', '" . $_POST['loginID'] . "', '" . $_POST['password'] . "', '" . $loginURL . "', '" . $notes . "');")){

				echo "New Login Notes have been added";
			}else{
				echo "Error processing your request - please contact support!";
			}
		}


        break;



    case 'deleteLogin':
    	if (mysql_query("delete from interface_login where interfaceLoginID = " . $_GET['interfaceLoginID'])){
    		echo "Login Notes have been deleted";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;





    case 'deleteMonth':

    	if ($_GET['publisherPlatformID']){
			if (mysql_query("delete from title_stats_monthly where archiveInd = '" . $_GET['archiveInd'] . "' and publisherPlatformID = '" . $_GET['publisherPlatformID'] . "' and year = '"  . $_GET['year'] . "' and month = '" . $_GET['month'] . "';")){
				echo "Month for publisher has been deleted";
			}else{
				echo "Error processing your request - please contact support!";
			}
		}else{
			if (mysql_query("delete from title_stats_monthly where publisherPlatformID in (select publisherPlatformID from publisher_platform where platformID = '" . $_GET['platformID'] . "') and year = '"  . $_GET['year'] . "' and month = '" . $_GET['month'] . "' and archiveInd = '" . $_GET['archiveInd'] . "';")){
				echo "Month for entire platform has been deleted";
			}else{
				echo "Error processing your request - please contact support!";
			}

		}
        break;



    case 'updateOverride':
    	if (mysql_query("update title_stats_monthly set overrideUsageCount = '" . $_POST['overrideUsageCount'] . "' where titleStatsMonthlyID = '" . $_POST['titleStatsMonthlyID'] . "';")){
    		echo "Override Usage Count has been updated";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;


    case 'ignoreOutlier':
    	if (mysql_query("update title_stats_monthly set ignoreOutlierInd = '1' where titleStatsMonthlyID = '" . $_POST['titleStatsMonthlyID'] . "';")){
    		echo "Outlier flag has been removed";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;


    case 'removeOutlier':
    	if (mysql_query("update title_stats_monthly set outlierID = '0' where titleStatsMonthlyID = '" . $_GET['titleStatsMonthlyID'] . "';")){
    		echo "Outlier flag has been removed";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;

    case 'updateYTDOverride':
    	if (mysql_query("update title_stats_ytd set " . $_POST['overrideColumn'] . " = '" . $_POST['overrideCount'] . "' where titleStatsYTDID = '" . $_POST['titleStatsYTDID'] . "';")){
    		echo "Override Count has been updated";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;

    case 'addISSN':
    	$ISSN = trim(str_replace ('-','',$_POST['issn']));

		$query="insert into title_issn (titleID, ISSN, ISSNType) values ('" . $_POST['titleID'] . "', '" . $ISSN . "', '" . $_POST['issnType'] . "');";
    	if (mysql_query($query)){
    		echo "ISSN has been added" . $query;
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;

    case 'removeISSN':
    	if (mysql_query("delete from title_issn where titleISSNID = '" . $_GET['titleISSNID'] . "';")){
    		echo "ISSN has been removed";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;


    case 'updatePlatformDisplay':
    	$platformID = $_GET['updateID'];
    	$reportDisplayName = $_GET['reportDisplayName'];


    	if (mysql_query("update platform set reportDisplayName  = '" . $reportDisplayName . "' where platformID = '" . $platformID . "';")){
    		echo "Platform display name has been updated";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;



    case 'updatePlatformDropDown':
    	$platformID = $_GET['platformID'];
    	$dropDownInd = $_GET['dropDownInd'];


    	if (mysql_query("update platform set reportDropDownInd  = '" . $dropDownInd . "' where platformID = '" . $platformID . "';")){
    		echo "Default display list has been updated";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;



    case 'updatePublisherDisplay':
    	$publisherPlatformID = $_GET['updateID'];
    	$reportDisplayName = $_GET['reportDisplayName'];


    	if (mysql_query("update publisher_platform set reportDisplayName  = '" . $reportDisplayName . "' where publisherPlatformID = '" . $publisherPlatformID . "';")){
    		echo "Publisher display name has been updated";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;


    case 'updatePublisherDropDown':
    	$publisherPlatformID = $_GET['publisherPlatformID'];
    	$dropDownInd = $_GET['dropDownInd'];


    	if (mysql_query("update publisher_platform set reportDropDownInd  = '" . $dropDownInd . "' where publisherPlatformID = '" . $publisherPlatformID . "';")){
    		echo "Default display list has been updated";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;




	default:
       echo "Function " . $_REQUEST['function'] . " not set up!";
       break;


}



?>