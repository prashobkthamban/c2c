<?php
/*
 * date :21/08/2015
 * API for UPDATE contact 
 * 
 * 
 * */

// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");

$inputJSON = file_get_contents('php://input');
$input= json_decode($inputJSON, TRUE );
// log write




$outputFile = "api_save_contact.log";
file_put_contents($outputFile,print_r($input, true),FILE_APPEND);

$email=$input['email'];
$fname=$input['fname'];
$lname=$input['lname'];
$id=$input['id'];

if($fname=='' || $id=='' )
{$arr['status']='Faild';
                $arr['message']='Fname/ is empty';
echo json_encode($arr);
exit;
}
	$s="UPDATE contacts SET fname='$fname',lname='$lname',email='$email' Where id='$id'";
if(mysqli_query($link,$s))
{$arr['status']='Success';
	$arr['message']='Data Updated';
	}else
	{$arr['status']='Failed';
		$arr['message']='No call record found :';

	}
$arr['data']='';

echo json_encode($arr);	
		





?>
