<?php

session_start();
$_SESSION['last_error'] = '';
$prevLocation = 'Location: ../mailConfirm.php';

if (!include_once("functions.php")) {
	$_SESSION['last_error'] = 'Error: cannot find function script';
	header($prevLocation);
	exit;
}

if (($ret = checkValidationRequest()) != '')	{
	$_SESSION['last_error'] = $ret;
	header($prevLocation);
	exit;
}

if (!include_once('connectDB.php')) {
	$_SESSION['last_error'] = 'Error: cannot find DB connection script';
	header($prevLocation);
	exit;
}

if (($ret = checkRegEmail()) != '') {
	$_SESSION['last_error'] = $ret;
	header($prevLocation);
	exit;
}

$ret = checkEmailInDB(	$connectDB, $_REQUEST['email'] );
if ($ret == 1) {
	$_SESSION['last_error'] = 'This email is already taken';
	header($prevLocation);
	exit;
} else if ($ret == -1) {
	$_SESSION['last_error'] = 'Connection DB error';
	header($prevLocation);
	exit;
}

if (!updateEmail($connectDB, $_SESSION['to_confirm'], $_REQUEST['email'])) {
	$_SESSION['last_error'] = 'Something goes wrong';
	header($prevLocation);
	exit;
}

$confirmCode = getConfirmEmailCodeDB($connectDB, $_SESSION['to_confirm']);
if ($confirmCode === 'error') {
	$_SESSION['last_error'] = 'Connection DB error on get confirm code';
	header($prevLocation);
	exit;
} else if ($confirmCode == '') {
	$_SESSION['last_error'] = 'This login isnt exists in DB';
	header($prevLocation);
	exit;
}


// $mailRequest = [
// 	'login'         => $_SESSION['to_confirm'],
// 	'email'         => $_REQUEST['email'],
// 	'confirmCode'	=> $confirmCode
// ];
// $urlEncodedMailRequest = http_build_query($mailRequest);
// $headers = [
// 	'Content-type: application/x-www-form-urlencoded',
// 	'Content-length: '.strlen($urlEncodedMailRequest)
// ];

// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, 'http://'.$MAIL_HOST.':'.$MAIL_PORT.'/scripts/sendConfirmEmail.php');
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
// curl_setopt($ch, CURLOPT_TIMEOUT_MS, 50);
// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $urlEncodedMailRequest);
// curl_exec($ch);
// curl_close($ch);

sendConfirmMail($_SESSION['to_confirm'], $_REQUEST['email'], $confirmCode);

$_SESSION['last_error'] = '<span style="color:green;">Email was updated</span>';
header($prevLocation);

?>