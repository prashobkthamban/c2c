<?php
// webc2c.php?apikey=7671d511e621d2584e25c1306d5f1c9a&source=&number=

include('/var/www/asterconnect_scripts/dbconnect.php');
$inputJSON = file_get_contents('php://input');
$input= json_decode( $inputJSON, TRUE );

$groupid = $input['groupid'];
$operatorid = $input['operatorid'];
$ph_number = $input['number'];
$number = preg_replace("/[^0-9]/", "", $ph_number);

$outputFile = "app_api_c2c.log";
file_put_contents($outputFile,print_r($input,true),FILE_APPEND);
file_put_contents($outputFile,print_r( "number1 = $number   philip",true),FILE_APPEND);


if(!is_numeric($number) || strlen($number) < 10){
	$arr['status']='Failed';
	$arr['message']='NOT_A_VALID_NUMBER:'.$number;
	file_put_contents($outputFile,print_r($arr, true),FILE_APPEND);
	echo json_encode($arr);
	exit;
}

if(strlen($number) == 10){
	$number = '0'.$number;
}
// get the groupid using the apikey and API status check the condition 
$getgid = "SELECT id,c2c,resellerid from accountgroup where id='$groupid'"; 
$resq = mysqli_query($link,$getgid);
$gid = mysqli_fetch_row($resq);
$didnumber = $gid[3];
if($gid[0]==''){ 
	$arr['status']='Failed';
	$arr['message']='APIKEY_NOT_FOUND';
	file_put_contents($outputFile,print_r($arr, true),FILE_APPEND);

	echo json_encode($arr);
	exit;
}
if($gid[1]!='Yes'){ 
	$arr['status']='Failed';
	$arr['message']='CLICK2CALL_NOT_ALLOWED';
	file_put_contents($outputFile,print_r($arr, true),FILE_APPEND);

	echo json_encode($arr);
	exit;
}
if($gid[0]!=NULL){
	$groupid=$gid[0];
	$resellerid=$gid[2];
	$date=date("Y-m-d");
	// get biller details 
	$getgid="SELECT c2cpri,set_did_no,rdins,dial_prefix from dids where assignedto='$groupid' ";
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
		file_put_contents($outputFile,print_r($arr, true),FILE_APPEND);
		$arr['status']='Failed';
		$arr['message']='SERVICE_ERROR';
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
		if($operatorid !=''){
			
			$QUERY = "SELECT operatoraccount.id,phonenumber FROM operatoraccount WHERE id='$operatorid'";
			$resd=mysqli_query($link,$QUERY);//$agent= '8594049580';
			$result=mysqli_fetch_row($resd);
			$agent = $result[1];
		}else {
				//get the account phonenumber
			$QUERY = "SELECT phone_number FROM account WHERE groupid='".$groupid."' ";
			$resd=mysqli_query($link,$QUERY);//$agent= '8594049580';
			$result=mysqli_fetch_row($resd);
			if (is_array($result) && ($result[0] > 0) ) {
				$agent = $result[0];
				$operatorid ='';
			}else {
				$arr['status']='Failed';
				$arr['message']='OPERATOR_NUM_NOT_VALID';
				echo json_encode($arr);
				exit;
			}		
		}		
	} else{
		$arr['status']='Failed';
		$arr['message']='NO_DEPARTMENT_OR_NON_OFFICE_HOURS';
		
		file_put_contents($outputFile,print_r($arr, true),FILE_APPEND);
		
		echo json_encode($arr);
		exit;
	}
	// change the entries 
	$cdrinsert="INSERT INTO cdrc2c (`groupid`, `resellerid`, `operatorid`, `datetime`, `deptname`, `status`, `number`,comments,did_no) VALUES ('".$groupid."', '".$resellerid."', '".$operatorid."', CURRENT_TIMESTAMP,  '$dept_name', 'DIALING', '".$number."','".$agent."',''); ";		
	mysqli_query($link,$cdrinsert);
	$cdrid=mysqli_insert_id($link);
	file_put_contents($outputFile,"going to dial now with cdrid $cdrid",FILE_APPEND);
	
	if($dialstring==NULL)	{
		$dialstring=$destination;
	}
	$rec =date("Ymd_His_").$number;
	$phone1 = $dialprefix.$agent."-".$cdrid."-".$set_did_no;
	$phone2 = $dialprefix.$number."-".$cdrid."-".$set_did_no;
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
	$arr['status']='Success';
	$arr['message']='Dialing your number';
	echo json_encode($arr);
	exit;
}
?>
