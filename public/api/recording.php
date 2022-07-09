<?php
include ("dbconnect.php");

$dir = "/var/spool/asterisk/monitorDONE/MP3/362/";
$files =  array();
// Open a directory, and read its contents
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
	if(substr($file,"6",1) == '_'){
		$table ='cdr';
		echo $cdrid = substr($file,0,6);
			echo "<br>";
    		 $s="SELECT datetime,number,cdrid FROM cdr WHERE cdrid='$cdrid' ";
		$re = mysql_query($s)or die(' error');
		$row = mysql_fetch_assoc($re);
		if($row == false) {
			$table = 'cdr_archive';
			 $s="SELECT datetime,number,cdrid FROM cdr_archive WHERE cdrid='$cdrid' ";
        		$re = mysql_query($s)or die(' error');
        		$row = mysql_fetch_assoc($re);
			
		}
		$cdrid= $row['cdrid'];
		$date  = str_replace('-','',$row['datetime']);
		$date  = str_replace(' ','-',$date);
		$date  = str_replace(':','',$date);
	
		 $number = $row['number'];
		 $newfilename = $date.'_'.$number.'.mp3';
		if($cdrid){
			 $update = "UPDATE $table SET recordedfilename='362/$newfilename'  Where cdrid=$cdrid";
			mysql_query($update);
			 echo $cmd= "mv $dir$file $dir$newfilename";
			if(shell_exec($cmd)){
				echo " \n file moved";
			}
		}
	 echo $file.' \n';
	$files[] = $file;
	}else {
//		echo $file;
	}
  }
    closedir($dh);
  }
}
print_r($files,true);
	  

?>
