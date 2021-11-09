<?php
// Initialize the session

require('db_connect.php');
include ("fileupload.php");

$query = "SELECT categoryId, name FROM categories";
try{
    $statement = $db->prepare($query);
     // Execution on the DB server is delayed until we execute().
     $statement->execute(); 
     $results = $statement->fetchAll();
} 
catch(Exception $ex) {
 echo ($ex -> getMessage());
}

function getCategoryId($str){
    $i = strrpos($str,"-");
    if (!$i) { return ""; }
    $I = strlen($str) - $i;
    $ext = substr($str,$i+1,$I);
    return $ext;
}

// Validate categoryId
if(empty($_POST["category"])){
    $category_err = "Please select category.";     
} else{
    $category = getCategoryId($_POST["category"]);
}


session_start();
 
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
                        <a class="nav-link active" aria-current="page" href="design.php">Upload Design</a>
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
        <h1>Design Upload</h1>
        <hr>

        <form action="process_post.php" method="post" enctype='multipart/form-data'>
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Design Name</label>
                <input type="name" class="form-control" name= "designname" id="name" aria-describedby="nameHelp">
            </div>
            <div class="mb-3">
                <label for="text" class="form-label fw-bold">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
           <div class="mb-3">
                <label for="image" class="form-label fw-bold">Image</label>
                <input type="file" name= image class="form-control" id="image"></input>
            </div> 
            <div class="mb-3">
                <label for="category" class="form-label fw-bold">Category Id</label>
                <select class="form-control" id="category" name="category_id">
                    <option>-- Select Category --</option>
                    <?php foreach ($results as $result) {?>
                    <option><?php echo $result["categoryId"]; ?> - <?php echo $result["name"]; ?></option>
                <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="designer" class="form-label fw-bold">Designer Id</label>
                <input id="designer" class="form-control" name="designer" value="<?= $_SESSION["designerId"] ?>" readonly>
            </div> 
            <hr>
            <button type="submit" name= "designupload" class="btn btn-secondary">submit</button>
        </form>
        <!-- Display response messages -->
        <?php if(!empty($resMessage)) {?>
        <div class="alert <?php echo $resMessage['status']?>">
          <?php echo $resMessage['message']?>
        </div>
        <?php }?>
        <div id="footer">
            Copyright 2021 - No Rights Reserved
        </div>
    </main>
</body>
</html>