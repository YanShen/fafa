<?php

include('classes/main.class.php');
include('classes/survey.class.php');

$survey = new UCCASS_Survey;

echo $survey->com_user_header();

echo $survey->latest_surveys2();

echo $survey->com_footer();

?>