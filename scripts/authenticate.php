<?php

session_start();
$_SESSION['last_error'] = '';
$prevLocation = 'Location: ../signIn.php';

if (!include_once("functions.php")) {
	$_SESSION['last_error'] = 'Error: cannot run one of scripts';
	header($prevLocation);
	exit;
}

if ($_SESSION['loggued_on_user'] != '') {
	$_SESSION['last_error'] .= 'You are already logged as ' . xmlDefense($_SESSION['loggued_on_user']);
	header($prevLocation);
	exit;	
}

if (!checkAuthRequest())	{
	header($prevLocation);
	exit;
}

require_once('connectDB.php');

if (!autorizeDB($connectDB, $_REQUEST['login'], $_REQUEST['passwd'])) {
	$_SESSION['last_error'] = 'Autorization failed, wrong login or password';
	header($prevLocation);
	exit;
}

if (!checkUserStatusDB($connectDB, $_REQUEST['login'], $_REQUEST['passwd'])) {
	$_SESSION['last_error'] = 'Please confirm your e-mail';
	header($prevLocation);
	// header('Location: ../emailConfirm.php');
	exit;
}

$_SESSION['loggued_on_user'] = $_REQUEST['login'];
header($prevLocation);
?>