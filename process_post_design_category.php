<?php
require('db_connect.php');

if (isset($_POST['design_category']) && isset($_POST['category'])){
		/*$category=$_POST['category'];
		$design=$_POST['design'];*/
		//, $_POST['category'], $_POST['design'])
       //  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $categoryId= filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
        $designId= filter_input(INPUT_POST, 'design', FILTER_SANITIZE_NUMBER_INT);
        
        //  Build the parameterized SQL query and bind to the above sanitized values.
        $query = "INSERT INTO design_categories ($categoryId, $designId) VALUES (:categoryId, :designId)";
        $statement = $db->prepare($query); //Catch the statement and wait for values
        
        //  Bind values to the parameters
        $statement->bindvalue(':categoryId', $categoryId);
        $statement->bindvalue(':designId', $designId);  
        
        //  Execute the INSERT.
       $statement->execute();

        // Redirect after submit.
        header("Location: design_category.php");
        exit;     
    } else {
        $error_message = "An error occured while processing your post."; 
        $error_detail = "Check Again.";
    } 

?>