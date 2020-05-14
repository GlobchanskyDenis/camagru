<?php

session_start();

if (!include_once('connectDB.php')) {
	exit;
}
if (!include_once('functions.php')) {
	exit;
}

// if ($_REQUEST['data'] == 0) {
// 	updateNotifications($connectDB, $_SESSION['loggued_on_user'], 1);
// } else {
// 	updateNotifications($connectDB, $_SESSION['loggued_on_user'], 0);
// }

if (($ret = getNotifications($connectDB, $_SESSION['loggued_on_user'])) == -1) {
	exit;
} else if ($ret == 0) {
	updateNotifications($connectDB, $_SESSION['loggued_on_user'], 1);
} else {
	updateNotifications($connectDB, $_SESSION['loggued_on_user'], 0);
}

?>