<?php

session_start();
$prevLocation = 'Location: ../settings.php';

if (!isset($_SESSION['loggued_on_user'])) {
	$_SESSION['last_error'] = 'You are not logged in';
	header($prevLocation);
	exit;
}

if (!include_once('functions.php')) {
	$_SESSION['last_error'] = 'Cannot include functions file';
	header($prevLocation);
	exit;
}

if (!include_once('connectDB.php')) {
	$_SESSION['last_error'] = 'Cannot connect to Database';
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

if (!updateEmail($connectDB, $_SESSION['loggued_on_user'], $_REQUEST['email'])) {
	$_SESSION['last_error'] = 'Something goes wrong';
	header($prevLocation);
	exit;
} else {
	sendChangeMail($_SESSION['loggued_on_user'], $_REQUEST['email']);
	$_SESSION['last_error'] = '<span style="color:green;">Email was updated</span>';
	header('Location: ../settings.php');
}

?>