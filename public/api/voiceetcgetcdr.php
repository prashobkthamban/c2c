<?php
// this is for web API
// db connect
include ("/var/www/asterconnect_scripts/dbconnect.php");

$sep=$_REQUEST['delimiter'];
$format='json';
$ip=$_SERVER['REMOTE_ADDR'];
$apikey=$_REQUEST['apikey'];
$date=$_REQUEST['date'];
$date=$_REQUEST['cdrid'];


// get groupid from accountgroup table 

$s="SELECT id,ip FROM accountgroup where cdr_apikey='$apikey'";
//echo $s;
$r=mysqli_query($link,$s);
$re=mysqli_fetch_row($r);
$groupid=$re[0];

$dbip=$re[1];
if($groupid==NULL)
{echo "INVALID KEY";
	exit;}
	
//if the client want  one specific call  details

if(!empty($cdrid))
{
  $sql = "SELECT cdrid,datetime,deptname As Department_Name,number as Caller,opername as Operater,status as Call_status,firstleg as Total_sec,secondleg as agent_talk_sec,recordedfilename,did_no FROM cdr LEFT JOIN operatoraccount ON operatoraccount.id=cdr.operatorid WHERE  cdr.groupid='$groupid' and cdrid='$cdrid'";
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

}

$da=explode("-",$date);
$year=$da[0];
$month=$da[1];
$day=$da[2];
if(!empty($date))
{
  $sql = "SELECT cdrid,datetime,deptname As Department_Name,number as Caller,opername as Operater,status as Call_status,firstleg as Total_sec,secondleg as agent_talk_sec,recordedfilename,did_no FROM cdr LEFT JOIN operatoraccount ON operatoraccount.id=cdr.operatorid WHERE  cdr.groupid='$groupid' and datetime like'$date%'"; 
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

 $sql = "SELECT cdrid,datetime,deptname As Department_Name,number as Caller,opername as Operater,status as Call_status,firstleg as Total_sec,secondleg as agent_talk_sec,recordedfilename,did_no FROM cdr LEFT JOIN operatoraccount ON operatoraccount.id=cdr.operatorid WHERE ast_update='0' and cdr.groupid='$groupid' Order by datetime ASC LIMIT 1"; 
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
mysqli_query($link,$sqlupdate);
exit;

}



?>
