<?php
    /* A simple blogging application
    Title : Db_Connect Page - For Database Connection
    Date: November 7, 2021
    */
    //Defining a datasource with mysql as database
    define('DB_DSN','mysql:host=localhost;dbname=serverside');
    define('DB_USER','serveruser');
    define('DB_PASS','gorgonzola7!');    
     
    try {
         // Try creating new PDO connection to MySQL.
         $db = new PDO(DB_DSN, DB_USER, DB_PASS); // PDO - PHP Data Objects
         //,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    } catch (PDOException $e) {
        print "Error: " . $e->getMessage();
        die(); // Force execution to stop on errors.
        // When deploying to production you should handle this
        // situation more gracefully. ¯\_(ツ)_/¯
    }
 ?>