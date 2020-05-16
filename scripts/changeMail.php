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

sendConfirmMail($_SESSION['to_confirm'], $_REQUEST['email'], $confirmCode);

$_SESSION['last_error'] = '<span style="color:green;">Email was updated</span>';
header($prevLocation);

?>