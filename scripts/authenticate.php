<?php

session_start();
$_SESSION['last_error'] = '';
$prevLocation = 'Location: ../signIn.php';

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

if (($ret = checkAuthRequest()) != '')	{
	$_SESSION['last_error'] = $ret;
	header($prevLocation);
	exit;
}

if (!include_once('connectDB.php')) {
	$_SESSION['last_error'] = 'Error: cannot find DB connection script';
	header($prevLocation);
	exit;
}

$ret = autorizeDB($connectDB, $_REQUEST['login'], $_REQUEST['passwd']);
if ($ret == 0) {
	$_SESSION['last_error'] = 'Autorization failed, wrong login or password';
	header($prevLocation);
	exit;
} else if ($ret == -1) {
	$_SESSION['last_error'] = 'Connection DB error';
	header($prevLocation);
	exit;
}

// !!!!!!!!!!!!!!!!!!!!!!! Нет возврата ошибки. Это норм?
if (!updateUserTime($connectDB, $_REQUEST['login'])) {
	header($prevLocation);
	exit;
}

$ret = checkUserStatusDB($connectDB, $_REQUEST['login'], $_REQUEST['passwd']);
if ($ret == 0) {
	$_SESSION['last_error'] = 'Please confirm your e-mail';
	header($prevLocation);
	exit;
} else if ($ret == -1) {
	$_SESSION['last_error'] = 'Connection DB error';
	header($prevLocation);
	exit;
}

$_SESSION['loggued_on_user'] = $_REQUEST['login'];
header('Location: ../gallery.php');
?>