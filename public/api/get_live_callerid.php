<?php
/*
 * date :21/08/2015
 * API for android login 
 * 
 * groupadmin and operator login
 * return 
 * DID numbers
 * operator id 
 * groupid
 * */
/*
 * EXPECTED DATA
 * {
"operatorid":8,
"comments":" hihihih"
  }
 * */

// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");

$inputJSON = file_get_contents('php://input');
$input= json_decode( $inputJSON, TRUE );

$outputFile = "api_get_live_callerid.log";
file_put_contents($outputFile,print_r($input, true),FILE_APPEND);


$key=trim($input['key']);

	// operator
$s="SELECT id,groupid,livetrasferid from operatoraccount Where androidkey='$key' ";

$q=mysqli_query($link,$s);
$res=mysqli_fetch_row($q);
$operatorid=$res[0];
$groupid=$res[1];
if(!is_numeric($operatorid))
{$arr['status']='Faild';
                $arr['message']=$key.'=Invalid key';
echo json_encode($arr);
exit;
	}	

$s="SELECT callerid,language,(select fname from contacts where groupid='$groupid' and phone=callerid) as fname FROM cur_channel_used  where operatorid='$operatorid' ";

$re=mysqli_query($link,$s);
$r=mysqli_fetch_assoc($re);
if($r['callerid'] > 0)
{
	$arr['status']='Success';
	$arr['message']='datafound';
	
	
	}
	else
	{	
		$arr['status']='Failed'; // needed two lines
		$arr['message']='No call record found :';
		echo json_encode($arr);	
		

exit;
	}


$arr1['callerid']= $r['callerid'];
$arr1['language']= $r['language'];
$arr1['fname']= $r['fname'];

$arr['data']=$arr1;
echo json_encode($arr);	
		exit;



?>
