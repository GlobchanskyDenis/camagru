<?php

session_start();
$_SESSION['last_error'] = '';
$prevLocation = 'Location: ../signUp.php';

if (!include_once("functions.php")) {
	$_SESSION['last_error'] = 'Error: cannot find function script';
	header($prevLocation);
	exit;
}

if (isset($_SESSION['loggued_on_user']) && $_SESSION['loggued_on_user'] != '') {
	$_SESSION['last_error'] = 'You are already logged as ' . xmlDefense($_SESSION['loggued_on_user']);
	header($prevLocation);
	exit;
}

if ( ($ret = regRequestErrors()) != '' )	{
	$_SESSION['last_error'] = $ret;
	header($prevLocation);
	exit;
}

if (!include_once('connectDB.php')) {
	$_SESSION['last_error'] = 'Error: cannot find DB connection script';
	header($prevLocation);
	exit;
}


$ret = checkLoginInDB( $connectDB, $_REQUEST['login'] );
if ( $ret == 1 ) {
	$_SESSION['last_error'] = 'This login is already taken';
	header($prevLocation);
	exit;
} else if ( $ret == -1 ) {
	$_SESSION['last_error'] = 'Connection DB error on check login';
	header($prevLocation);
	exit;
}

$ret = checkEmailInDB(	$connectDB, $_REQUEST['email'] );
if ($ret == 1) {
	$_SESSION['last_error'] = 'This email is already taken';
	header($prevLocation);
	exit;
} else if ($ret == -1) {
	$_SESSION['last_error'] = 'Connection DB error on check email';
	header($prevLocation);
	exit;
}

if (!userRegisterDB( $connectDB, $_REQUEST['login'], $_REQUEST['passwd'], $_REQUEST['email'], 'not confirmed' )) {
	$_SESSION['last_error'] = 'Cannot connect to Database';
	header($prevLocation);
	exit;
}

$confirmCode = getConfirmEmailCodeDB($connectDB, $_REQUEST['login']);
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
// 	'login'         => $_REQUEST['login'],
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

sendConfirmMail($_REQUEST['login'], $_REQUEST['email'], $confirmCode);

$_SESSION['last_error'] = '<span style="color: green;">Please confirm your e-mail</span>';
$_SESSION['to_confirm'] = $_REQUEST['login'];
$_SESSION['status'] = 'not confirmed';
header('Location: ../mailConfirm.php');

?>