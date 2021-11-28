 <?php
session_start();
 
require('db_connect.php');
include ('image_display.php');

$status = 0;

$query = "SELECT * FROM designs ORDER BY RAND() LIMIT 15";
try{
    $statement = $db->prepare($query);
     // Execution on the DB server is delayed until we execute().
     $statement->execute(); 
     $results = $statement->fetchAll();
} 
catch(Exception $ex) {
 echo ($ex -> getMessage());
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if (isset($_POST['search']) && strlen($_POST['searchtext']) >=1) {
      //  Sanitize user input to escape HTML entities and filter out dangerous characters.
      $searchtext = filter_input(INPUT_POST, 'searchtext', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $categoryId = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);

      $query = "SELECT * FROM designs WHERE name OR description LIKE '%$searchtext%' AND categoryId = '$categoryId'";
      $statement = $db->prepare($query); //Catch the statement and wait for values
      $statement->execute();
      $results = $statement->fetchAll();

      $status = 1;
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
		          <a class="nav-link" aria-current="page" href="#">Explore</a>
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
	
	  	<div class="card" >
	        <img class="img-fluid" src="images/designs.jpg" alt="Robots in the Park">
	        <div class="search-box">
	            <div class="form-group">
	            	<form class="d-inline-flex p-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					        <input class="form-control" type="search" name= "searchtext" placeholder="Search for designs" aria-label="Search">
			    				<select class="form-control" id="category" name="category">
		                <option value='' selected="" disabled="">-- Select Category --</option>
		                <?php
		                    require('db_connect.php');
		                    $sql="select * from categories ";  
		                    foreach ($db->query($sql) as $row) {
		                    echo "<option value=$row[categoryId]>$row[name]</option>";
		                    }
		                ?>
			            </select>
			            <button class="btn btn-primary" type="submit" name= "search">Search</button>
					     </form>
					     <p>Trending:</p>
	            </div>
	        </div>
	    </div>

	    <hr>
			<div class="container">
				<?php if ($status == 0) { ?>
					<div class="row">
						<?php foreach($results  as $result): ?>
				    <div class="col-md-4">
				      <div class="thumbnail">
				      	<a href="single_design.php?id=<?php echo $result['designId']; ?>">	      	
							   <img src="<?php  echo version_name(getImageFolder($result['image']), 'medium'); ?>" alt= "<?php echo $result['name']; ?> ">	
							  </a>
							</div>
						</div>
						<?php endforeach ?>
					</div>
				<?php } elseif ($status == 1) { ?>
					<div class="row">
						<?php if (count($results) > 0) { ?>
							<p><?php echo count($results) ?> design(s) found.</p>
							<?php foreach($results  as $result): ?>
					    <div class="col-md-4">
					      <div class="thumbnail">
					      	<a href="single_design.php?id=<?php echo $result['designId']; ?>">	      	
								   <img src="<?php  echo version_name(getImageFolder($result['image']), 'medium'); ?>" alt= "<?php echo $result['name']; ?> ">	
								  </a>
								</div>
							</div>
							<?php endforeach ?>
						<?php } else { ?>
							<p> Sorry, No result found! Try another search using different key word.</p>
						<?php } ?>
					</div>
				<?php } ?>	
			</div>	
    </main>
  </body>
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
  crossorigin="anonymous"
></script>
</body>
</html>