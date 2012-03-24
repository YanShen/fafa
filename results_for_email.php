<?php

include('classes/main.class.php');
include('classes/special_results.class.php');

$survey = new UCCASS_Special_Results;

$output .= $survey->results_email_by_marked(@$_REQUEST['sid'], @$_REQUEST['marked']);

echo $output;

?>