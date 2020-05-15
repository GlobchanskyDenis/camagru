<?php

session_start();
$requestAjax = [
	'error' => '',
];

if (	!file_exists('../config') ||
		!file_exists('../config/database.php'))	{
	$requestAjax['error'] = 'Config file not found';
	echo json_encode($requestAjax);
	exit;
}

if (!include_once('../config/database.php')) {
	$requestAjax['error'] = 'Error: cannot run one of scripts';
	echo json_encode($requestAjax);
	exit;
}

try {
	$connectDB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
	$requestAjax['error'] = 'Cannot connect to Database';
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

if ($_REQUEST['data'] == 0) {
	$notification = 1;
} else {
	$notification = 0;
}

if (!updateNotifications($connectDB, $_SESSION['loggued_on_user'], $notification)) {
	$requestAjax['error'] = 'Cannot connect to Database';
}
echo json_encode($requestAjax);
// if (($ret = getNotifications($connectDB, $_SESSION['loggued_on_user'])) == -1) {
// 	exit;
// } else if ($ret == 0) {
// 	updateNotifications($connectDB, $_SESSION['loggued_on_user'], 1);
// } else {
// 	updateNotifications($connectDB, $_SESSION['loggued_on_user'], 0);
// }

?>