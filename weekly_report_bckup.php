<?php
include('/var/www/asterconnect_scripts/dbconnect.php');

// get groupid from accountgroup table 



$time=date("H:i:");
echo $time;

$date=date("Y-m-d");
$today = date("N");
if($today==1)
{$day='Monday';
}
if($today==2)
{$day='Tuesday';
}
if($today==3)
{$day='Wednesday';
}
if($today==4)
{$day='Thursday';
}
if($today==5)
{$day='Friday';
}
if($today==6)
{$day='Saturday';
}
if($today==7)
{$day='Sunday';
}
$s="SELECT * FROM ast_weekly where day='$day' and time like'$time%'";
echo $s;
$re=$mysqli->query($s);
while($row=$re->fetch_row())
{//  getting groupids
	$emailto=$re[4];
	$groupid=$re[1];
	$url=$re[5]; 
	$sub=$re[6];
	$content=$re[7];
	
$file = fopen("/var/www/asterconnect_scripts/WEEKLY/".$date."-".$groupid."_CDR-REPORT.csv","w"); 
$header='datetime,Department_Name,Caller,Operater,Call_status,Total_sec, agent_talk_sec,recordedfilename,comments';
fputcsv($file,explode(',',$header));
$sql = "SELECT datetime,deptname As Department_Name,number as Caller,opername as Operater,status as Call_status,firstleg as Total_sec,secondleg as agent_talk_sec,recordedfilename,comments FROM cdr LEFT JOIN operatoraccount ON operatoraccount.id=cdr.operatorid WHERE  cdr.groupid='$groupid' and datetime > DATE_SUB(NOW(), INTERVAL 7 DAY) AND datetime < NOW()"; 
echo $sql;
$res=$mysqli->query($sql);
while($row=$res->fetch_row())
{// cdrs
	
fputcsv($file,$res);
  
}
fclose($file);

// mail sending functionality here 

$filename = "/var/www/asterconnect_scripts/WEEKLY/".$date."-".$groupid."_CDR-REPORT.csv";
include_once "/var/www/html/Mail.php"; // PEAR Mail package
include_once ('/var/www/html/Mail/mime.php'); // PEAR Mail_Mime packge
$from = "Reports <admin@ivrmanager.in>";
$to = "$emailto";
$subject = "$sub";
$headers = array ('From' => $from,'To' => $to, 'Subject' => $subject);
$text = "$content"; // text and html versions of email.
$html = "$content";

$crlf = "\n";
$mime = new Mail_mime($crlf);
$mime->setTXTBody($text);
$mime->setHTMLBody($html);
$mime->addAttachment($filename, 'text/csv');
$body = $mime->get();
$headers = $mime->headers($headers);
$host = "us2.smtp.mailhostbox.com";
$username = "admin@ivrmanager.in";
$password = "WdKvFaR7";
$smtp = Mail::factory('smtp', array ('host' => $host, 'auth' => true, 'username' =>$username,'password' => $password));
$mail = $smtp->send($to, $headers, $body);
if (PEAR::isError($mail))
{
echo("<p>" . $mail->getMessage() . "</p>");
}
else
{
echo("Message successfully sent!");
}


}
?>
