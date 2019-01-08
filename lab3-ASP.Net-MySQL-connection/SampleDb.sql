-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.28-rc-community

--
-- Create schema SampleDB
--
DROP DATABASE IF EXISTS SampleDb;

CREATE DATABASE IF NOT EXISTS SampleDb;

USE SampleDb;

--
-- Definition of table `Smple'
--
DROP TABLE IF EXISTS Sample;
CREATE TABLE Sample(
SampleID Integer PRIMARY KEY AUTO_INCREMENT,
SampleData varchar(10) NOT NULL UNIQUE
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- CREATE USER 'superuser'@'%' IDENTIFIED BY 'Password1';
GRANT ALL PRIVILEGES ON SampleDB.* TO 'superuser'@'%';





