<?php
// Initialize the session
session_start();
 
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
$_SESSION = [];
//session_destroy();
 
// Redirect to login page
header("location: index.php");
exit;
?>