<?php
// Initialize the session

require('db_connect.php');
//require('fabrics.php');

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


$qry = "SELECT designId, name FROM designs WHERE categoryId";
try{
    $stmt = $db->prepare($qry);
     // Execution on the DB server is delayed until we execute().
     $stmt->execute(); 
     $outputs = $stmt->fetchAll();
}
catch(Exception $ex) {
 echo ($ex -> getMessage());
}





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
                            <a class="nav-link" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="comments.php">Link</a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</a>
                        <a class="nav-link" aria-current="page" href="editcomments.php">Comments</a>
                        <a class="nav-link" aria-current="page" href="design_category.php">Design-Category</a>
                        <a class="nav-link" aria-current="page" href="editcategories.php">Categories</a>
                        <a class="nav-link" aria-current="page" href="editdesigns.php">Designs</a>
                        <a class="nav-link" aria-current="page" href="password_reset_admin.php">Reset Password</a>
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
        <h1>Add Design_Category</h1>
        <hr>

        <form action="process_post_design_category.php" method="post">
            <div class="mb-3">
                <label for="category" class="form-label fw-bold">Category Id</label>
                <select class="form-control" id="category" name="category">
                    <option value='' selected="" disabled="">-- Select Category --</option>
                    <?php
                        require('db_connect.php');
                        //require "config.php";// connection to database 
                        $sql="select * from categories "; // Query to collect data 

                        foreach ($db->query($sql) as $row) {
                        echo "<option value=$row[categoryId]>$row[categoryId]</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="design" class="form-label fw-bold">Design Id</label>
                <select class="form-control" id="design" name="design">
                </select>
            </div>
            
              <button type="submit" name= design_category class="btn btn-secondary">Submit</button>
        </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
        $(document).ready(function(){
            $('#category').change(function(){
                var categoryId= $('#category').val();
                $('#design').empty();
                $.get('retrieve_design.php', {'categoryId':categoryId},function(returnData){
                    $.each(returnData.data, function(key,value){
                        $('#design').append("<option value= "+value.designId+">"+ value.designId+"</option>");
                    });
                }, 'json');
            });
        });
    </script>
        <div id="footer">
            Copyright 2021 - No Rights Reserved
        </div>
    </main>
</body>
</html>