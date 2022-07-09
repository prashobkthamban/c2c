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




$outputFile = "api_cdrc2c_view.log";
file_put_contents($outputFile,print_r($input, true),FILE_APPEND);



// log write over 
$operatorid=$input['operatorid'];
$groupid=$input['groupid'];
$sdate=$input['sdate'];
$edate=$input['edate'];
$assgineid=$input['assigneeid'];
$depart = $input['department'];
$status = $input['status'];
if($sdate == NULL && $edate ==NULL){
	$dateq = 'and datetime > DATE_SUB(datetime,INTERVAL 90 DAY)';
}
if($sdate != NULL){
        $dateq .= "and datetime > '$sdate 00:00:00'";
}
if($edate !=NULL){
        $dateq .= "and datetime < '$edate 23:59:59'";
}
$dept='';
if($depart != NULL){
        $dept = " and deptname = '$depart'";
}
if($status != NULL){
        $dept .= " and `status` = '$status'";
}


if($assgineid !=NULL){
	$assq = " and assignedto='$assgineid'";
}

if($operatorid=='' && $groupid==NULL)
{$arr['status']='Failed';
                $arr['message']='username is empty';
echo json_encode($arr);
exit;
}
	// operator update CDR table 
	if($operatorid != '')// assuming for operator not sending the groupid
{$s="SELECT cdrid as id,datetime,did_no as didno,(SELECT fname from contacts where phone=cdrc2c.number and groupid=cdrc2c.groupid) as name,number,(select count(*) from cdr_notes where uniqueid=cdrc2c.uniqueid) as notescount,uniqueid,status,deptname,secondleg as duration,recordedfilename,B.opername as assignee FROM cdrc2c LEFT JOIN operatoraccount as B ON B.id=cdrc2c.assignedto WHERE operatorid='$operatorid' $dateq $assq $dept  ORDER BY datetime DESC ";
}else{
$s="SELECT cdrid as id,datetime,did_no as didno,(select fname from contacts where phone=cdrc2c.number and groupid=cdrc2c.groupid) as name,(select count(*) from cdr_notes where uniqueid=cdrc2c.uniqueid) as notescount,number,uniqueid,status,deptname,A.opername,secondleg as duration,recordedfilename,B.opername as assignee FROM cdrc2c LEFT JOIN operatoraccount as A ON A.id=cdrc2c.operatorid LEFT JOIN operatoraccount as B ON B.id=cdrc2c.operatorid  WHERE cdrc2c.groupid='$groupid' $dateq $assq $dept  ORDER BY datetime DESC ";
}

//$fp = fopen($outputFile, 'a');
//fwrite($fp, $s);
//fclose($fp);


$re=mysqli_query($link,$s);   
if($re)
{$arr['status']='Success';
	$arr['message']='';
	$arr['filepath']=$locationserver.'/voicefilesc2c/';
	//$arr['filepath'] = 'https://ivrmanager.in/voicefiles/';
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

file_put_contents($outputFile,print_r(json_encode($arr), true),FILE_APPEND);
print_r(json_encode($arr));	
		

?>
