<?php
require_once('auth.php');
session_start();
if ($_POST && $_POST['login'] && $_POST['passwd'] && auth($_POST['login'], $_POST['passwd']))	{
	$_SESSION['loggued_on_user'] = $_POST['login'];
	//echo 'OK' . PHP_EOL;
	header('Location: ../lobby/lobby.php');
	echo 'Redirect to the lobby' . PHP_EOL;
}
else	{
	$_SESSION['loggued_on_user'] = FALSE;
	/*
	**	DEBUG START
	*/
	// echo 'login.php' . '</br>' . PHP_EOL;
	// if (!$_POST)
	// 	echo 'POST method is empty' . PHP_EOL;
	// if ($_POST && !$_POST['passwd'])
	// 	echo 'passwd empty' . PHP_EOL;
	// if ($_POST && !$_POST['login'])
	// 	echo 'login empty' . PHP_EOL;
	/*
	**	DEBUG END
	*/
	header('Location: ../index.html');
	echo 'Cannot authenticate. Redirect to index.html' . PHP_EOL;
}
?>