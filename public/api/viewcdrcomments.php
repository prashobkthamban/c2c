<?php
/*
 * date :21/08/2015
 * API for Get cdr Notes 
 * 
 * return 
}
 * 
 * */

// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");

$inputJSON = file_get_contents('php://input');
$input= json_decode($inputJSON, TRUE );
// log write




$outputFile = "api_viewcdrcomments.log";
file_put_contents($outputFile,print_r($input, true),FILE_APPEND);


// log write over 



$uniqueid=$input['uniqueid'];

if($uniqueid==''  )
{$arr['status']='Faild';
                $arr['message']='uniqueid is empty';
echo json_encode($arr);
exit;
}
	$s="SELECT * from cdr_notes where uniqueid='$uniqueid'";
$re=mysqli_query($link,$s);   
if($re)
{$arr['status']='Success';
	$arr['message']='Comments fund'.$s;
	}else
	{$arr['status']='Failed';
		$arr['message']="Query Error" ;

	}
$rows=array();
  while($row=mysqli_fetch_assoc($re)) 
  {  $rows[] = $row; 
	  
 }

$arr['data']=$rows;
echo json_encode($arr);	
		







?>
