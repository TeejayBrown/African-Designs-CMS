<?php
// Initialize the session
//session_start();
 
// Check if the admin is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] === true){
    header("location: adminwelcome.php");
    exit;
}
 
// Include config file
require('db_connect.php');

 
function test_input($data) {
      
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
   
if ($_SERVER["REQUEST_METHOD"]== "POST") {
      
    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);
    $stmt = $db->prepare("SELECT * FROM adminlogin");
    $stmt->execute();
    $users = $stmt->fetchAll();
      
    foreach($users as $user) {
          
        if(($user['username'] == $username) && 
            ($user['password'] == $password)) {
             session_start();
                            
                // Store data in session variables
                $_SESSION["adminloggedin"] = true;
                $_SESSION["adminId"] = $id;
                $_SESSION["username"] = $username;                            
                
                // Redirect user to welcome page
                //header("location: welcome.php");
                header("location: adminwelcome.php");
            }
        else {
            header("location: index.php");
            exit();
        }
    }
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
    <title>Admin</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php">African Designs</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="#">Explore</a>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <a class="nav-link" aria-current="page" href="design.php">Submit a design</a>
            <a class="nav-link" aria-current="page" href="login.php">Log in</a>
            <a class="nav-link" aria-current="page" href="designerlogin.php">Designer</a>
            <a class="nav-link active" aria-current="page" href="admin.php">Admin</a>
            </ul>
        </div>
      </div>
    </nav>
    <div class="wrapper">
        <h2>Admin Login</h2>
        <p>Please fill in your credentials to login.</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="">
            </div>
            <hr>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Sign in">
            </div>
        </form>
    </div>
</body>
</html>