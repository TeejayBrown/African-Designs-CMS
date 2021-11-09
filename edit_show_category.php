<?php
	/* A Simple Blogging Application
    Title : Edit Page - For Updating and Deleting Post
    Date: September 27th 2021
    Group 8: Taiwo Omoleye and Jan Cyruss Naniong
    */
    require('db_connect.php');

    session_start();
    require('authenticate.php');

    if(!isset($_SESSION["adminloggedin"]) || $_SESSION["adminloggedin"] !== true){
    header("location: admin.php");
    exit;
	}
    
    if (isset($_GET['id'])) { // Retrieve quote to be edited, if id GET parameter is in URL.
        // Sanitize the id. 
        $categoryId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        
        // Build the parametrized SQL query using the filtered id.
        $query = "SELECT * FROM categories WHERE categoryId = :categoryId";
        $statement = $db->prepare($query);
        $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        
        // Execute the SELECT and fetch the single row returned.
        $statement->execute();
        $categories = $statement->fetch();
    } 

    function validateID(){
		return filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
	}

	if(validateID() == false){
		header('Location: adminwelcome.php');
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
                        <a class="nav-link" aria-current="page" href="#">Edit Comments</a>
                        <a class="nav-link" aria-current="page" href="editcategories.php">Edit Categories</a>
                        <a class="nav-link" aria-current="page" href="#">Edit Designs</a>
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
        <h1>Existing Categories</h1>
        <hr>
		<div class="container">		
			<form action="process_post.php" method="post">
				<fieldset>
					<legend>Editing <?= $categories['name'] ?></legend>
					<div class="mb-3">
		                <label for="title" class="form-label fw-bold">Category Name</label>
		                <input type="name" class="form-control" id="title" name="title" value="<?= $categories['name'] ?>">
		            </div>
		            
		            <div class="mb-3">
		                <label for="text" class="form-label fw-bold">Description</label>
		                <textarea class="form-control" id="description" name="description" rows="3"><?= $categories['description'] ?></textarea>
		            </div>
					<p>
						<input type="hidden" name="categoryId" value="<?= $categories['categoryId'] ?>">
						<input type="submit" name="updatecategory" value="Update">
						<input type="submit" name="deletecategory" value="Delete" onclick="return confirm('Are you sure you wish to delete this post?')">
					</p>
				</fieldset>
			</form>	
		</div>
		<div id="footer">
			Copyright 2021 - No Rights Reserved
		</div>
	</div>
</body>
</html>