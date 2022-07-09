<?php
// this is for web API
// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");

$format='json';
$ip=$_SERVER['REMOTE_ADDR'];
$apikey=$_REQUEST['apikey'];
$date=$_REQUEST['date'];


// get groupid from accountgroup table 

$s="SELECT id,ip FROM accountgroup where cdr_apikey='$apikey'";
$r=mysqli_query($link,$s);
$re=mysqli_fetch_row($r);
$groupid=$re[0];

$dbip=$re[1];
if ($groupid == NULL) {
	echo "INVALID KEY";
	exit;
}

if ($ip != $dbip){
//	echo "Access Denined from this IP :$ip";
//	exit;
}
	

if(!empty($date)) {
	$sql = "SELECT cdrid,status as Call_status FROM cdr WHERE  cdr.groupid='$groupid' and datetime like'$date%'"; 
	$query=mysqli_query($link,$sql);
	while($row=mysqli_fetch_assoc($query)) {
		$rows[] = $row; 
	}
	if(count($rows)==0) {
		echo"false";
		exit;
	} else {
		echo json_encode($rows); 
		exit;
	}
	
}
?>
