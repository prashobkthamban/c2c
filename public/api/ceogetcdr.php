<?php

// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");


//$format='json';
$ip=$_SERVER['REMOTE_ADDR'];
$apikey=$_REQUEST['apikey'];
$date=$_REQUEST['date'];


// get groupid from accountgroup table 

$s="SELECT id FROM resellergroup where cdr_apikey='$apikey'";
$r=mysqli_query($link,$s);
$re=mysqli_fetch_row($r);
$resellerid=$re[0];

//$dbip=$re[1];
if($resellerid==NULL)
{echo "INVALID KEY";
	exit;}
/*if($ip!=$dbip)
{echo "Access Denined from this IP :$ip";
	exit;
	}
*/	
$da=explode("-",$date);
$year=$da[0];
$month=$da[1];
$day=$da[2];

if(checkdate($month,$day,$year))
{
 $sql = "SELECT cdrid,cdr.groupid as ID,name,datetime,deptname As Department_Name,number as Caller,opername as Operater,cdr.status as Call_status,firstleg as Total_sec,secondleg as agent_talk_sec,recordedfilename,comments FROM cdr LEFT JOIN operatoraccount ON operatoraccount.id=cdr.operatorid LEFT JOIN accountgroup ON accountgroup.id=cdr.groupid WHERE ast_update='0' and cdr.resellerid='$resellerid' and datetime like'$date%'"; 
$query=mysqli_query($link,$sql);
while($row=mysqli_fetch_assoc($query))
{
	$rows[] = $row; 
	
	}
	if(count($rows)==0)
	{echo"false";
		exit;
	}else{
	echo json_encode($rows); 
	exit;}
	
}else
{

 $sql = "SELECT cdrid,cdr.groupid as ID,name,datetime,deptname As Department_Name,number as Caller,opername as Operater,cdr.status as Call_status,firstleg as Total_sec,secondleg as agent_talk_sec,recordedfilename,comments FROM cdr LEFT JOIN operatoraccount ON operatoraccount.id=cdr.operatorid LEFT JOIN accountgroup ON accountgroup.id=cdr.groupid WHERE ast_update='0' and cdr.resellerid='$resellerid' Order by datetime ASC LIMIT 1"; 
$query=mysqli_query($link,$sql);
$res=mysqli_fetch_assoc($query);
if($format=='json')
{if(count($res)==0)
	{echo"false";
		exit;
		}else{
echo json_encode($res);
}
}

$sqlupdate="UPDATE cdr set ast_update='1'  Where cdrid='$res[cdrid]'";
//echo $sqlupdate;
//mysqli_query($link,$sqlupdate);
exit;

}



?>
