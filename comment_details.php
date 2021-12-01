<?php
session_start();
/*if (isset($_GET['id'])) { // Retrieve quote to be edited, if id GET parameter is in URL.
    // Sanitize the id. 
    $designId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    
    // Build the parametrized SQL query using the filtered id.
    $query = "SELECT * FROM designs WHERE designId = :designId";
    $statement = $db->prepare($query);
    $statement->bindValue(':designId', $designId, PDO::PARAM_INT);
    
    // Execute the SELECT and fetch the single row returned.
    $statement->execute();
    $designs = $statement->fetch();
}
*/

require('db_connect.php');
/*$designId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$comments_query = "SELECT * FROM comments WHERE designId= '$designId' ORDER BY comment_date DESC";
$stmt=$db->prepare($comments_query); //WHERE categoryId = '$categoryId'";
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);*/

if (isset($_POST['submit_comment']) && strlen($_POST['comment_text']) >=1 && ($_SESSION['CAPTCHA_CODE'] == $_POST["captcha"])) {

	//$captcha = $_POST["captcha"];

      $captchaUser = filter_var($_POST["captcha"], FILTER_SANITIZE_STRING);

      /*if(empty($captcha)) {
        $captchaError = array(
          "status" => "alert-danger",
          "message" => "Please enter the captcha."
        );
      }
      else if($_SESSION['CAPTCHA_CODE'] == $captchaUser){
        $captchaError = array(
          "status" => "alert-success",
          "message" => "Your form has been submitted successfuly."
        );
      } else {
        $captchaError = array(
          "status" => "alert-danger",
          "message" => "Captcha is invalid."
        );
      }*/
      //$foo = strip_tags('<b>code with html</b>')
    //  Sanitize user input to escape HTML entities and filter out dangerous characters.
      //$description = strip_tags($_POST['comment_text']);
      //$description = filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    $description = filter_input(INPUT_POST, 'comment_text', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $designId = filter_input(INPUT_POST, 'designId', FILTER_SANITIZE_NUMBER_INT);
    $slug = $_SESSION["designName"];
    
    //  Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO comments (description, username, designId) VALUES (:description, :username, :designId)";
    $statement = $db->prepare($query); //Catch the statement and wait for values
    
    //  Bind values to the parameters
    $statement->bindvalue(':description', $description); 
    $statement->bindvalue(':username', $username); 
    $statement->bindvalue(':designId', $designId); 
    
    //  Execute the INSERT.
   $statement->execute();

    $count = "SELECT COUNT(*) AS total FROM comments WHERE designId = '$designId'";
    $data = $db->prepare($count);
    $data->execute();
    $output = $data->fetch();
	    //return $output['total'];


   // Query same comment
/*       $result = "SELECT * FROM comments WHERE designId = '$designId'";
   $stmt = $db->prepare($result);
   $stmt->execute();
   $comment = $stmt->fetchAll(PDO::FETCH_ASSOC);
   //if insert is successful
   if ($statement) {
    $comment_info = array(
        'comment' => $comment,
        'comments_count' => $output['total']
    );
    echo json_encode($comment_info);*/

    // Redirect after submit.
    header("Location: single_design.php?id=$designId&p=$slug");
    exit;     
} else {
    $error_message = "An error occured while processing your post."; 
    $error_detail = "Error.";
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
		      	<?php if(isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] === true) {?>
		      		<a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</a>
                <a class="nav-link" aria-current="page" href="#">Comments</a>
                <a class="nav-link" aria-current="page" href="design_category.php">Design-Category</a>
                <a class="nav-link" aria-current="page" href="editcategories.php">Categories</a>
                <a class="nav-link" aria-current="page" href="editdesigns.php">Designs</a>
                <a class="nav-link" aria-current="page" href="password_reset_admin.php">Reset Password</a>
                <a class="nav-link" aria-current="page" href="logout.php">Sign Out</a>
		      	<?php } elseif(isset($_SESSION["designerloggedin"]) && $_SESSION["designerloggedin"] === true) {?>
		      		<a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</a>
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
	    <div id="wrapper">
	        <div id="header">
	            <h1><a href="index.php"></a></h1>
	        </div>
	        <h1><?= $error_message ?></h1>
	        <p><?= $error_detail ?></p>
	        <a href="index.php">Return Home</a>
	        <div id="footer">
	            Copyright 2021 - No Rights Reserved
	        </div>
	    </div>
	</main>
</body>
</html>