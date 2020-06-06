<?php

$requestAjax = [
    'error'    => "",
];

$limit = 5;
session_start();

if (!isset($_SESSION['loggued_on_user'])) {
    $requestAjax['error'] = 'You are not logged';
    echo json_encode($requestAjax);
	exit; 
}

if (!include_once("functions.php")) {
    $requestAjax['error'] = 'cannot find functions file';
    echo json_encode($requestAjax);
	exit; 
}

if (	!file_exists('../config') ||
		!file_exists('../config/database.php'))	{
    $requestAsync['error'] = 'Config file not found';
	echo json_encode($requestAsync);
	exit;
}

if (!include_once('../config/database.php')) {
	$requestAsync['error'] = 'Error: cannot run one of scripts';
	echo json_encode($requestAsync);
	exit;
}

try {
	$connectDB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
	$requestAjax['error'] = 'Cannot connect to Database';
	echo json_encode($requestAjax);
	exit;
}

if ( !($notifArr = getNotificationsByAuthorDB($connectDB, $_SESSION['loggued_on_user'], $limit)) ) {
    $requestAjax['error'] = 'cannot get notifications from database';
    echo json_encode($requestAjax);
    exit;
}

$requestAjax = array_merge($requestAjax, $notifArr);

echo json_encode($requestAjax);

?>