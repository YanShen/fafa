<?php
include('classes/php-captcha.inc.php');
include('classes/main.class.php');
include('classes/survey.class.php');

$survey = new UCCASS_Survey;

$body = $survey->take_survey($_REQUEST['sid']);

$header = $survey->com_user_header("{$survey->survey_name}");

echo $header;
echo $body;
echo $survey->user_footer();

?>
