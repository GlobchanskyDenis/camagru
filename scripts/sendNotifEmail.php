<?php

if (	!file_exists('../config') ||
		!file_exists('../config/database.php'))	{
	exit;
}

if (!include_once('../config/database.php')) {
	exit;
}

if ( !isset($_REQUEST['login']) || !isset($_REQUEST['email']) ||
		!isset($_REQUEST['userNotifier']) || !isset($_REQUEST['message'])) {
	exit;
}

$subject = 'Camagru notification';

$message = '<html><body style="font-size: 1.4em;"><p><span style="font-size: 1.3em; color: green;">';
$message .= 'Hello, <b>'.$_REQUEST['login'].'</b></span></p>'.PHP_EOL;
$message .= '<p>'.$_REQUEST['userNotifier']. ' ' . $_REQUEST['message'] .'</p>';
$message .= '</body></html>';

$headers  = 'MIME-Version: 1.0' . PHP_EOL;
$headers .= 'Content-type: text/html; charset=utf8' . PHP_EOL;
mail($_REQUEST['email'], $subject, $message, $headers);

?>