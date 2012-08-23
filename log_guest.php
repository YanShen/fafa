<?php
include('classes/main.class.php');

$client_ip = $_REQUEST["ca"];
$access_page = $_REQUEST["ap"];

$msg = "User-Agent".$_SERVER['HTTP_USER_AGENT'].", Client IP:".$client_ip." is accessing: ".$access_page." Unauthorized attempting to post on yahoo groups.";

error_log('['.date("F j, Y, g:i a e O").']'.$msg." \n", 3,  "C:\\Program Files\\Apache Group\\Apache2\\logs\\unauthorized_access.log");

?>