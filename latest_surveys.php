<?php

include('classes/main.class.php');
include('classes/survey.class.php');

$survey = new UCCASS_Survey;

echo $survey->com_user_header();
//List latest surveys
?>
<div class="mainarea_container">
<?
echo $survey->latest_surveys2();

//List all surveys
echo $survey->available_surveys();
?>
</div>
<?
echo $survey->user_footer();

?>