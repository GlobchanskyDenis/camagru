<?php

session_start();
$requestAjax = [
	'error' => '',
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