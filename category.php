<?php
// Initialize the session

require('db_connect.php');
require('fabrics.php');

session_start();
require('authenticate.php');

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["adminloggedin"]) || $_SESSION["adminloggedin"] !== true){
    header("location: admin.php");
    exit;
}

/*function fabrics_dropdown($user_category_code='') {
        $option = "";
        foreach ($GLOBALS['categories_list'] as $key => $value) {
            $selected = ($key == $user_category_code ? ' selected' : '');
            $option .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>'."\n";
        }
        return $option;
    }*/
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <div class="navbar-nav ms-auto">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="explore.php">Explore</a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo ucfirst(htmlspecialchars($_SESSION["username"])); ?></b>.</a>
                        <a class="nav-link" aria-current="page" href="allpages.php">All Pages</a>
                        <a class="nav-link" aria-current="page" href="editcomments.php">Comments</a>
                        <!-- <a class="nav-link" aria-current="page" href="design_category.php">Design-Category</a> -->
                        <a class="nav-link" aria-current="page" href="editcategories.php">Categories</a>
                        <a class="nav-link" aria-current="page" href="editdesigns.php">Designs</a>
                        <a class="nav-link" aria-current="page" href="password_reset_admin.php">Reset Password</a>
                        <a class="nav-link" aria-current="page" href="logout.php">Sign Out</a>
                    </ul>
                </div>
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
        <h1>Category</h1>
        <hr>

        <form action="process_post.php" method="post">
            <div class="mb-3">
                <label for="title" class="form-label fw-bold">Category Name</label>
                <input type="name" class="form-control" id="title" name="title">
            </div>
            
            <div class="mb-3">
                <label for="text" class="form-label fw-bold">Description</label>
                <textarea class="ckeditor" id="description" name="description" rows="3"></textarea>
            </div>
              <button type="submit" name = category class="btn btn-secondary">Submit</button>
              <a class="btn btn-link ml-2" href="editcategories.php">Cancel</a>
        </form>
        <?php include("footer.php") ?>
    </main>
</body>
</html>