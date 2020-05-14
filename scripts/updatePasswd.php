<?php

session_start();
$prevLocation = 'Location: ../settings.php';

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

// $_REQUEST['login'] = $_REQUEST['newLogin'];
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

// if (checkLoginInDB($connectDB, $_REQUEST['login'])) {
// 	$_SESSION['last_error'] = 'User with same login already registered';
// 	header($prevLocation);
// 	exit;
// }

if (!updatePasswd($connectDB, $_SESSION['loggued_on_user'], $_REQUEST['passwd'])) {
	$_SESSION['last_error'] = 'Something goes wrong';
	header($prevLocation);
	exit;
} else {
	$_SESSION['last_error'] = 'Password was updated';
	header('Location: ../settings.php');
}

?>