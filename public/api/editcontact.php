<?php
/*
 * date :16/08/2017
 * API for edit contact CDR
 * 
 * EDIT Contact  * return 
 */
// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");


$inputJSON = file_get_contents('php://input');
$input= json_decode($inputJSON, TRUE );
// log write




$outputFile = "api_cdr_edit_contact.log";
file_put_contents($outputFile,print_r($input, true),FILE_APPEND);


// log write over 



$groupid=$input['groupid'];
$number=$input['number'];

if($groupid=='' || $number=='' )
{$arr['status']='Faild';
                $arr['message']='Fname is empty';
echo json_encode($arr);
exit;
}
	$s="SELECT id,fname,lname,email,phone FROM contacts where groupid='$groupid' and phone='$number'";
$re=mysqli_query($link,$s);   
if($re)
{$arr['status']='Success';
	$arr['message']='Data Updated';
	}else
	{$arr['status']='Failed';
		$arr['message']='No call record found :';

	}
	
	$rows=array();
  while($row=mysqli_fetch_assoc($re)) 
  {  $rows[] = $row; 
	  
 }


	// $data = array('cdr' => $rows);
	 
$arr['data']=$rows;
echo json_encode($arr);	
		





?>
