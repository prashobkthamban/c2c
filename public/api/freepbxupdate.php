<?php
$action =$_REQUEST['action'];
$row = $_REQUEST;
$outputFile = "VoiceOUT_api.log";
file_put_contents($outputFile,print_r($row, true),FILE_APPEND);
require_once ("/var/www/html/curlpost.php");
echo "SUCCESS";
$pbx = new pbxcurl();
$r = $pbx->post($action,$row);
	
  ?>
