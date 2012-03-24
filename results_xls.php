<?php

include('classes/main.class.php');
include('classes/special_results.class.php');

$survey = new UCCASS_Special_Results;

$output = $survey->com_header("Survey Results");

$output .= $survey->results_xls_by_marked(@$_REQUEST['sid'], @$_REQUEST['marked']);

header("Content-disposition: filename=".$_REQUEST['marked'].".xls");
header("Content-type: application/octetstream"); 
header("Pragma: no-cache");
header("Expires: 0");
 
echo $output;

?>