<?php
// webc2c.php?apikey=7671d511e621d2584e25c1306d5f1c9a&source=&number=

include('/var/www/asterconnect_scripts/dbconnect.php');
$apikey = $_REQUEST['apikey'];
$agent = $_REQUEST['agent'];
$number = $_REQUEST['number'];
if(!is_numeric($number) || strlen($number) < 7){
	echo "NOT_A_VALID_NUMBER";
	exit;
}
if(!is_numeric($agent) || strlen($agent) < 7){
        echo "NOT_A_VALID_AGENT";
        exit;
}

// get the groupid using the apikey and API status check the condition 
$getgid = "SELECT id,c2c,resellerid from accountgroup where apikey='$apikey'"; 
$resq = mysqli_query($link,$getgid);
$gid = mysqli_fetch_row($resq);
$didnumber = $gid[3];
if($gid[0]==''){ 
	echo "APIKEY_NOT_FOUND";
	exit;
}
if($gid[1]!='Yes'){ 
	echo "CLICK2CALL_NOT_ALLOWED";
	exit;
}
if($gid[0]!=NULL){
	$groupid=$gid[0];
	$resellerid=$gid[2];
	$date=date("Y-m-d");
	// get biller details 
	$getgid="SELECT c2cpri,c2ccallerid,rdins,dial_prefix from dids where assignedto='$groupid' ";
	//echo $getgid;
	$resq = mysqli_query($link,$getgid);
	$gid=mysqli_fetch_row($resq);
	$gatewayid=$gid[0];
	$set_did_no=$gid[1];
	$rdin=$gid[2];
	$dialprefix = $gid[3];
	$qa="Select Gchannel from prigateway where id='".$gatewayid."'";
 	$qaa=mysqli_query($link,$qa);
 	$did=mysqli_fetch_row($qaa);
	$span = $did[0];
	if($span==NULL){
		echo "SERVICE_ERROR";
		exit;
	}
	$getgid="SELECT main_balance,billingmode,creditlimit from billing where groupid='$groupid' ";
	$resq=mysqli_query($link,$getgid);
	$bill=mysqli_fetch_row($resq);
	if($bill[1]=='prepaid'){
		if($bill[0] >=3 ){
			
		}else{
//			echo "NOT_ENOUGH_CREDIT";
//			exit;
		}
	}else {//postpaid
		if($bill[0]> $bill[2]){
//			echo "NOT_ENOUGH_CREDIT";
//			exit;
		}
	}
	$getdpt="SELECT id,opt_calltype,apicall,dept_name FROM operatordepartment WHERE groupid='$groupid' and C2C='1'";
	$resd=mysqli_query($link,$getdpt);
	$dpt = mysqli_fetch_row($resd);
	$calltype = $dpt[1];
	$depid = $dpt[0];
	$api = $dpt[2];
	$dept_name = $dpt[3];
	if($dpt[0] > 0) {
		if(strlen($api) >15){ // call API with number
			$api=$api.$number; 
			$ch = curl_init($api);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT_MS, 200);
			$curl_scraped_page = curl_exec($ch);
			curl_close($ch);
		}
			$QUERY = "SELECT operatoraccount.id,phonenumber FROM operatoraccount WHERE groupid='".$groupid."' AND phonenumber='$agent'";
			$resd=mysqli_query($link,$QUERY);//$agent= '8594049580';
			$result=mysqli_fetch_row($resd);
			if (is_array($result) && ($result[0] > 0) ) {
				$operatorid=$result[0] ;
				$num1=$result[1];
				$opername=$result[2];
			}else {
				echo "OPERATOR_NUM_NOT_VALID";
        	       	exit;
			}		
	} else{
		echo "NO_DEPARTMENT_OR_NON_OFFICE_HOURS";
		exit;
	}
	// change the entries 
	$cdrinsert="INSERT INTO cdrc2c (`groupid`, `resellerid`, `operatorid`, `datetime`, `deptname`, `status`, `number`,comments,did_no) VALUES ('".$groupid."', '".$resellerid."', '".$operatorid."', CURRENT_TIMESTAMP,  '$dept_name', 'DIALING', '".$number."','".$agent."','$rdin'); ";		
	mysqli_query($link,$cdrinsert);
	$cdrid=mysqli_insert_id($link);
	if($dialstring==NULL)	{
		$dialstring=$destination;
	}
	$rec =date("Ymd_His_").$number;
	$phone1= $dialprefix.$agent."-".$cdrid."-".$set_did_no;
	$phone2= $dialprefix.$number."-".$cdrid."-".$set_did_no;
	$qa="Select * from asterisk_manager where id='1'";
 	$qaa=mysqli_query($link,$qa); 
	$manager=mysqli_fetch_assoc($qaa);
	$strHost = $manager['ip'];
	$strUser = $manager['username'];
	#specify the password for the above user
	$strSecret = $manager['password'];
	$errno = "";
    $errstr = "";
    $timeout = "30";
	$socket = fsockopen("$strHost","5038", $errno, $errstr, $timeout);
    fputs($socket, "Action: Login\r\n");
    fputs($socket, "UserName: $strUser\r\n");
    fputs($socket, "Secret: $strSecret\r\n\r\n");
	fputs($socket, "Variable: span=$span\r\n");
	fputs($socket, "Variable: num1=$dialprefix$agent\r\n");
	fputs($socket, "Variable: Rec=$rec\r\n");
	fputs($socket, "Action: Originate\r\n");
	fputs($socket, "Variable: span=$span\r\n");
	fputs($socket, "Channel: local/".$phone1."@ast_appwebleg1c2c\r\n");
	fputs($socket, "Context: ast_appwebleg2c2c\r\n");
	fputs($socket, "Variable: cdrid=$cdrid\r\n");
	fputs($socket, "Exten: ".$phone2."\r\n");
	fputs($socket, "Callerid: $set_did_no\r\n");
	fputs($socket, "Priority: 1\r\n");
	fputs($socket, "Timeout: 30000\r\n\r\n");
	fputs($socket, "Action: Logoff\r\n\r\n");
   while (!feof($socket)) {
		$wrets .= fread($socket, 4096);
    }
    fclose($socket);
	echo 'SUCCESS';
}
?>
