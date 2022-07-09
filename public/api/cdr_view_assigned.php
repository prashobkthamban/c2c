<?php
/*
 * date :21/08/2015
 * API for android CDR view 
 * */

// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");

$inputJSON = file_get_contents('php://input');
$input= json_decode( $inputJSON, TRUE );
// log write




$outputFile = "api_cdr_view_assign.log";
file_put_contents($outputFile,print_r($input, true),FILE_APPEND);



// log write over 
$operatorid=$input['operatorid'];
$groupid=$input['groupid'];
$sdate=$input['sdate'];
$edate=$input['edate'];
$assgineid=$input['assigneeid'];

if($sdate == NULL && $edate ==NULL){
	$dateq = 'and datetime > DATE_SUB(datetime,INTERVAL 90 DAY)';
}
if($sdate != NULL){
        $dateq .= "and datetime > '$sdate 00:00:00'";
}
if($edate !=NULL){
        $dateq .= "and datetime < '$edate 23:59:59'";
}

if($assgineid !=NULL){
	$assq = "and assignedto='$assgineid'";
}

if($operatorid=='' && $groupid==NULL)
{$arr['status']='Failed';
                $arr['message']='username is empty';
echo json_encode($arr);
exit;
}
	// operator update CDR table 
	if($operatorid != '')// assuming for operator not sending the groupid
{$s="SELECT cdrid as id,datetime,did_no as didno,(SELECT fname from contacts where phone=cdr.number and groupid=cdr.groupid) as name,number,(select count(*) from cdr_notes where uniqueid=cdr.uniqueid) as notescount,uniqueid,deptname,secondleg as duration,recordedfilename,B.opername as assignee FROM cdr LEFT JOIN operatoraccount as B ON B.id=cdr.assignedto WHERE assignedto='$operatorid'  $dateq $assq  ORDER BY datetime DESC  LIMIT 500";
}else{
$s="SELECT cdrid as id,datetime,did_no as didno,(select fname from contacts where phone=cdr.number and groupid=cdr.groupid) as name,(select count(*) from cdr_notes where uniqueid=cdr.uniqueid) as notescount,number,uniqueid,status,deptname,A.opername,secondleg as duration,recordedfilename,B.opername as assignee FROM cdr LEFT JOIN operatoraccount as A ON A.id=cdr.operatorid LEFT JOIN operatoraccount as B ON B.id=cdr.operatorid  WHERE cdr.groupid='$groupid' $dateq $assq  ORDER BY datetime DESC  LIMIT 500";
}
file_put_contents($outputFile,$s,FILE_APPEND);
//$fp = fopen($outputFile, 'a');
//fwrite($fp, $s);
//fclose($fp);


$re=mysqli_query($link,$s);   
if($re)
{$arr['status']='Success';
	$arr['message']='';
	$arr['filepath']=$locationserver.'/voicefiles/';
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

print_r(json_encode($arr));	
		

?>
