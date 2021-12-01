<?php
	/* A Simple Blogging Application
    Title : Edit Page - For Updating and Deleting Post
    Date: September 27th 2021
    Group 8: Taiwo Omoleye and Jan Cyruss Naniong
    */
    require('db_connect.php');
    include ("fileupload.php");
    
    function getImageFolder($str){
        $i = strrpos($str,"uploads");
        if (!$i) { return ""; }
        $I = strlen($str) - $i;
        $ext = substr($str,$i,$I);
        return $ext;
    }

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
    session_start();
    //require('authenticate.php');



    if(!isset($_SESSION["adminloggedin"]) || $_SESSION["adminloggedin"] !== true){
    header("location: admin.php");
    exit;
	}
    
    if (isset($_GET['id']) && isset($_GET['design_name'])) { // Retrieve quote to be edited, if id GET parameter is in URL.
        // Sanitize the id. 
        $designId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $slug = filter_input(INPUT_GET, 'design_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // Build the parametrized SQL query using the filtered id.
        $query = "SELECT * FROM designs WHERE designId = :designId && slug = :slug";
        $statement = $db->prepare($query);
        $statement->bindValue(':designId', $designId, PDO::PARAM_INT);
        $statement->bindValue(':slug', $slug, PDO::PARAM_STR);
        // Execute the SELECT and fetch the single row returned.
        $statement->execute();
        $designs = $statement->fetch();
        //$display = $designs['image'];
    } 

    if ($designs===false) {
        header("Location: editdesigns.php");
        exit;
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
                <a class="nav-link" aria-current="page" href="#">Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</a>
                <a class="nav-link" aria-current="page" href="allpages.php">All Pages</a>
                <a class="nav-link" aria-current="page" href="editcomments.php">Comments</a>
                <!-- <a class="nav-link" aria-current="page" href="design_category.php">Design-Category</a> -->
                <a class="nav-link" aria-current="page" href="editcategories.php">Categories</a>
                <a class="nav-link active" aria-current="page" href="editdesigns.php">Designs</a>
                <a class="nav-link" aria-current="page" href="password_reset_admin.php">Reset Password</a>
                <a class="nav-link" aria-current="page" href="logout.php">Sign Out</a>
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
        <h1>Existing Designs</h1>
        <hr>
		<div class="container">		
			<form action="process_post.php" method="post">
				<fieldset>
					<legend>Editing <?= $designs['name'] ?></legend>
					<div class="mb-3">
		                <label for="title" class="form-label fw-bold">Design Name</label>
		                <input type="name" class="form-control" id="title" name="title" value="<?= $designs['name'] ?>">
		            </div>
		            
		            <div class="mb-3">
		                <label for="text" class="form-label fw-bold">Description</label>
		                <textarea class="ckeditor" id="description" name="description" rows="3"><?= $designs['description'] ?></textarea>
		            </div>
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Image</label>

                        <div class="mb-3">
                            <img src="<?= getImageFolder($designs['image']) ?>" alt= <?= $designs['name'] ?>>
                        </div>
                    </div> 
                    <div class="mb-3">
                        <div class="mb-3">
                            <label for="category" class="form-label fw-bold">Selected Category Id</label>
                            <input type="name" class="form-control" value="<?= $designs['categoryId'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label fw-bold">Category Id: Select to confirm</label>
                            <select class="form-control" id="category" name="categoryId">
                                <option>-- Select Category --</option>
                                <?php foreach ($results as $result) {?>
                                <option><?php echo $result["categoryId"]; ?> - <?php echo $result["name"]; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
					<p>
						<input type="hidden" name="designId" value="<?= $designs['designId'] ?>">
						<input type="submit" name="updatedesign" value="Update">
						<input type="submit" name="deletedesign" value="Delete" onclick="return confirm('Are you sure you wish to delete this post?')">
					</p>
				</fieldset>
			</form>	
		</div>
		<div id="footer">
			Copyright 2021 - No Rights Reserved
		</div>
	</main>
</body>
</html> 