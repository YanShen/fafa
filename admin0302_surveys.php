<?php

include('classes/main.class.php');
include('classes/survey.class.php');

$survey = new UCCASS_Survey;

echo $survey->com_header();

$activeType = $_REQUEST["activeType"];
if(!isset($activeType)) $activeType = 1;

echo $survey->active_surveys('admin_surveys.tpl', $activeType);

echo $survey->com_footer();

?>