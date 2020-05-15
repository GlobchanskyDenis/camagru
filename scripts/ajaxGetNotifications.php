<?php

session_start();

$requestAjax = [
	'error' => '',
	'data'	=> '',
];

if (!include_once('connectDB.php')) {
	$requestAjax['error'] = 'Cannot find DB connection script';
	echo json_encode($requestAjax);
	exit;
}

if (!include_once('functions.php')) {
	$requestAjax['error'] = 'Cannot find functions script';
	echo json_encode($requestAjax);
	exit;
}

if (!isset($_SESSION['loggued_on_user'])) {
	$requestAjax['error'] = 'You are not logged in';
	echo json_encode($requestAjax);
	exit;
}

$requestAjax['data'] = getNotifications($connectDB, $_SESSION['loggued_on_user']);
if ($requestAjax['data'] == -1) {
	$requestAjax['error'] = 'Cannot connect to Database';
}
echo json_encode($requestAjax);

?>