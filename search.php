<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){

  if (isset($_POST['search']) && strlen($_POST['searchtext']) >=1) {
      //  Sanitize user input to escape HTML entities and filter out dangerous characters.
      $searchtext = filter_input(INPUT_POST, 'searchtext', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $searchtext = strtolower($searchtext); 
      $categoryId = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
      
      if (isset($categoryId)) {
      	$query = "SELECT * FROM designs WHERE (LOWER(name) LIKE '%$searchtext%' OR LOWER(description) LIKE '%$searchtext%') AND categoryId = '$categoryId'";
	      $statement = $db->prepare($query); //Catch the statement and wait for values
	      $statement->execute();
	      $results = $statement->fetchAll();
      } else {
      	$query = "SELECT * FROM designs WHERE (LOWER(name) LIKE '%$searchtext%' OR LOWER(description) LIKE '%$searchtext%')";
	      $statement = $db->prepare($query); //Catch the statement and wait for values
	      $statement->execute();
	      $results = $statement->fetchAll();
      }
			
      //header("Location: ?search=$searchtext&category=$categoryId");
  		
      $status = 1;
      
    //exit;
  } 
}
 
?> 