<?php

// session_start();

if (require_once('connectDB.php')) {
	echo 'Connected done'.PHP_EOL;
}
if (require_once('functions.php')) {
	echo 'functions included'.PHP_EOL;
}

if (checkLoginInBD(	$connectDB, 'bsabre' )) {
	echo 'true'.PHP_EOL;
}	else	{
	echo 'false'.PHP_EOL;
}

?>