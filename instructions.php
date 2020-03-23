<?

$newotp = mt_rand(100000,999999);


require_once("inclib/lib.php");
$phn = implode(',',$_POST['phoneno']);
$explode_num = array_unique(explode(',',$phn));


if($_POST['action'] == "confirm_otp")

{	
	$_SESSION['otp'] = $newotp;

	print_r($_SESSION['otp']);exit;
}

if($_POST['action'] == "new_otp")

{
echo "wdiquwdw";
	//Send OTP SMS

	$ch = curl_init();

	$txt = urlencode('OTP for mobile number verification for your ZSSBOYSHOSTEL registration is '.$newotp.' valid for 10 minutes only');

	//curl_setopt($ch, CURLOPT_URL, "http://sms.pmbtechnology.in/api/sendhttp.php?authkey=316104AMdejcfKc5e351697P1&mobiles=9913692942&message=".$txt."&sender=zssboy&route=4&unicode=1&response=json");

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$smsmsg = curl_exec($ch);

	curl_close($ch);

}


/*if($_POST['action'] == "instructionsdt") //add new records to tbl_student_admission
{
	
	foreach(array_chunk($explode_num, 10) as $num )
	{
		//echo '<pre>';
		//print_r($num);
		$num_set =  implode(',',$num);
		//echo '<br>';
		$ch = curl_init();
		$txt = urlencode($_POST['instructions']);

		curl_setopt($ch, CURLOPT_URL, "http://sms.pmbtechnology.in/api/sendhttp.php?authkey=316104AMdejcfKc5e351697P1&mobiles=".$num_set."&message=".$txt."i&sender=zssboy&route=4&unicode=1&response=json");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		curl_close($ch);
		

	}
	
	$ch1 = curl_init();
	curl_setopt($ch1, CURLOPT_URL, "http://sms.pmbtechnology.in/api/balance.php?authkey=316104AMdejcfKc5e351697P1&type=4");
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch1);
	curl_close($ch1);

	$ch2 = curl_init();
	curl_setopt($ch2, CURLOPT_URL, "http://sms.pmbtechnology.in/api/sendhttp.php?authkey=316104AMdejcfKc5e351697P1&mobiles=9879740238,8320886195&message=Your ZSSBOY sms package balance ".$result." are remainings.&sender=zssboy&route=4&unicode=1&response=json");
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch2);
	curl_close($ch2);
	
}

header("location:".$_POST['link']);*/

?>

<!DOCTYPE HTML>

<html>

<? include "stylesheet.js.php"; ?>

<script type="text/javascript">

function checkFrm(frmName)

{

	if(!validateBlankNew('otp'))

		return false;



return true;

}





</script>

<body>

<? include "top.php"; ?>

<!--banner start here-->

<div class="service">

  <div class="container remove-p">

    <div class="contact-main">

      <form name="frmContact" id="frmContact" action="instructions.php" method="post">

        <input type="hidden" name="action" id="action" value="confirm_otp" />

        <input type="hidden" name="page" id="page" value="<?=$page;?>" />

        <input type="hidden" name="student_admission_id" id="student_admission_id" value="<? echo $_POST['student_admission_id'];?>" />

        <input type="hidden" name="mobile_number" id="mobile_number" value="<? echo $_POST['mobile_number'];?>" />

        <div class="about-head">

          <h3>Instruction Confirmation</h3>

        </div>

        <div class="row">

          <div class="msg col-sm-12 text-center">

            <? 

			if($msg=='')

			{

				echo 'OTP has been send on President Mobile Number';

            }

            else

            {

            	echo $msg;

            }

			?>

          </div>

        </div>

        <br />

        <div class="row">

        <div class="col-sm-5"></div>

          <div class="col-sm-2 text-center contact-left">

              <input type="text" name="otp" id="otp" placeholder="Please Enter OTP" maxlength="6" />

              <button name="cnfmotp" class="btn btn-md" id="cnfmotp">Confirm OTP</button>

        	</div>

         <div class="col-sm-5"></div>

        </div>

      </form>

      <br />

      <br />

      <form name="frmContact" id="frmContact" action="instructions.php" method="post">

        <input type="hidden" name="action" id="action" value="new_otp" />

        <input type="hidden" name="page" id="page" value="<?=$page;?>" />

        <input type="hidden" name="mobile_number" id="mobile_number" value="<? echo $_POST['mobile_number'];?>" />

        <div class="row">

        <div class="col-sm-2"></div>

           <div class="col-sm-8 text-center contact-left">

              if your mobile number <? echo $_POST['mobile_number'];?> not recevied, <button name="cnfmotp" class="btn btn-sm" id="cnfmotp">RESEND</button> again.

        	</div>

         <div class="col-sm-2"></div>

        </div>

      </form>

    </div>

  </div>

</div>

<? include "bottom.php"; ?>