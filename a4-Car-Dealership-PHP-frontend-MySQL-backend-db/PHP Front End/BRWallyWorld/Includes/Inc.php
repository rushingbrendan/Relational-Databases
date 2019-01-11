<?php

/*
FILE          : index.php
PROJECT       : PROG2110 - Relational Databases: Assignment #4 - Wally World
PROGRAMMER    : Brendan Rushing
FIRST VERSION : 2018-12-05
DESCRIPTION   :   This project is a web application for a used car dealership called Wally's World

- This web app uses PHP for serverside communication
- The web page is built with HTML and Javascript

- Wally's World is connected to a MySQL database

- Customers can buy, sell and trade in cars
- Customers can also be added to the database

- Order details can be searched and updated.

*/

    //MYSQL SETTINGS
    $servername = "127.0.0.1";  //server name
    $username = "root";         //user name
    $password = "Conestoga1";   //password
    $dbName = "BRWally";        //database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbName);

    // Check connection
    if ($conn->connect_error) 
    {
	    die("Connection failed: " . $conn->connect_error);
    }    
?>