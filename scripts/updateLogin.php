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

if (($ret = checkRegLogin()) != '') {
	$_SESSION['last_error'] = $ret;
	header($prevLocation);
	exit;
}

if (checkLoginInDB($connectDB, $_REQUEST['login'])) {
	$_SESSION['last_error'] = 'User with same login already registered';
	header($prevLocation);
	exit;
}

if (!updateLogin($connectDB, $_SESSION['loggued_on_user'], $_REQUEST['login'])) {
	$_SESSION['last_error'] = 'Something goes wrong';
	header($prevLocation);
	exit;
} else {
	$_SESSION['last_error'] = '<span style="color:green;">Login was updated</span>';
	$_SESSION['loggued_on_user'] = $_REQUEST['login'];
	header('Location: ../settings.php');
}

?>