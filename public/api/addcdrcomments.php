<?php
/*
 * date :16/08/2017
 * API for CDR comments Add
 * 
 * */

// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");

$inputJSON = file_get_contents('php://input');
$input= json_decode($inputJSON, TRUE );
// log write




$outputFile = "api_cdr_notes_add.log";
file_put_contents($outputFile,print_r($input, true),FILE_APPEND);


// log write over 



$uniqueid=$input['uniqueid'];
$comments=$input['comments'];
$opername=$input['opername'];

if($uniqueid=='' || $comments=='' )
{$arr['status']='Faild';
                $arr['message']='Comments/(id) is empty';
echo json_encode($arr);
exit;
}
	$s="INSERT INTO cdr_notes (note,uniqueid,operator,datetime)Values('$comments','$uniqueid','$opername',NOW())";
if(mysqli_query($link,$s))
{$arr['status']='Success';
	$arr['message']='Comments Added';
	}else
	{$arr['status']='Failed';
		$arr['message']="Query Error" ;

	}
$arr['data']='';

echo json_encode($arr);	
		





?>
