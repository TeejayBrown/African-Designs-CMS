<?php 

require('db_connect.php');
//include ('comment_details.php');
include ('image_display.php');

//$userId = $_SESSION["UserId"];
/*if (isset($_SESSION["UserId"])){
	echo $_SESSION["UserId"];
}*/
session_start();
/*if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
	}*/
$designId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$comments_query = "SELECT * FROM comments WHERE designId= '$designId' ORDER BY comment_date DESC";
$stmt=$db->prepare($comments_query); //WHERE categoryId = '$categoryId'";
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id']) && isset($_GET['design_name'])) { // Retrieve quote to be edited, if id GET parameter is in URL.
    // Sanitize the id. 
    $designId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $slug = filter_input(INPUT_GET, 'design_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $_SESSION["designId"] = $designId;
    // Build the parametrized SQL query using the filtered id.
    $query = "SELECT * FROM designs WHERE designId = :designId && slug = :slug";
    $statement = $db->prepare($query);
    $statement->bindValue(':designId', $designId, PDO::PARAM_INT);
    $statement->bindValue(':slug', $slug, PDO::PARAM_STR);
    // Execute the SELECT and fetch the single row returned.
    $statement->execute();
    $designs = $statement->fetch();
}

if ($designs===false) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['comment_reply'])) { // Retrieve quote to be edited, if id GET parameter is in URL.
    // Sanitize the id. 
    $commentId = filter_input(INPUT_GET, 'comment_reply', FILTER_SANITIZE_NUMBER_INT);
    
    // Build the parametrized SQL query using the filtered id.
    $query = "SELECT * FROM comments WHERE commentId = :commentId";
    $statement = $db->prepare($query);
    $statement->bindValue(':commentId', $commentId, PDO::PARAM_INT);
    
    // Execute the SELECT and fetch the single row returned.
    $statement->execute();
    $comments = $statement->fetch();
    //$display = $designs['image'];
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
		      		<a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</a>
		      		<a class="nav-link" aria-current="page" href="allpages.php">All Pages</a>
                <a class="nav-link" aria-current="page" href="editcomments.php">Comments</a>
                <!-- <a class="nav-link" aria-current="page" href="design_category.php">Design-Category</a> -->
                <a class="nav-link" aria-current="page" href="editcategories.php">Categories</a>
                <a class="nav-link" aria-current="page" href="editdesigns.php">Designs</a>
                <a class="nav-link" aria-current="page" href="password_reset_admin.php">Reset Password</a>
                <a class="nav-link" aria-current="page" href="logout.php">Sign Out</a>
		      	<?php } elseif(isset($_SESSION["designerloggedin"]) && $_SESSION["designerloggedin"] === true) {?>
		      		<a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</a>
		      		<a class="nav-link" aria-current="page" href="mydesign.php">Designs</a>
              <a class="nav-link active" aria-current="page" href="design.php">Upload Design</a>
              <a class="nav-link" aria-current="page" href="password_reset_designer.php">Reset Password</a>
              <a class="nav-link" aria-current="page" href="logout.php">Sign Out</a>
             <?php } elseif(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {?>
            	<a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</a>
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
	  	<h1><?= $designs['name'] ?></h1>
        <hr>
		<div class="container">
			<form class="clearfix" action="process_post.php" method="post" id="comment_form" enctype="multipart/form-data">		
	            <div class="mb-3">
	                <img src="<?= getImageFolder($designs['image']) ?>" alt= <?= $designs['name'] ?>>
	            </div>
				<input type="hidden" name="designId" value="<?php echo $_SESSION["designId"]; ?>">
				<input type="submit" name="delete_design" value="Delete" onclick="return confirm('Are you sure you wish to delete this design?')">
			</form>      				
		</div>		
    </main>
  </body>
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
  crossorigin="anonymous"
></script>
<script src="script.js"></script>
</body>
</html>