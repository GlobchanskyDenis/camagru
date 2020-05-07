<?php

session_start();
$_SESSION['last_error'] = '';

if (!include_once("functions.php")) {
	$_SESSION['last_error'] = 'Error: cannot run one of scripts';
	header('Location: ../signIn.php');
	exit;
}

if ($_SESSION['loggued_on_user'] != '') {
	$_SESSION['last_error'] .= 'You are already logged as ' . xmlDefense($_SESSION['loggued_on_user']);
	header('Location: ../signIn.php');
	exit;	
}

if (!checkAuthRequest())	{
	header('Location: ../signIn.php');
	exit;
}

if (	!file_exists('../config') ||
		!file_exists('../config/database.php'))	{
	$_SESSION['last_error'] .= 'Config file not found';
	header('Location: ../signIn.php');
	exit;
}

if (!include_once('../config/database.php')) {
	$_SESSION['last_error'] .= 'Error: cannot run one of scripts';
	header('Location: ../signIn.php');
	exit;
}

$database = mysqli_connect(
	$DB_DSN, $DB_USER, $DB_PASSWORD, $DB_NAME);

if (mysqli_connect_errno() || $database == null) {
	$_SESSION['last_error'] .= 'Error ' . mysqli_connect_errno() . ': ' . mysqli_connect_error();
	header('Location: ../signIn.php');
	exit;
}

if (!autorizeBD($database, $_REQUEST['login'], $_REQUEST['passwd'])) {
	$_SESSION['last_error'] = 'Autorization failed, wrong login or password';
	header('Location: ../signIn.php');
	exit;
}

if (!checkUserStatusBD($database, $_REQUEST['login'], $_REQUEST['passwd'])) {
	$_SESSION['last_error'] = 'Please confirm your e-mail';
	header('Location: ../emailConfirm.php');
	exit;
}

$_SESSION['loggued_on_user'] = $_REQUEST['login'];
header('Location: ../gallery.php');
?>