<?php

session_start();
$_SESSION['last_error'] = '';
$prevLocation = 'Location: ../signUp.php';

if (!include_once("functions.php")) {
	$_SESSION['last_error'] .= 'Error: cannot run one of scripts';
	header($prevLocation);
	exit;
}

if ($_SESSION['loggued_on_user'] != '') {
	$_SESSION['last_error'] .= 'You are already logged as ' . xmlDefense($_SESSION['loggued_on_user']);
	header($prevLocation);
	exit;
}

if ( ($ret = regRequestErrors()) != '' )	{
	$_SESSION['last_error'] = $ret;
	header($prevLocation);
	exit;
}

require_once('connectDB.php');

if (checkLoginInDB(	$connectDB, $_REQUEST['login'] )) {
	$_SESSION['last_error'] = 'This login is already taken';
	header($prevLocation);
	exit;
}

if (checkEmailInDB(	$connectDB, $_REQUEST['email'] )) {
	$_SESSION['last_error'] = 'This email is already taken';
	header($prevLocation);
	exit;
}

userRegisterDB( $connectDB, $_REQUEST['login'], $_REQUEST['passwd'], $_REQUEST['email'], 'not confirmed' );

// $_SESSION['loggued_on_user'] = $_REQUEST['login'];
echo 'И вот тут перенаправление на страничку валидации email</br>'.PHP_EOL;
// header('Location: ../emailConfirm.php');
?>