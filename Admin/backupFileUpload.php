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

        function file_upload_path($original_filename, $upload_subfolder_name = 'uploads/') {
           //$current_folder = basename(__DIR__);
           
           // Build an array of paths segment names to be joins using OS specific slashes.
           /*$path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];*/
           $path_segments = $upload_subfolder_name . $original_filename;
           // The DIRECTORY_SEPARATOR constant is OS specific.
           //return join(DIRECTORY_SEPARATOR, $path_segments);
           return  $path_segments;
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

        try{
            if ($image_upload_detected) { 
                $image_filename        = $_FILES['image']['name'] ; 
                $temporary_image_path  = $_FILES['image']['tmp_name'] ; 
                $new_image_path        = file_upload_path($image_filename);
                
                session_start();
                $designer_id = $_SESSION["designerId"];
                $designerName= $_SESSION["username"];
                $designer_folder = "uploads/".$designer_id."/";

                // Checking filesize
                if ($_FILES['image']['size']>1048576) {
                    die("The file is too big. Max size is 1MB");
                }

                // CHECKING IF DESIGNER'S FOLDER EXISTS
                if(!is_dir($designer_folder)) {
                    mkdir("uploads/".$designer_id); 
                }

                $qry = "SELECT * FROM designs WHERE designerId= $designer_id";
                $stmt = $db->prepare($qry); 
                $stmt->execute(); 

                $record = $stmt->fetch();
                $imageMainPath = $record['image'];

                //check database for name similarity
                $rawBaseName = pathinfo($image_filename, PATHINFO_FILENAME );
                $extension = pathinfo($image_filename, PATHINFO_EXTENSION );
                $counter = 0;
                if(file_exists($imageMainPath)) {
                    $image_filename = $designer_folder;
                };

                if(getExtension($image_filename) != "pdf"){
                $image = new ImageResize($image_filename);
                $image
                        ->resize(400, 400)
                        ->save("uploads/".version_name($image_filename , 'medium'))

                        ->resize(50, 50)
                        ->save("uploads/".version_name($image_filename , 'thumbnail'));
                }
                
                $new_image_path = file_upload_path($image_filename);

                if (file_is_an_image($temporary_image_path, $new_image_path)) {
                    $value = getCategoryId($_POST['categoryId']);
                    move_uploaded_file($temporary_image_path, $new_image_path);
                    $name= filter_input(INPUT_POST, 'designname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $description = strip_tags($_POST['description']);
                    $description = filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    //$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $categoryId = filter_input(INPUT_POST, 'categoryId', FILTER_SANITIZE_NUMBER_INT);
                    $designerId = filter_input(INPUT_POST, 'designer', FILTER_SANITIZE_NUMBER_INT);

                    $query = "INSERT INTO designs (name, description, image, categoryId, designerId) VALUES (:name, :description, :image,:categoryId, :designerId)";
                    $statement = $db->prepare($query); //Catch the statement and wait for values

                    //  Bind values to the parameters
                    $statement->bindvalue(':name', $name);
                    $statement->bindvalue(':description', $description);
                    $statement->bindvalue(':image', $new_image_path );  
                    $statement->bindvalue(':categoryId', $categoryId);
                    $statement->bindvalue(':designerId', $designerId);   
                    
                    //  Execute the INSERT.
                   $statement->execute();

                    // Redirect after submit.
                    header("Location: index.php");
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
            
        
             
    } else {
        $error_message = "An error occured while processing your post."; 
        $error_detail = "Both the title and content must at least one character.";
    }