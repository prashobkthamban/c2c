<?php
/*
 * date :21/08/2015
 * API for android login 
 * 
 * Add contact
 * return 
 * 
 * */
/*
 * EXPECT DATA 
 * {
"$email=$input['email'];
$fname=$input['fname'];
$lname=$input['lname'];
$groupid=$input['groupid'];
$number=$input['number'];
}
 * 
 * */

// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");

$inputJSON = file_get_contents('php://input');
$input= json_decode($inputJSON, TRUE );
// log write




$outputFile = "api_cdr_addcontact.log";
file_put_contents($outputFile,print_r($input, true),FILE_APPEND);


// log write over 



$email=$input['email'];
$fname=$input['fname'];
$lname=$input['lname'];
$groupid=$input['groupid'];
$number=$input['number'];

if($fname=='' || $number=='' )
{$arr['status']='Faild';
                $arr['message']='Fname is empty';
echo json_encode($arr);
exit;
}
	$s="INSERT INTO contacts (fname,lname,groupid,email,phone)Values('$fname','$lname','$groupid','$email','$number')";
if(mysqli_query($link,$s))
{$arr['status']='Success';
	$arr['message']='Data Updated';
	}else
	{$arr['status']='Failed';
		$arr['message']="Query Error" ;

	}
$arr['data']='';

echo json_encode($arr);	
		





?>
