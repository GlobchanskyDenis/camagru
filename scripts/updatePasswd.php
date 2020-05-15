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
	$_SESSION['last_error'] = 'Cannot connect Database';
	header($prevLocation);
	exit;
}

if (($ret = checkRegPasswd()) != '') {
	$_SESSION['last_error'] = $ret;
	header($prevLocation);
	exit;
}

if (($ret = checkRegPasswdConfirm()) != '') {
	$_SESSION['last_error'] = $ret;
	header($prevLocation);
	exit;
}

if (!updatePasswd($connectDB, $_SESSION['loggued_on_user'], $_REQUEST['passwd'])) {
	$_SESSION['last_error'] = 'Something goes wrong';
	header($prevLocation);
	exit;
} else {
	$_SESSION['last_error'] = '<span style="color:green;">Password was updated</span>';
	header('Location: ../settings.php');
}

?>