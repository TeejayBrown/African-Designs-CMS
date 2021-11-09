 <?php
session_start();
 
// Check if any user is already logged in, if yes then redirect him to welcome page
/*if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}*/
	require('db_connect.php');

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
                <a class="nav-link" aria-current="page" href="#">Edit Comments</a>
                <a class="nav-link" aria-current="page" href="editcategories.php">Edit Categories</a>
                <a class="nav-link" aria-current="page" href="#">Edit Designs</a>
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
	</div>
	
  	<div class="card" >
        <img class="img-fluid" src="images/designs.jpg" alt="Robots in the Park">
        <div class="search-box">
            <div class="form-group">
            	<form class="d-inline-flex p-3">
			        <input class="form-control" type="search" placeholder="Search for designs" aria-label="Search">
    				<button class="btn btn-primary" type="submit">Search</button>
			     </form>
			     <p>Trending:</p>
            </div>
        </div>
    </div>

    <hr>
	<!-- <div class="container">
	  <img class="img-thumbnail" src="https://pixelprowess.com/i/park_tn.png" alt="Robots in the Park">
	</div> -->
	<div class="row row-cols-1 row-cols-md-3">
	  <div class="col">
	    <div class="card">
	    	<div class="image">
	      	<img class="card-img-top" src="https://pixelprowess.com/i/mug-dolores.jpg" alt="Rex">
	      </div>
	      <div class= card-show>
		      <div class="card-img-overlay text-black">
				    <h1 class="card-title">Bubbles</h1>
				    <p class="card-text lh-sm">That's Bubble Gum Robot, or "Bubbles" for short.</p>
				    <a href="#" class="card-link btn btn-light mt-auto">more info</a>
				  </div>
				</div>
	    </div>
	  </div>

		<div class="col">
		  <div class="card">
		  	<div class="image">
		    	<img class="card-img-top" src="https://pixelprowess.com/i/mug-dolores.jpg" alt="Rex">
		    </div>
		    <div class= card-show>
			    <div class="card-img-overlay text-black">
				    <h1 class="card-title">Bubbles</h1>
				    <p class="card-text lh-sm">That's Bubble Gum Robot, or "Bubbles" for short.</p>
				    <a href="#" class="card-link btn btn-light mt-auto">more info</a>
				  </div>
				</div>
		  </div>
		</div>

		<div class="col">
		  <div class="card">
		  	<div class="image">
		    	<img class="card-img-top" src="https://pixelprowess.com/i/mug-bubbles.jpg" alt="bubbles">
		  	</div>
		    <div class= card-show>
			    <div class="card-img-overlay text-blue">
				    <h1 class="card-title">Bubbles</h1>
				    <p class="card-text lh-sm">That's Bubble Gum Robot, or "Bubbles" for short.</p>
				    <a href="#" class="card-link btn btn-light mt-auto">more info</a>
				  </div>
				</div>
		  </div>  
		</div>
	</div> 
    </main>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
      crossorigin="anonymous"
    ></script>
  </body>
</html>