<?php
    session_start();
	$_SESSION["loggued_on_user"] = "";
	header('Location: ../index.html');
	echo 'user was loggued out' . PHP_EOL;
?>