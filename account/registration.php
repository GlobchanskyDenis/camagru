<?php

require_once('log.php');
require_once('validation.php');
require_once('auth.php');

session_start();

write_to_logs_header('script registration.php start');
write_to_logs('login ' . $_POST['login'] . ' passwd ' . $_POST['passwd']);	// ONLY FOR DEBUG. DELETE THIS LINE IN THE END

if (is_valid_login($_POST['login']) != 1 || is_valid_passwd($_POST['passwd']) != 1 || $_POST['submit'] !== 'Create Account')	{
	if (is_valid_login($_POST['login']) != 1)
		write_to_logs('Invalid login');
	if (is_valid_passwd($_POST['passwd']) != 1)
		write_to_logs('Invalid password');
	if ($_POST['submit'] !== 'Create Account')
		write_to_logs('Invalid submit button');
	write_to_logs('Redirect to registration.html');
	header('Location: ../registration.html');
	exit (0);
}

if ( auth($_POST['login'], $_POST['passwd']) )
{
	write_to_logs('Cannot create account. Login already exists. Redirect to registration.html');
	header('Location: ../registration.html');
	exit (0);
}

// if (!file_exists('../private'))			{
// 	write_to_logs('making private folder');
// 	mkdir("../private");
// }

// if (file_exists('../private/passwd'))	{
// 	write_to_logs('read passwd file');
// 	$file_data_arr = unserialize(file_get_contents('../private/passwd'));
// 	foreach ($file_data_arr as $user)
// 	{
// 		if ($user['login'] === $_POST['login'])
// 		{
// 			write_to_logs('Cannot create account. Login already exists. Redirect to registration.html');
// 			header('Location: ../registration.html');
// 			exit (0);
// 		}
// 	}
// }

$line['login'] = $_POST['login'];
$line['passwd'] = hash('whirlpool', $_POST['passwd']);
$file_data_arr[] = $line;
file_put_contents('../private/passwd', serialize($file_data_arr));
$_SESSION['loggued_on_user'] = $_POST['login'];
write_to_logs('account ' . $_POST['login'] . ' was created. Redirect to index.html');
header('Location: ../index.html');

/*
**	DEBUG START
*/
// echo 'connecting login: ' . $_POST['login'] . ' password: ' . $_POST['passwd'] . '</br>' . PHP_EOL;
// echo 'writed login: ' . $line['login'] . '</br>' . PHP_EOL;
// echo 'hashed passwd: ' . $line["passwd"] . '</br>' . '</br>' . PHP_EOL;
/*
**	DEBUG END
*/

exit(0);

?>