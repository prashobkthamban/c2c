<?php
include ("/var/www/asterconnect_scripts/dbconnect.php");

$select="SELECT deviceid FROM operatoraccount  WHERE id='36'";
$sel=mysqli_query($link,$select);
while($re=mysqli_fetch_row($sel))
{
echo $message = 'This is my test app REMINDERID id open it with new API' ;
echo "\n:";
 echo $id = $re['0'];
$res = sendFCM($message,$id);
$q="UPDATE assigncdr_app_notify SET status='1' Where id=$re[0]";
mysqli_query($link,$q);
$outputFile = "app_assign_cdr.log";
$fp = fopen($outputFile, 'a');
fwrite($fp, $re[0]);
fwrite($fp, $res);
fwrite($fp, '------------------------------------------------------------------ ');
fclose($fp);


}

function sendFCM($mess,$id) {
$url = 'https://fcm.googleapis.com/fcm/send';
$fields = array (
        'to' => $id,
 	'data' => array (
                 "id" =>"197",
                 "type" =>"RID",
		 "body" =>"$mess"
        )
);
$fields = json_encode ( $fields );
print_r($fields);
$headers = array (
        'Authorization: key=' . "AIzaSyCvG7263mqoTrHMREQVBo7ihyihGDBQoYY",
        'Content-Type: application/json'
);

$ch = curl_init ();
curl_setopt ( $ch, CURLOPT_URL, $url );
curl_setopt ( $ch, CURLOPT_POST, true );
curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

echo $result = curl_exec ( $ch );

curl_close ( $ch );
return $result;
}

?>
