<?php
	$DB_HOST = "localhost";
	$DB_NAME = 'tmp_db';
	$DB_DSN = 'mysql:host='.$DB_HOST.';dbname='.$DB_NAME;
	$DB_USER = "bsabre";
	$DB_PASSWORD = "23";

	try {
    	$connectDB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
		###$connectDB->query('SELECT * FROM users');
	} catch (PDOException $e) {
		echo 'Cannot connect to Database '.$e->getmessage().PHP_EOL;
		exit;
	}
?>
