<?php 

const StatusCheckNhso = true;
function DateChanges($change, $time, $full=true){

    $date = date('Y-m-d H:i:s', strtotime($change, strtotime($time)));
    $datestr = strtotime($date);
    if(!$full){
        $date = date('Y-m-d', $datestr);
    }

    return $date;

}

function encrypter($action, $string, $key = "1qaz2wsx3ed!", $salt = "1qaz2wsx3ed!") {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = $key;
    $secret_iv = $salt;
    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

function datethai($strDate,$strTime=NULL)
{
	$strYear = date("Y",strtotime($strDate))+543;
	$strMonth= date("n",strtotime($strDate));
	$strDay= date("j",strtotime($strDate));
	$strHour= date("H",strtotime($strDate));
	$strMinute= date("i",strtotime($strDate));
	$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
	$strMonthThai=$strMonthCut[$strMonth];
	if($strTime == NULL)
	{
		$str = $strDay.' '.$strMonthThai.' '.$strYear;
	}
	else
	{
		$str = $strDay.' '.$strMonthThai.' '.$strYear.' '.$strHour.':'.$strMinute.':'.$strSeconds;
	}
	
	return $str;
}


