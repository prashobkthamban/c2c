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


// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");
$inputJSON = file_get_contents('php://input');
$input= json_decode( $inputJSON, TRUE );
// log write



$outputFile = "api_userlogin.log";

file_put_contents($outputFile,print_r($input, true),FILE_APPEND);


// log write over 
$username=trim($input['username']);
$password=trim($input['password']);
$isAdmin=trim($input['isAdmin']);
$deviceid=trim($input['deviceid']);
if($username=='')
{$arr['status']='Faild';
                $arr['message']='username is empty';
echo json_encode($arr);
exit;
}
$d=date ("d");
$m=date ("m");
$y=date ("Y");
$t=time();
$dmt=$d+$m+$y+$t;
$ran= rand(0,10000000);
$dmtran= $dmt+$ran;
$un=  uniqid();
$dmtun = $dmt.$un;
$mdun = md5($dmtran.$un);
$sort=substr($mdun, 16); // if you want sort length code.
$apikey=$sort;

// group admin
//echo "HIHI";
if($isAdmin==1)
{$s="SELECT account.id,account.username,account.groupid,dids.outgoing_callerid FROM account LEFT JOIN accountgroup ON accountgroup.id=account.groupid LEFT JOIN dids ON account.groupid=dids.assignedto where username='$username'  AND user_pwd='$password' AND  accountgroup.andriodapp='Yes'";
//echo $s;
$re=mysqli_query($link,$s);
$r=mysqli_fetch_assoc($re);
if($r['id']>0)
{$arr['status']='Success';
	$arr['message']='Login success';
	}else
	{$arr['status']='Failed';
		$arr['message']='Login Failed. user name or password is wrong for admin account';

	}
$arr1['id']=$r['id'];
$arr1['username']=$r['username'];
$arr1['groupid']=$r['groupid'];
$arr1['did']=$r['outgoing_callerid'];
$arr1['key']=$apikey;
$arr['data']=$arr1;
//$jsonarr=json_encode($arr1);

//$arr['data']=$jsonarr;

echo json_encode($arr);
$return = implode(" ",$arr);
$fp = fopen($outputFile, 'a');
fwrite($fp, $retun);
fclose($fp);

$sql ="UPDATE account SET deviceid='$deviceid' WHERE id='".$r['id']."' ";
mysqli_query($link,$sql);

	}
	// operator
if($isAdmin==0)
{ 
	$s="SELECT operatoraccount.id as operatorid,operatoraccount.opername,groupid,outgoing_callerid,livetrasferid FROM operatoraccount LEFT JOIN accountgroup ON accountgroup.id=operatoraccount.groupid LEFT JOIN dids ON operatoraccount.groupid=dids.assignedto where login_username='$username'  AND  password='$password' and accountgroup.andriodapp='Yes' and app_use='Yes'";
//echo $s;
$re=mysqli_query($link,$s);
$r=mysqli_fetch_assoc($re);
if($r['operatorid']>0)
{$arr['status']='Success';
	$arr['message']='Login success';
	}else
	{$arr['status']='Failed';
		$arr['message']='Login Failed user name or password is wrong operator account :';

	}
$arr1['id']=$r['operatorid'];
$arr1['username']=$r['opername'];
$arr1['groupid']=$r['groupid'];
$arr1['did']=$r['outgoing_callerid'];
$arr1['livetrasferid']=$r['livetrasferid'];

$arr1['key']=$apikey;	
$arr['data']=$arr1;
//$jsonarr=json_encode($arr1);

//$arr['data']=$jsonarr;
$fp = fopen($outputFile, 'a');
fwrite($fp, json_encode($arr));
echo json_encode($arr);	
$u="update operatoraccount SET androidkey='$apikey',deviceid='$deviceid' WHERE id='".$r['operatorid']."'";
mysqli_query($link,$u);
	}	





?>
