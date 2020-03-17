<?php
function auth($login, $passwd)	{
	/*
	**	DEBUG START
	*/
	// echo 'connecting login:' . $login . ' password:' . $passwd . '</br>' . PHP_EOL;
	/*
	**	DEBUG END
	*/
	if (!file_exists("../private") || !file_exists("../private/passwd") || $login === "" || $passwd === "")
		return (FALSE);
	$file_data_arr = unserialize(file_get_contents("../private/passwd"));
	$passwd = hash('whirlpool', $passwd);
	if ($file_data_arr)						{
		foreach ($file_data_arr as &$user)	{
			
			/*
			**	DEBUG START
			*/
			// echo 'database login:' . $user['login'] . '</br>' . PHP_EOL;
			// if ($user["login"] === $login)
			// 	echo 'login was found  ';
			// else
			// 	echo 'login wrong      ';
			
			// if ($user["passwd"] === $passwd)
			// 	echo 'passwd was found</br>' . PHP_EOL;
			// else
			// 	echo 'passwd wrong    </br>' . PHP_EOL;
			// echo $passwd . '</br>' . PHP_EOL;
			// echo $user["passwd"] . '</br></br>' . PHP_EOL . PHP_EOL;
			/*
			**	DEBUG END
			*/
			
			if ($user["login"] === $login && $user["passwd"] === $passwd)
				return (TRUE);
		}
	}
	return (FALSE);
}
?>