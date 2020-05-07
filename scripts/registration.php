<?php

session_start();
$_SESSION['last_error'] = '';

if (!include_once("functions.php")) {
	$_SESSION['last_error'] .= 'Error: cannot run one of scripts';
	header('Location: ../signUp.php');
	exit;
}

if ($_SESSION['loggued_on_user'] != '') {
	$_SESSION['last_error'] .= 'You are already logged as ' . xmlDefense($_SESSION['loggued_on_user']);
	header('Location: ../signUp.php');
	exit;
}

if ( ($ret = regRequestErrors()) != '' )	{
	$_SESSION['last_error'] = $ret;
	header('Location: ../signUp.php');
	exit;
}

if (	!file_exists('../config') ||
		!file_exists('../config/database.php'))	{
	$_SESSION['last_error'] .= 'Config file not found';
	header('Location: ../signUp.php');
	exit;
}

if (!include_once('../config/database.php')) {
	$_SESSION['last_error'] .= 'Error: cannot run one of scripts';
	header('Location: ../signUp.php');
	exit;
}

$database = mysqli_connect(
	$DB_DSN, $DB_USER, $DB_PASSWORD, $DB_NAME);

if (mysqli_connect_errno() || $database == null) {
	$_SESSION['last_error'] .= 'Error ' . mysqli_connect_errno() . ': ' . mysqli_connect_error();
	header('Location: ../signUp.php');
	exit;
}

if (!checkLoginInBD(	$database, $_REQUEST['login'] )) {
	$_SESSION['last_error'] = 'This login is already taken';
	header('Location: ../signUp.php');
	exit;
}

if (!checkEmailInBD(	$database, $_REQUEST['email'] )) {
	$_SESSION['last_error'] = 'This email is already taken';
	header('Location: ../signUp.php');
	exit;
}

userRegisterBD( $database, $_REQUEST['login'], $_REQUEST['passwd'], $_REQUEST['email'], 'not confirmed' );

if (mysqli_connect_errno()) {
	$_SESSION['last_error'] = 'Registration failed ' . mysqli_connect_errno() . ': ' . mysqli_connect_error();
	header('Location: ../signUp.php');
	exit;
}

// $_SESSION['loggued_on_user'] = $_REQUEST['login'];
echo 'И вот тут перенаправление на страничку валидации email</br>'.PHP_EOL;
// header('Location: ../gallery.php');
?>