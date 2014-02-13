<?php

function numberToMonth($number){

	//correct to start with 0
	$number = $number -1;

	$months=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

	return $months[$number];

}


?>