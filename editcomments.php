<?php
// Initialize the session

require('db_connect.php');

/*$qry = "SELECT designId, name FROM designs";
try{
    $stmt = $db->prepare($qry);
     // Execution on the DB server is delayed until we execute().
     $stmt->execute(); 
     $outputs = $stmt->fetchAll();
}
catch(Exception $ex) {
 echo ($ex -> getMessage());
}

$query = "SELECT * FROM comments WHERE designId";
try{
    $statement = $db->prepare($query);
     // Execution on the DB server is delayed until we execute().
     $statement->execute(); 
     $results = $statement->fetchAll();
} 
catch(Exception $ex) {
 echo ($ex -> getMessage());
}*/

session_start();
//require('authenticate.php');

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["adminloggedin"]) || $_SESSION["adminloggedin"] !== true){
    header("location: admin.php");
    exit;
}



/*// SQL is written as a String.
 $query = "SELECT * FROM comments ORDER BY commentId";

 // A PDO::Statement is prepared from the query.
 $statement = $db->prepare($query);

 // Execution on the DB server is delayed until we execute().
 $statement->execute(); */

function formatdate($date) {
    return date('F j, Y, g:i a', strtotime($date));
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
                        <a class="nav-link" aria-current="page" href="#">Explore</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</a>
                    <a class="nav-link active" aria-current="page" href="editcomments.php">Comments</a>
                    <!-- <a class="nav-link" aria-current="page" href="design_category.php">Design-Category</a> -->
                    <a class="nav-link" aria-current="page" href="editcategories.php">Categories</a>
                    <a class="nav-link" aria-current="page" href="editdesigns.php">Designs</a>
                    <a class="nav-link" aria-current="page" href="password_reset_admin.php">Reset Password</a>
                    <a class="nav-link" aria-current="page" href="logout.php">Sign Out</a>
                </ul>
            </div>
      </div>
    </nav>
    <main class="container">
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
        <h1>Existing Comments</h1>
        <hr>
        <div class="container"> 
            <div class="mb-3">
                <label for="design" class="form-label fw-bold">Design Id</label>
                <select class="form-control" id="design" name="design">
                    <option value='' selected="" disabled="">-- Select Design --</option>
                    <?php
                        require('db_connect.php');
                        //require "config.php";// connection to database 
                        $sql="select * from designs "; // Query to collect data 

                        foreach ($db->query($sql) as $row) {
                        echo "<option value=$row[designId]>$row[designId]</option>";
                        }
                    ?>
                </select>
            </div>
            <hr>
            <div class="mb-3" id="blog">
                <h2 id= "name"></h2>
                <p><small id= "date"><a></a></small></p>
                <p class="mb-3" id= "blog"></p>      
            </div>
        </div>

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
                        $('#blog').append( $("<p />").html(value.description).text() ).text();
                    });
                }, 'json');
            });
        });
    </script>
        <!-- <div id="footer">
            Copyright 2021 - No Rights Reserved
        </div> -->
    </main>
</body>
</html>