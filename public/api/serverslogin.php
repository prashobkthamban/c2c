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
/*
$username=$_REQUEST['username'];
$password=$_REQUEST['password'];
$isAdmin=$_REQUEST['isAdmin'];
*/
$inputJSON = file_get_contents('php://input');
$input= json_decode( $inputJSON, TRUE );
// log write



$outputFile = "api_userlogin.log";


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


	$s="SELECT ip,location FROM servers WHERE 1 ORDER BY id DESC ";


$re=mysqli_query($link,$s);   
if($re)
{$arr['status']='Success';
	$arr['message']='';
	//$arr['filepath']='ivrmanager.in/asterconnect/voicefiles/';
	}else
	{$arr['status']='Failed';
		$arr['message']='No servers found :';

	}
 $rows=array();
  while($row=mysqli_fetch_assoc($re)) 
  {  $rows[] = $row; 
	  
 }


	// $data = array('cdr' => $rows);
	 
$arr['data']=$rows;

print_r(json_encode($arr));	
		





?>







