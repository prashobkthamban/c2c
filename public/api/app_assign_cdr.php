<?php
/*
 * date :29/05/2017
 * API for android login 
 APP assign cdr to operators 
 inputs needed (operatorid and cdrid)
 * 
 * 
 * */

// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");

$inputJSON = file_get_contents('php://input');
$input= json_decode($inputJSON, TRUE );
// log write




$outputFile = "app_assign_cdr.log";
file_put_contents($outputFile,print_r($input, true),FILE_APPEND);


// log write over 



$operatorid=$input['operatorid'];
$cdrid=$input['cdrid'];

if($cdrid=='' || $operatorid=='' )
{$arr['status']='Faild';
                $arr['message']=print_r($input,true).'operatorid or cdrid is empty';
echo json_encode($arr);
exit;
}
// update cdr tables

$cdrq="UPDATE  cdr SET assignedto='$operatorid' where cdrid='".$cdrid."'";

if(mysqli_query($link,$cdrq))
{$arr['status']='Success';
	$arr['message']='Data Updated';

// if operator doesnot have deviceid not need to add
$sqlop="SELECT deviceid from operatoraccount WHERE id='$operatorid'";
$rr=mysqli_query($link,$sqlop);
$row = mysqli_fetch_row($rr);
if($row[0] != NULL)
{
// let form the message for app
$sqlcdr ="SELECT number,datetime,deptname,status FROM cdr WHERE cdrid='$cdrid'";
$rcdr=mysqli_query($link,$sqlcdr);
$cdrrow = mysqli_fetch_row($rcdr);
$message="There is new call assigned to you from  $cdrrow[0] called at $cdrrow[1] to $cdrrow[2] call status:$cdrrow[3]";

$qq="INSERT INTO assigncdr_app_notify(message,operatorid,status,deviceid,cdrid)VALUES('$message','$operatorid','0','$row[0]','$cdrid')";
mysqli_query($link,$qq);
}






	}else
	{$arr['status']='Failed';
		$arr['message']="Query Error" ;

	}
$arr['data']='';

echo json_encode($arr);	
		





?>
