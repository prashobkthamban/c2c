<?php
/*
29/06/2017
auther philip -> jissphilip@gmail.com
copy right voiceetc
App get all operators 
*/
// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");

$inputJSON = file_get_contents('php://input');
$input= json_decode($inputJSON, TRUE );
// log write
$outputFile = "app_list_callstatus.log";
file_put_contents($outputFile,print_r($input, true),FILE_APPEND);
$groupid=$input['groupid'];

$sql="SELECT DISTINCT(status) FROM cdr where groupid='$groupid'";
$query=mysqli_query($link,$sql);
while($row=mysqli_fetch_assoc($query)){
	$rows[] = $row; 
}
if(count($rows)==0){
 	$ar[0] = array("status"=>"MISSED");
	$ar[1] = array("status"=>"ANSWERED");
	$ar[2] = array("status"=>"CANCEL");
 echo json_encode($ar);
	file_put_contents($outputFile," No Status found",FILE_APPEND);
	exit;
}else{
	echo json_encode($rows);
	file_put_contents($outputFile,print_r($rows, true),FILE_APPEND); 
	exit;
}
?>
