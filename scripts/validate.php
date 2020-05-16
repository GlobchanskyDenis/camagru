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

$ret = getConfirmEmailCodeDB($connectDB, $_SESSION['to_confirm']);
if ($ret === 'error') {
	$_SESSION['last_error'] = 'Connection DB error';
	header($prevLocation);
	exit;
} else if ($ret == '') {
	$_SESSION['last_error'] = 'Login '.$_SESSION['to_confirm'].' isnt exists in DB ';
	header($prevLocation);
	exit;
}

if ($ret !== $_REQUEST['code']) {
	$_SESSION['last_error'] = 'Wrong confirm code';
	header($prevLocation);
	exit;
}

if (!updateUserStatusDB($connectDB, $_SESSION['to_confirm'], 'user')) {
	$_SESSION['last_error'] = 'Connection DB error';
	header($prevLocation);
	exit;
}

$_SESSION['loggued_on_user'] = $_SESSION['to_confirm'];

if (!updateUserTime($connectDB, $_SESSION['loggued_on_user'])) {
	$_SESSION['last_error'] = 'Connection DB error';
	header($prevLocation);
	exit;
}

header('Location: ../profile.php');

?>