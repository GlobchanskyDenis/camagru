<?php

function write_to_logs_header($new_log)		{
	if (!file_exists('../debug'))
		mkdir('../debug');
	if (file_exists('../debug/log.txt'))
		$old_log = file_get_contents('../debug/log.txt');
	file_put_contents('../debug/log.txt', $old_log . PHP_EOL . '===========' . $new_log . '===========' . PHP_EOL);
}

function write_to_logs($new_log)			{
	if (!file_exists('../debug'))
		mkdir('../debug');
	if (file_exists('../debug/log.txt'))
		$old_log = file_get_contents('../debug/log.txt');
	date_default_timezone_set('Europe/Paris');
	$date = date("Y:m:d H:m:s");
	file_put_contents('../debug/log.txt', $old_log . $date . "\t" . $new_log . PHP_EOL);
}

?>