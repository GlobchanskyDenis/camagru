<?php

session_start();

if (!include_once('connectDB.php')) {
	exit;
}

if (!include_once('functions.php')) {
	exit;
}

echo json_encode(getNotifications($connectDB, $_SESSION['loggued_on_user']))

?>