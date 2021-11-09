<?php
// Initialize the session
// Include config file
require('db_connect.php');
   
$username = $_SESSION['username'];
$stmt = $db->prepare("SELECT * FROM designers");
$stmt->execute();
$designers = $stmt->fetchAll();
      
foreach($designers as $designer) {
      
    if(($designer['username'] == $username)) {
            header("location: designerwelcome.php");
        }
    else {
        header("location: index.php");
        exit();
    }
}

?>