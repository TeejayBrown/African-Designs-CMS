<?php
  /* A simple blogging application
    Title : Authentication Page - For Username and Password Authentication
    Date: September 27th 2021
    Group 8: Taiwo Omoleye and Jan Cyruss Naniong
    */

  /*define('ADMIN_LOGIN','dragonfly');

  define('ADMIN_PASSWORD','grey');

  if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])

      || ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN)

      || ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD)) {

    header('HTTP/1.1 401 Unauthorized');

    header('WWW-Authenticate: Basic realm="Our Blog"');

    exit("Access Denied: Username and password required.");

  }*/  

  // check if username is admin
if($_SESSION['username'] !== 'dragonfly'){
    // isn't admin, redirect them to a different page
    header("Location: logout.php");
}


?>