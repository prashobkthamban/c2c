<?php
/*
 * date :21/08/2015
 * API for Reminder view 
 * 
 * */
// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");

$inputJSON = file_get_contents('php://input');
$input= json_decode( $inputJSON, TRUE );
// log write
$outputFile = "api_PUSH_reminder.log";
file_put_contents($outputFile,print_r($input, true),FILE_APPEND);


// log write over 
$operatorid=$input['operatorid'];
$groupid=$input['groupid'];
$id=$input['id'];
$limit=$input['limit'];
$orderby=$input['orderby'];
if($groupid == NULL && $id == NULL)
{$arr['status']='Failed';
                $arr['message']='User details are empty';
echo json_encode($arr);
exit;
}
	// operator update CDR table 
	if($operatorid!=NULL)
$s="SELECT reminders.id as id,followupdate,(select fname from contacts where phone=reminders.number and groupid='$groupid') as name,number,uniqueid,deptname,appoint_status,recordedfilename FROM reminders WHERE id='$id' ";
else
$s="SELECT reminders.id as id,followupdate,(select fname from contacts where phone=reminders.number and groupid='$groupid') as name,number,uniqueid,deptname,appoint_status,recordedfilename FROM reminders LEFT JOIN operatoraccount ON operatoraccount.id=reminders.operatorid   WHERE reminders.id='$id' ";


$re=mysqli_query($link,$s);   
if($re)
{$arr['status']='Success';
	$arr['message']='';
	$arr['filepath']=$locationserver.'/voicefiles/';
	}else
	{$arr['status']='Failed';
		$arr['message']=$s.'No call record found :';

	}
 $rows=array();
  while($row=mysqli_fetch_assoc($re)) 
  {  $rows[] = $row; 
	  
 }


	// $data = array('cdr' => $rows);
	 
$arr['data']=$rows;

print_r(json_encode($arr));	
		





?>
