<?php
// Initialize the session

require('db_connect.php');


session_start();

 //require('designeraunthenticate.php');

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["designerloggedin"]) || $_SESSION["designerloggedin"] !== true){
    header("location: designerlogin.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <title>African Design</title>
  </head>
<body>
    <main class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="index.php">African Designs</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="comments.php">Link</a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</a>
                        <a class="nav-link" aria-current="page" href="design.php">Upload Designs</a>
                        <a class="nav-link" aria-current="page" href="password_reset_designer.php">Reset Password</a>
                        <a class="nav-link" aria-current="page" href="logout.php">Sign Out</a>
                    </ul>
                </div>
          </div>
        </nav>
    
        <ul class="nav nav-fill w-100">
            <li class="nav-item">
                <a class="nav-link" href="#">Dolores</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Bubbles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Dolores</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Bubbles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Dolores</a>
            </li>
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Bubbles</a>
            </li>
        </ul>
    
        <div class="card" >
            <img class="img-fluid" src="images/designs.jpg" alt="Robots in the Park">
            <div class="search-box">
                <div class="form-group">
                    <form class="d-inline-flex p-3">
                        <input class="form-control" type="search" placeholder="Search for designs" aria-label="Search">
                        <button class="btn btn-primary" type="submit">Search</button>
                     </form>
                     <p>Trending:</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>