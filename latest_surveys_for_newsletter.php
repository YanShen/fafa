<?php

include('classes/main.class.php');
include('classes/survey.class.php');

$survey = new UCCASS_Survey;

echo $survey->com_newsletter_header();
//List latest surveys
?>

<!-- COPY & PASTE FROM THE LINE BELOW -->

<style>
<?php require "templates/Chinese/style.css";?>
</style>

<div class="page_title" style="background-color:white">
<?
echo $survey->latest_surveys2("latest_surveys_for_newsletter.tpl");
?>
</div>

<!-- COPY & PASTE FROM THE LINE ABOVE -->

<?
echo $survey->newsletter_footer();

?>