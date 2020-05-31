<?php

if (!file_exists('database.php'))	{
    echo 'No database file was found.'.PHP_EOL;
    echo 'Maybe you forgot to change directory.'.PHP_EOL;
	exit;
}

if (!include_once('database.php')) {
	echo 'Cannot include database file.'.PHP_EOL;
	exit;
}

if (!include_once('../scripts/functions.php')) {
    echo 'Cannot include functions file.'.PHP_EOL;
	exit;
}

try {
    $connectDB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $connectDB->query('DROP TABLE IF EXISTS users');
    $connectDB->query('DROP TABLE IF EXISTS photo');
    $connectDB->query('CREATE TABLE users ( id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
                                            login VARCHAR(15) NOT NULL, 
                                            passwd VARCHAR(35) NOT NULL, 
                                            email VARCHAR(30) NOT NULL, 
                                            status VARCHAR(15) NOT NULL,
                                            notifications BOOL DEFAULT TRUE,
                                            registrationDate TIMESTAMP,
                                            lastVisited TIMESTAMP )');

    userRegisterDB($connectDB, 'admin', 'admin', 'bsabre.cat@gmail.com', 'superUser');
    userRegisterDB($connectDB, 'bsabre', 'Den23@', 'bsabre.cat@yandex.ru', 'user');
    userRegisterDB($connectDB, 'simpleUser', 'User1!', 'skinnyman89@yandex.ru', 'user');

    $connectDB->query('CREATE TABLE photo ( id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
                                            filename VARCHAR(60) NOT NULL,
                                            data MEDIUMBLOB NOT NULL,
                                            author VARCHAR(15) NOT NULL,
                                            authorId INT NOT NULL, 
                                            name VARCHAR(30) NOT NULL,
                                            notifStatus BOOL DEFAULT FALSE,
                                            likeCounter INT DEFAULT 0,
                                            regDate TIMESTAMP,
                                            modDate TIMESTAMP )');    
} catch (PDOException $e) {
	echo 'Cannot connect to Database'.PHP_EOL;
	exit;
}

?>