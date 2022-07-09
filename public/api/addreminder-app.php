<?php
/*
 * date :21/08/2015
 * API for android REminder Add  
 * 
 * Add contact
 * return 
 */ 
// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");

$inputJSON = file_get_contents('php://input');
$input= json_decode($inputJSON, TRUE );
// log write




$outputFile = "addreminder.log";
file_put_contents($outputFile,print_r($input, true),FILE_APPEND);


// log write over 



$reoperatorid=$input['operatorid'];
$followupdate=$input['followupdate'];
$follower=$input['follower'];// name of the user
$cdrid=$input['cdrid'];

if($cdrid=='' || $follower=='' )
{$arr['status']='Faild';
                $arr['message']=print_r($input,true).'CDRID or Follower is empty';
echo json_encode($arr);
exit;
}
// get nessary details from cdr tables

$cdrq="select * from cdr where cdrid='".$cdrid."'";
$r=mysqli_query($link,$cdrq);
	$row=mysqli_fetch_assoc($r);
	extract($row);
	
	$q="INSERT INTO  reminders(`number`,`groupid` ,`operatorid` ,`followupdate` ,`appoint_status` ,`follower` ,`recordedfilename` ,`calldate` ,`deptname` ,`uniqueid`,`resellerid`,`secondleg`,`assignedto`)VALUES ( '$number',  '$groupid', '$reoperatorid','$followupdate','Live','$follower',  '$recordedfilename',  '$datetime',  '$deptname',  '$uniqueid','$resellerid','$secondleg','$assignedto');";
if(mysqli_query($link,$q))
{$arr['status']='Success';
	$arr['message']='Data Updated';
	}else
	{$arr['status']='Failed';
		$arr['message']="Query Error" ;

	}
$arr['data']='';

echo json_encode($arr);	
		





?>
