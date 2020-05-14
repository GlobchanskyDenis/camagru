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

$notifications = 0;

if (!updateNotifications($connectDB, $_SESSION['loggued_on_user'], $notifications)) {
	$_SESSION['last_error'] = 'Something goes wrong';
	header($prevLocation);
	exit;
} else {
	$_SESSION['last_error'] = 'Login was updated';
	$_SESSION['loggued_on_user'] = $_REQUEST['login'];
	header('Location: ../settings.php');
}

?>