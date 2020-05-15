<?php

session_start();
$_SESSION['last_error'] = '';
$prevLocation = 'Location: ../signUp.php';

if (!include_once("functions.php")) {
	$_SESSION['last_error'] = 'Error: cannot find function script';
	header($prevLocation);
	exit;
}

if (isset($_SESSION['loggued_on_user']) && $_SESSION['loggued_on_user'] != '') {
	$_SESSION['last_error'] = 'You are already logged as ' . xmlDefense($_SESSION['loggued_on_user']);
	header($prevLocation);
	exit;
}

if ( ($ret = regRequestErrors()) != '' )	{
	$_SESSION['last_error'] = $ret;
	header($prevLocation);
	exit;
}

if (!include_once('connectDB.php')) {
	$_SESSION['last_error'] = 'Error: cannot find DB connection script';
	header($prevLocation);
	exit;
}


$ret = checkLoginInDB( $connectDB, $_REQUEST['login'] );
if ( $ret == 1 ) {
	$_SESSION['last_error'] = 'This login is already taken';
	header($prevLocation);
	exit;
} else if ( $ret == -1 ) {
	$_SESSION['last_error'] = 'Connection DB error';
	header($prevLocation);
	exit;
}

$ret = checkEmailInDB(	$connectDB, $_REQUEST['email'] );
if ($ret == 1) {
	$_SESSION['last_error'] = 'This email is already taken';
	header($prevLocation);
	exit;
} else if ($ret == -1) {
	$_SESSION['last_error'] = 'Connection DB error';
	header($prevLocation);
	exit;
}

if (!userRegisterDB( $connectDB, $_REQUEST['login'], $_REQUEST['passwd'], $_REQUEST['email'], 'not confirmed' )) {
	$_SESSION['last_error'] = 'Cannot connect to Database';
	header($prevLocation);
	exit;
}


echo 'И вот тут перенаправление на страничку валидации email</br>'.PHP_EOL;

?>