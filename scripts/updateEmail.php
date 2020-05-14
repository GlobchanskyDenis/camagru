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

// $_REQUEST['email'] = $_REQUEST['newEmail'];
if (($ret = checkRegEmail()) != '') {
	$_SESSION['last_error'] = $ret;
	header($prevLocation);
	exit;
}

if (checkEmailInDB($connectDB, $_REQUEST['email'])) {
	$_SESSION['last_error'] = 'User with this email already registered';
	header($prevLocation);
	exit;
}

if (!updateEmail($connectDB, $_SESSION['loggued_on_user'], $_REQUEST['email'])) {
	$_SESSION['last_error'] = 'Something goes wrong';
	header($prevLocation);
	exit;
} else {
	$_SESSION['last_error'] = 'Email was updated';
	header('Location: ../settings.php');
}

?>