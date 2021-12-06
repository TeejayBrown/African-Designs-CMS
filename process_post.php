<?php
    /* A Simple Blogging Application
    Title : Process_Post Page - For Validating New/Updated/Deleted Posts and Display Validation Error
    Date: November 7, 2021
    */
    include '\xampp\htdocs\wd2\Project\php-image-resize-master\lib\ImageResize.php';
    include '\xampp\htdocs\wd2\Project\php-image-resize-master\lib\ImageResizeException.php';
    use \Gumlet\ImageResize;

    require('db_connect.php');
    //$content = 
    if (isset($_POST['search']) && strlen($_POST['searchtext']) >=1) {
        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $searchtext = filter_input(INPUT_POST, 'searchtext', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        //  Build the parameterized SQL query and bind to the above sanitized values.
        $query = "SELECT * FROM designs WHERE name OR description LIKE '%$searchtext%'";
        $statement = $db->prepare($query); //Catch the statement and wait for values
        $statement->execute();
        $results = $statement->fetchAll();

        // Redirect after submit.
        header("Location: editcategories.php");
        exit;     
    } else {
        $error_message = "An error occured while processing your post."; 
        $error_detail = "The saerch content must have at least one character.";
    }

    if (isset($_POST['category']) && strlen($_POST['title']) >=1 && strlen($_POST['description']) >=1) {
        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $description = strip_tags($_POST['description']);
        $description = filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $name= filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $slug = strtolower(str_replace(" ", "-", $name));
        //$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        //  Build the parameterized SQL query and bind to the above sanitized values.
        $query = "INSERT INTO categories (name, slug, description) VALUES (:name, :slug, :description)";
        $statement = $db->prepare($query); //Catch the statement and wait for values
        
        //  Bind values to the parameters
        $statement->bindvalue(':name', $name);
        $statement->bindvalue(':slug', $slug);
        $statement->bindvalue(':description', $description);  
        
        //  Execute the INSERT.
       $statement->execute();

        // Redirect after submit.
        header("Location: editcategories.php");
        exit;     
    } else {
        $error_message = "An error occured while processing your post."; 
        $error_detail = "Both the title and content must at least one character.";
    }

    if (isset($_POST['updatecategory']) && strlen($_POST['title']) >=1 && strlen($_POST['description']) >=1 && isset($_POST['categoryId'])) {
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $description = strip_tags($_POST['description']);
        $description = filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $name = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $slug = strtolower(str_replace(" ", "-", $name));
        //$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $categoryId  = filter_input(INPUT_POST, 'categoryId', FILTER_SANITIZE_NUMBER_INT);
        
        // Build the parameterized SQL query and bind to the above sanitized values.
        $query     = "UPDATE categories SET name= :name, slug= :slug, description = :description WHERE categoryId = :categoryId";
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $name);
        $statement->bindvalue(':slug', $slug);        
        $statement->bindValue(':description', $description);
        $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        
        // Execute the INSERT.
        $statement->execute();
        
        // Redirect after update.
        header("Location: editcategories.php");
        exit;
    } else {
        $error_message = "An error occured while processing your post."; 
        $error_detail = "Both the name and description must at least one character.";
    }

    if (isset($_POST["deletecategory"]) && isset($_POST['categoryId'])) {
        $categoryId = filter_input(INPUT_POST, 'categoryId', FILTER_SANITIZE_NUMBER_INT);
        // Build the parameterized SQL query and bind to the above sanitized values.
        $query = "DELETE FROM categories WHERE categoryId=$categoryId LIMIT 1";
        ECHO $categoryId;
        $statement = $db->prepare($query);

        // Execute the INSERT.
        $statement->execute();
        
        // Redirect after update.
        header("Location: editcategories.php");
        exit;
    } 


    if (isset($_POST['designupload'])  && strlen($_POST['designname']) >=1 && strlen($_POST['description']) >=1) {

        function getExtension($str){
            $i = strrpos($str,".");
            if (!$i) { return ""; }
            $I = strlen($str) - $i;
            $ext = substr($str,$i+1,$I);
            return $ext;
        }

        function getFilename($str){
            $i = strrpos($str,".");
            if (!$i) { return ""; }
            $I = strlen($str) - $i;
            $ext = substr($str,0,-$I);
            return $ext;
        }

        function getCategoryId($str){
            $ext = substr($str,0,1);
            return $ext;
        }

        function version_name($str, $name){
            if ($name == 'medium'){
                $mid1 = '_'. $name.'.';
                $result =  getFilename($str).$mid1.getExtension($str);
            } elseif($name == 'thumbnail'){
                $mid1 = '_'. $name.'.';
                $result =  getFilename($str).$mid1.getExtension($str);
            } else{
                $result = "";
            }
            return $result;
        }

        function platformSlashes($path) {
            return str_replace(DIRECTORY_SEPARATOR,'/', $path);
        }

        function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
           $current_folder = dirname(__FILE__);
           
           // Build an array of paths segment names to be joins using OS specific slashes.
           $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
           
           // The DIRECTORY_SEPARATOR constant is OS specific.
           return join(DIRECTORY_SEPARATOR, $path_segments);
        }

        // file_is_an_image() - Checks the mime-type & extension of the uploaded file for "image-ness".
        function file_is_an_image($temporary_path, $new_path) {
            $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
            $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
            
            $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
            $actual_mime_type        = getimagesize($temporary_path)['mime'];
            
            $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
            $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
            
            return $file_extension_is_valid && $mime_type_is_valid;
        }
        
        $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
        $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);

        if ($image_upload_detected) {
            $image_filename        = $_FILES['image']['name'];
            $temporary_image_path  = $_FILES['image']['tmp_name'];

            $actual_name = pathinfo($image_filename,PATHINFO_FILENAME);
            $original_name = $actual_name;
            $extension = pathinfo($image_filename, PATHINFO_EXTENSION);
            $counter = 1;
            //Check if file name exist, add counter to the file name
            while(file_exists(file_upload_path($image_filename))){           
                $actual_name = (string)$original_name.$counter;
                $image_filename = $actual_name.".".$extension;
                $new_image_path = file_upload_path($image_filename);
                $i++;
            }; 

            $new_image_path        = file_upload_path($image_filename);
            if (file_is_an_image($temporary_image_path, $new_image_path)) {
                move_uploaded_file($temporary_image_path, $new_image_path);
            }
            $image = new ImageResize($new_image_path);
                    $image
                            ->resize(400, 400)
                            ->save(version_name($new_image_path, 'medium'))

                            ->resize(50, 50)
                            ->save(version_name($new_image_path, 'thumbnail'));
        }

        $value = getCategoryId($_POST['categoryId']);
        $name= filter_input(INPUT_POST, 'designname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //$description = strip_tags($_POST['description']);
        $slug = strtolower(str_replace(" ", "-", $name));
        $description = htmlspecialchars_decode($_POST['description']);
        $description = filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $categoryId = filter_input(INPUT_POST, 'categoryId', FILTER_SANITIZE_NUMBER_INT);
        $designerId = filter_input(INPUT_POST, 'designer', FILTER_SANITIZE_NUMBER_INT);

        $query = "INSERT INTO designs (name, slug, description, image, categoryId, designerId) VALUES (:name, :slug, :description, :image,:categoryId, :designerId)";
        $statement = $db->prepare($query); //Catch the statement and wait for values

        //  Bind values to the parameters
        $statement->bindvalue(':name', $name);
        $statement->bindvalue(':slug', $slug);
        $statement->bindvalue(':description', $description);
        $statement->bindvalue(':image', $new_image_path );  
        $statement->bindvalue(':categoryId', $categoryId);
        $statement->bindvalue(':designerId', $designerId);   
        
        //  Execute the INSERT.
       $statement->execute();

        // Redirect after submit.
        //header("Location: single_design.php?id=$designId&design_name=$slug");
        header("Location: mydesign.php");
        exit;
    }       

    if (isset($_POST['updatedesign']) && strlen($_POST['title']) >=1 && strlen($_POST['description']) >=1 && isset($_POST['designId'])) {
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $description = strip_tags($_POST['description']);
        $description = filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $name = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $slug = strtolower(str_replace(" ", "-", $name));
        //$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $designId  = filter_input(INPUT_POST, 'designId', FILTER_SANITIZE_NUMBER_INT);
        $categoryId = filter_input(INPUT_POST, 'categoryId', FILTER_SANITIZE_NUMBER_INT);
        
        // Build the parameterized SQL query and bind to the above sanitized values.
        $query     = "UPDATE designs SET name= :name, slug= :slug, description = :description, categoryId = :categoryId WHERE designId = :designId";
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $name); 
        $statement->bindValue(':slug', $slug);       
        $statement->bindValue(':description', $description);
        $statement->bindValue(':categoryId', $categoryId);
        $statement->bindValue(':designId', $designId, PDO::PARAM_INT);
        
        
        // Execute the INSERT.
        $statement->execute();
        
        // Redirect after update.
        header("Location: single_design.php?id=$designId&design_name=$slug");
        exit;
    } else {
        $error_message = "An error occured while processing your post."; 
        $error_detail = "Both the name and description must at least one character.";
    }


    if (isset($_POST["deletedesign"]) && isset($_POST['designId'])) {
        $designId = filter_input(INPUT_POST, 'designId', FILTER_SANITIZE_NUMBER_INT);

        function getExtension($str){
          $i = strrpos($str,".");
          if (!$i) { return ""; }
          $I = strlen($str) - $i;
          $ext = substr($str,$i+1,$I);
          return $ext;
      }

      function getFilename($str){
          $i = strrpos($str,".");
          if (!$i) { return ""; }
          $I = strlen($str) - $i;
          $ext = substr($str,0,-$I);
          return $ext;
      }

      function version_name($str, $name){
          if ($name == 'medium'){
              $mid1 = '_'. $name.'.';
              $result =  getFilename($str).$mid1.getExtension($str);
          } elseif($name == 'thumbnail'){
              $mid1 = '_'. $name.'.';
              $result =  getFilename($str).$mid1.getExtension($str);
          } else{
              $result = "";
          }
          return $result;
      } 
        
        //Retrieve data from database
        $qry = "SELECT * FROM designs WHERE designId= $designId";
        $stmt = $db->prepare($qry); 
        $stmt->execute(); 

        $record = $stmt->fetch();
        //Obtain the image path
        //"C:\xampp\htdocs\wd2\Project\uploads\webdev1.png"
        //"uploads/".version_name($image_filename , 'medium')
        $imageMainPath = $record['image'];
        $imageMediumPath = version_name($record['image'], 'medium');
        $imageThumbnailPath = version_name($record['image'], 'thumbnail');
        //echo version_name($result['image'], 'medium')

        //check if image exists
        if(file_exists($imageMainPath)){

            //delete the image
            unlink($imageMainPath);
            unlink($imageMediumPath);
            unlink($imageThumbnailPath);

            //after deleting image you can delete the record
            $query = "DELETE FROM designs WHERE designId=$designId LIMIT 1";
            $statement = $db->prepare($query);
            $statement->execute();
        }
        /*$query = "DELETE FROM designs WHERE designId=$designId LIMIT 1";
            $statement = $db->prepare($query);
            $statement->execute();*/
        // Redirect after update.
        header("Location: editdesigns.php");
        exit;
    } else {
        $error_message = "An error occured while processing your deletion."; 
        $error_detail = "Delete comment associated with the design.";
    }


    if (isset($_POST["deletecomment"]) && isset($_POST['commentId'])) {
        $commentId = filter_input(INPUT_POST, 'commentId', FILTER_SANITIZE_NUMBER_INT);
        // Build the parameterized SQL query and bind to the above sanitized values.
        $query = "DELETE FROM comments WHERE commentId=$commentId LIMIT 1";
        //ECHO $categoryId;
        $statement = $db->prepare($query);

        // Execute the INSERT.
        $statement->execute();
        
        // Redirect after delete.
        session_start();
        $designId = $_SESSION["logId"];
        header("Location: single_design.php?id='$designId'");
        exit; 
        exit;
    }

    if (isset($_POST["delete_design"]) && isset($_POST['designId'])) {
        $designId = filter_input(INPUT_POST, 'designId', FILTER_SANITIZE_NUMBER_INT);

        function getExtension($str){
          $i = strrpos($str,".");
          if (!$i) { return ""; }
          $I = strlen($str) - $i;
          $ext = substr($str,$i+1,$I);
          return $ext;
      }

      function getFilename($str){
          $i = strrpos($str,".");
          if (!$i) { return ""; }
          $I = strlen($str) - $i;
          $ext = substr($str,0,-$I);
          return $ext;
      }

      function version_name($str, $name){
          if ($name == 'medium'){
              $mid1 = '_'. $name.'.';
              $result =  getFilename($str).$mid1.getExtension($str);
          } elseif($name == 'thumbnail'){
              $mid1 = '_'. $name.'.';
              $result =  getFilename($str).$mid1.getExtension($str);
          } else{
              $result = "";
          }
          return $result;
      } 
        
        //Retrieve data from database
        $qry = "SELECT * FROM designs WHERE designId= $designId";
        $stmt = $db->prepare($qry); 
        $stmt->execute(); 

        $record = $stmt->fetch();
        //Obtain the image path
        //"C:\xampp\htdocs\wd2\Project\uploads\webdev1.png"
        //"uploads/".version_name($image_filename , 'medium')
        $imageMainPath = $record['image'];
        $imageMediumPath = version_name($record['image'], 'medium');
        $imageThumbnailPath = version_name($record['image'], 'thumbnail');
        //echo version_name($result['image'], 'medium')

        //check if image exists
        if(file_exists($imageMainPath)){

            //delete the image
            unlink($imageMainPath);
            unlink($imageMediumPath);
            unlink($imageThumbnailPath);

            //after deleting image you can delete the record
            $query = "DELETE FROM designs WHERE designId=$designId LIMIT 1";
            $statement = $db->prepare($query);
            $statement->execute();
        }
        /*$query = "DELETE FROM designs WHERE designId=$designId LIMIT 1";
            $statement = $db->prepare($query);
            $statement->execute();*/
        // Redirect after update.
        header("Location: mydesign.php");
        exit;
    } else {
        $error_message = "An error occured while processing your deletion."; 
        $error_detail = "Delete comment associated with the design.";
    }
       
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>African Fabrics CMS</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
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
</body>
</html>