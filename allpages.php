<?php
// Initialize the session
require('db_connect.php');

//$query = "SHOW TABLES FROM $dbname";
//$query = "SHOW TABLE STATUS FROM $dbname ORDER BY Create_time";
/*$query = "SELECT Table_name FROM information_schema.tables WHERE TABLE_SCHEMA ='serverside'";
$statement = $db->prepare($query);
$statement->execute(); 
$results = $statement->fetchAll(PDO::FETCH_ASSOC);*/
/*foreach($results as $result){ 
    foreach($result as $key => $val){ 
        echo "$key: $val";
    } 
} */

//$qry = "show table status ";
session_start();
//require('authenticate.php');

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["adminloggedin"]) || $_SESSION["adminloggedin"] !== true){
    header("location: admin.php");
    exit;
}

function formatdate($date) {
    return date('F j, Y, g:i a', strtotime($date));
}

$query = "SELECT name FROM designs ORDER BY RAND()";
$statement = $db->prepare($query);
$statement->execute(); 
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
$status = 0;

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if (isset($_POST['sortbydatecreated'])){
        $query = "SELECT name FROM designs ORDER BY created_date";
        $statement = $db->prepare($query);
        $statement->execute(); 
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $status = 1;
    }

    if (isset($_POST['sortbytitle'])){
        $query = "SELECT name FROM designs";
        $statement = $db->prepare($query);
        $statement->execute(); 
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $status = 2;
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
                    <a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</a>
                    <a class="nav-link active" aria-current="page" href="allpages.php">All Pages</a>
                    <a class="nav-link active" aria-current="page" href="editcomments.php">Comments</a>
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
        <h1>List of Pages</h1>
        <hr>
        <div class="container"> 
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <input type="submit" name="sortbytitle" value="Sort Page By Title">
                    <input type="submit" name="sortbydatecreated" value="Sort Page By Date Created">
                </div>
            </form>
            <div class="mb-3">
                <?php if ($status == 0) { ?>
                    <div class="mb-3">
                        <ul>
                            <?php foreach($results as $result){ ?>
                                <?php foreach($result as $key => $val){ ?>
                                    <li><?php print_r("$val") ?></li>
                                <?php } ?>
                            <?php } ?>
                        </ul> 
                    </div>
                <?php } elseif ($status == 1) { ?>
                    <div class="mb-3">
                        <ul>
                            <?php foreach($results as $result){ ?>
                                <?php foreach($result as $key => $val){ ?>
                                    <li><?php print_r("$val") ?></li>
                                <?php } ?>
                            <?php } ?>
                        </ul> 
                    </div>
                <?php } elseif ($status == 2) { ?>
                    <div class="mb-3">
                        <ul>
                            <?php foreach($results as $result){ ?>
                                <?php foreach($result as $key => $val){ ?>
                                    <li><?php print_r("$val") ?></li>
                                <?php } ?>
                            <?php } ?>
                        </ul> 
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#design').change(function(){
                var designId= $('#design').val();
                $('#blog').empty();
                $.get('retrieve_comments.php', {'designId':designId},function(returnData){
                    $.each(returnData.data, function(key,value){
                        $('#blog').append("<h2>"+value.username+"</h2>");
                        $('#blog').append("<p><small>"+value.comment_date+" - "+"<a href=edit_show_comment.php?id=" +value.commentId+ ">" + "edit" + "</a></small></p>");
                        $('#blog').append("<p>"+ value.description+"</p>");
                    });
                }, 'json');
            });
        });
    </script>
        <!-- <div id="footer">
            Copyright 2021 - No Rights Reserved
        </div> -->
    
</body>
</html>