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
/*session_start();
require('db_connect.php');

if(!empty($_POST['variable'])){

    $query = "
    INSERT INTO designs (name, slug, description, image, categoryId, designerId, created_date) 
    VALUES (:name, :slug, :description, :image,:categoryId, :designerId, CURRENT_TIMESTAMP)";

    $statement = $db->prepare($query);
    $statement->bindvalue(':name', $name);
    $statement->bindvalue(':slug', $slug);
    $statement->bindvalue(':description', $description);
    $statement->bindvalue(':image', $new_image_path );  
    $statement->bindvalue(':categoryId', $categoryId);
    $statement->bindvalue(':designerId', $designerId);
    $statement->execute();
}
*/

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
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <title>African Design</title>
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
                  <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" aria-current="page" href="explore.php">Explore</a>
                </li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <?php if(isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] === true) {?>
                    <a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo ucfirst(htmlspecialchars($_SESSION["username"])); ?></b>.</a>
                    <a class="nav-link" aria-current="page" href="allpages.php">All Pages</a>
                <a class="nav-link" aria-current="page" href="editcomments.php">Comments</a>
                <!-- <a class="nav-link" aria-current="page" href="design_category.php">Design-Category</a> -->
                <a class="nav-link" aria-current="page" href="editcategories.php">Categories</a>
                <a class="nav-link" aria-current="page" href="editdesigns.php">Designs</a>
                <a class="nav-link" aria-current="page" href="password_reset_admin.php">Reset Password</a>
                <a class="nav-link" aria-current="page" href="logout.php">Sign Out</a>
                <?php } elseif(isset($_SESSION["designerloggedin"]) && $_SESSION["designerloggedin"] === true) {?>
                    <a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo ucfirst(htmlspecialchars($_SESSION["username"])); ?></b>.</a>
                    <a class="nav-link" aria-current="page" href="mydesign.php">Designs</a>
              <a class="nav-link" aria-current="page" href="design.php">Upload Designs</a>
              <a class="nav-link" aria-current="page" href="password_reset_designer.php">Reset Password</a>
              <a class="nav-link" aria-current="page" href="logout.php">Sign Out</a>
             <?php } elseif(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {?>
                <a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo ucfirst(htmlspecialchars($_SESSION["username"])); ?></b>.</a>
              <a class="nav-link" aria-current="page" href="design.php">Submit a design</a>
              <a class="nav-link" aria-current="page" href="password_reset.php">Reset Your Password</a>
              <a class="nav-link" aria-current="page" href="logout.php">Sign Out</a>
            <?php } else {?>
                    <a class="nav-link " aria-current="page" href="design.php">Submit a design</a>
                    <a class="nav-link " aria-current="page" href="login.php">Log in</a>
                    <a class="nav-link " aria-current="page" href="designerlogin.php">Designer</a>
                    <a class="nav-link " aria-current="page" href="admin.php">Admin</a>

                    <?php } ?>
                </ul>
            </div>
          </div>
        </nav>
    <main class="container">
        <ul class="nav nav-fill w-100">
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="adire.php">Adire</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="ankara.php">Ankara</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="asooke.php">Aso Oke</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="lace.php">Lace</a>
            </li>
        </ul>
        <h1>Add Page</h1>
        <hr>

        <form action="process_post.php" method="post" enctype='multipart/form-data'>
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Design Name</label>
                <input type="name" class="form-control" name= "designname" id="name" aria-describedby="nameHelp" required>
            </div>
            <div class="mb-3">
                <label for="text" class="form-label fw-bold">Description</label>
                <textarea class="ckeditor" CKEDITOR.config.autoParagraph = false; id="description" name="description" rows="3" required></textarea>
            </div>
           <div class="mb-3">
                <label for="image" class="form-label fw-bold">Image</label>
                <input type="file" name= image class="form-control" id="image" required></input>
            </div> 
            <div class="mb-3">
                <label for="category" class="form-label fw-bold">Category Id</label>
                <select class="form-control" id="category" name="categoryId">
                    <option>-- Select Category --</option>
                    <?php foreach ($results as $result) {?>
                    <option><?php echo $result["categoryId"]; ?> - <?php echo $result["name"]; ?></option>
                <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="designer" class="form-label fw-bold">Designer Id</label>
                <input id="designer" class="form-control" name="designer" value="<?= $_SESSION["designerId"] ?>" readonly required>
            </div> 
            <hr>
            <button type="submit" name= "designupload" class="btn btn-primary">Submit</button>
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