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
    if (isset($_POST['category']) && strlen($_POST['title']) >=1 && strlen($_POST['description']) >=1) {
        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $name= filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        //  Build the parameterized SQL query and bind to the above sanitized values.
        $query = "INSERT INTO categories (name, description) VALUES (:name, :description)";
        $statement = $db->prepare($query); //Catch the statement and wait for values
        
        //  Bind values to the parameters
        $statement->bindvalue(':name', $name);
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
        $name = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $categoryId  = filter_input(INPUT_POST, 'categoryId', FILTER_SANITIZE_NUMBER_INT);
        
        // Build the parameterized SQL query and bind to the above sanitized values.
        $query     = "UPDATE categories SET name= :name, description = :description WHERE categoryId = :categoryId";
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $name);        
        $statement->bindValue(':description', $description);
        $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        
        // Execute the INSERT.
        $statement->execute();
        
        // Redirect after update.
        header("Location: editcategories.php?id={$id}");
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

        function getCategoryId($str){
            $i = strrpos($str,"-");
            if (!$i) { return ""; }
            $I = strlen($str) - $i;
            $ext = substr($str,$i+1,$I);
            return $ext;
        }
        

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

        function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
           $current_folder = dirname(__FILE__);
           
           // Build an array of paths segment names to be joins using OS specific slashes.
           $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
           
           // The DIRECTORY_SEPARATOR constant is OS specific.
           return join(DIRECTORY_SEPARATOR, $path_segments);
        }

        function file_is_an_image($temporary_path, $new_path) {
            $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png', 'image/pdf'];
            $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png', 'pdf'];
            
            $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
             

            if($actual_file_extension != 'pdf'){
                $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
                $actual_mime_type        = getimagesize($temporary_path)['mime'];
                $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
                $result = $file_extension_is_valid && $mime_type_is_valid;
            }
            else{
                $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
                $result = $file_extension_is_valid; 
            }  
            
            return $result;
        }
        $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error']  === 0); 
        $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error']  > 0);

        //$correct_image_type = True;

        try{
            if ($image_upload_detected) { 
                $image_filename        = $_FILES['image']['name'] ; 
                $temporary_image_path  = $_FILES['image']['tmp_name'] ; 
                $new_image_path        = file_upload_path($image_filename);
                
                if(getExtension($image_filename) != "pdf"){
                $image = new ImageResize($image_filename);
                $image
                        ->resizeToWidth(400)
                        ->save("uploads/".version_name($image_filename , 'medium'))

                        ->resizeToWidth(50)
                        ->save("uploads/".version_name($image_filename , 'thumbnail'));
                }
    
                if (file_is_an_image($temporary_image_path, $new_image_path)) {

                    move_uploaded_file($temporary_image_path, $new_image_path);
                    $name= filter_input(INPUT_POST, 'designname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $categoryId = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
                    $designerId = filter_input(INPUT_POST, 'designerId', FILTER_SANITIZE_NUMBER_INT);

                    $query = "INSERT INTO designs (name, description, image, categoryId, designerId) VALUES (:name, :description, :image,:categoryId, :designerId)";
                    $statement = $db->prepare($query); //Catch the statement and wait for values
                    /*$image_filename        = $_FILES['image']['name'] ; 
                    $temporary_image_path  = $_FILES['image']['tmp_name'] ; 
                    $new_image_path        = file_upload_path($image_filename);
                    move_uploaded_file($temporary_image_path, $new_image_path);*/

                    //  Bind values to the parameters
                    $statement->bindvalue(':name', $name);
                    $statement->bindvalue(':description', $description);
                    $statement->bindvalue(':image', $image_filename );  
                    $statement->bindvalue(':categoryId', $categoryId);
                    $statement->bindvalue(':designerId', $designerId);   
                    
                    //  Execute the INSERT.
                   $statement->execute();

                    // Redirect after submit.
                    header("Location: designerwelcome.php");
                    exit;
                }
            }
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
            //$correct_image_type = FALSE;
        }

        // Validate categoryId
        if(empty($_POST["category"])){
            $category_err = "Please select category.";     
        } else{
            $category = getCategoryId($_POST["category"]);
        }
            
        
             
    } /*else {
        $error_message = "An error occured while processing your post."; 
        $error_detail = "Both the title and content must at least one character.";
    }*/


    /*if (isset($_POST['update']) && strlen($_POST['title']) >=1 && strlen($_POST['content']) >=1 && isset($_POST['id'])) {
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id      = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        
        // Build the parameterized SQL query and bind to the above sanitized values.
        $query     = "UPDATE blogs SET title= :title, content = :content WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);        
        $statement->bindValue(':content', $content);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        
        // Execute the INSERT.
        $statement->execute();
        
        // Redirect after update.
        header("Location: index.php?id={$id}");
        exit;
    } else {
        $error_message = "An error occured while processing your post."; 
        $error_detail = "Both the title and content must at least one character.";
    } 

    if (isset($_POST["delete"]) && isset($_POST['id'])) {
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        // Build the parameterized SQL query and bind to the above sanitized values.
        $query = "DELETE FROM blogs WHERE id=$id LIMIT 1";
        ECHO $id;
        $statement = $db->prepare($query);

        // Execute the INSERT.
        $statement->execute();
        
        // Redirect after update.
        header("Location: index.php");
        exit;
    } */
       
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Winnipeg Nature Facts</title>
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