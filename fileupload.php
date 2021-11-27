<?php
    /* 
    Title : File Upload Challenge
    Date: November 7, 2021
    Name: Taiwo Omoleye 
    */
    
    include '\xampp\htdocs\wd2\Project\php-image-resize-master\lib\ImageResize.php';
    include '\xampp\htdocs\wd2\Project\php-image-resize-master\lib\ImageResizeException.php';
    use \Gumlet\ImageResize;

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
    
    /*$image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error']  === 0); 
    $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error']  > 0);

    $correct_image_type = True;*/

    /*try{
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
            }
        }
    }
    catch(Exception $e) {
        echo 'Message: ' .$e->getMessage();
        $correct_image_type = FALSE;
    }*/
?>