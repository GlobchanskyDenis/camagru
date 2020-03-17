<?php

function is_valid_login($login)		{
	if ($login == '')
		return (0);
	$len = strlen($login);
	$i = -1;
	while (++$i < $len)
	{
		if ($login[$i] == '<' || $login[$i] == '>')
			return (-1);
	}
	return (1);
}

function is_valid_passwd($passwd)	{
	if ($passwd == '')
		return (0);
	$len = strlen($passwd);
	$i = -1;
	while (++$i < $len)
	{
		if ($passwd[$i] == '<' || $passwd[$i] == '>')
			return (-1);
	}
	return (1);
}

?>