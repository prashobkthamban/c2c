<?php
/*
 * date :21/08/2015
 * API for android login 
 * 
 * Admin status - Dashbord
 * return 
 * 
 * */

// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");
$date=date('Y-m-d');
$inputJSON = file_get_contents('php://input');
$input= json_decode( $inputJSON, TRUE );
// log write




$outputFile = "api_admin_status.log";
file_put_contents($outputFile,print_r($input, true),FILE_APPEND);


// log write over 
$groupid=$input['groupid'];

if($groupid=='')
{$arr['status']='Failed';
 $arr['message']='username is empty';
echo json_encode($arr);
exit;
}
// get billing details 0,1          ,2          ,3
//$b="SELECT main_balance,c2c_balance,billingmode,creditlimit FROM billing WHERE groupid='$groupid' ";
$b="SELECT name FROM accountgroup WHERE id='$groupid' ";
$bq=mysqli_query($link,$b);
$aname=mysqli_fetch_row($bq);
$groupname = $aname[0];

$b="SELECT count(*)  FROM operatoraccount WHERE groupid='$groupid' ";
$bq=mysqli_query($link,$b);
$aname=mysqli_fetch_row($bq);
$allopr = $aname[0];

$b="SELECT count(*)  FROM operatoraccount WHERE groupid='$groupid' and oper_status='online' ";
$bq=mysqli_query($link,$b);
$aname=mysqli_fetch_row($bq);
$activeallopr = $aname[0];

	// operator update CDR table 
	
$s="SELECT COUNT(cdrid) FROM cdr WHERE groupid='$groupid' and datetime like'$date %' ORDER BY datetime DESC ";
$s1="SELECT Count(id) FROM app_cdr WHERE app_cdr.groupid='$groupid' and date_time >='$date' ORDER BY date_time DESC ";
$re=mysqli_query($link,$s); 
$cdr=mysqli_fetch_row($re);
$re1=mysqli_query($link,$s1);
$appcdr=mysqli_fetch_row($re1); 
if($re)
{$arr['status']='Success';
	$arr['message']='datafound';
	$arr['incomingcalls']=$cdr[0];
	$arr['clientname']=$groupname;
	$arr['allopr']=$allopr;
	$arr['activeopr']=$activeallopr;
		}else
	{$arr['status']='Failed';
		$arr['message']='No call record found :';

	}
 
file_put_contents($outputFile,print_r($arr, true),FILE_APPEND);
print_r(json_encode($arr));	
		





?>
