<?php
    if (!file_exists("../private") || !file_exists("../private/passwd") || $_POST["login"] === "" || $_POST["oldpw"] === "" || $_POST["newpw"] === "" || $_POST["submit"] !== "Change Password")
    {
    	header('Location: ../modif.html');
	    echo 'Cannot modify account. Redirect to modif.html' . PHP_EOL;
    }
    $file_data_arr = unserialize(file_get_contents("../private/passwd"));
    $login = $_POST["login"];
    $oldpw = hash(whirlpool, $_POST["oldpw"]);
    $newpw = hash(whirlpool, $_POST["newpw"]);
    foreach ($file_data_arr as &$user)
    {
        if ($user["login"] === $login && $user["passwd"] === $oldpw && $oldpw !== $newpw)
        {
            $user["passwd"] = $newpw;
            file_put_contents("../private/passwd", serialize($file_data_arr));
            header('Location: ../index.html');
            echo 'password was changed successfully' . PHP_EOL;
            exit ;
        }
    }
    header('Location: ../modif.html');
	echo 'Cannot modify account. Redirect to modif.html' . PHP_EOL;
?>