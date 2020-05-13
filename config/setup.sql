USE Camagru;
DROP TABLE IF EXISTS users;
DROP DATABASE IF EXISTS Camagru;
CREATE DATABASE Camagru;
USE Camagru;
DROP USER IF EXISTS 'bsabre'@'localhost';
CREATE USER 'bsabre'@'localhost' IDENTIFIED by '23';
GRANT CREATE, SELECT, INSERT, UPDATE, DELETE, DROP ON `Camagru`.* TO 'bsabre'@'localhost';