<?php
// Include config file
require('db_connect.php');
require('countries.php');
 
// Define variables and initialize with empty values
$email = $username = $password = $confirm_password = $first_name = $last_name = $address = $country = $phone =  "";
$email_err = $username_err = $password_err = $confirm_password_err = $first_name_err = $last_name_err = $address_err = $country_err = $phone_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";     
    } elseif(!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i', trim($_POST["email"]))){
        $emailErr = "Invalid email format";
    } else{
        $email = trim($_POST["email"]);
    }

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $query = "SELECT designerId FROM designers WHERE username = :username";
        
        if($statement = $db->prepare($query)){
            // Bind variables to the prepared statement as parameters
            $statement->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($statement->execute()){
                if($statement->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($statement);
        }
    }
   
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 8){
        $password_err = "Password must have atleast 8 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
     
    // Validate first name
    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Please enter first name.";     
    } elseif(!preg_match('/^[a-z]*$/i', trim($_POST["first_name"]))){
        $first_name_err = "Invalid first name format";
    } else{
        $first_name = trim($_POST["first_name"]);
    }

    // Validate last name
    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Please enter last name.";     
    } elseif(!preg_match('/^[a-z]*$/i', trim($_POST["last_name"]))){
        $last_name_err = "Invalid last name format";
    } else{
        $last_name = trim($_POST["last_name"]);
    }

    // Validate address
    if(empty(trim($_POST["address"]))){
        $address_err = "Please enter last name.";     
    } elseif(!preg_match('/^\\d+ [a-zA-Z ]+, [a-zA-Z ]+$/', trim($_POST["address"]))){
        $address_err = "Invalid address format";
    } else{
        $address = trim($_POST["address"]);
    }

    // Validate country
    if(empty($_POST["country"])){
        $country_err = "Please enter country.";     
    } else{
        $country = $_POST["country"];
    }

    // Validate phone
    if(empty(trim($_POST["phone"]))){
        $phone_err = "Please enter phone number.";     
    } elseif(!preg_match('/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/i', trim($_POST["phone"]))){
        $phone_err = "Invalid phone format";
    } else{
        $phone = trim($_POST["phone"]);
    }

    // Check input errors before inserting in database
    if(empty($email_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($first_name_err) && empty($last_name_err) && empty($address_err) && empty($phone_err)){
        
        // Prepare an insert statement
        $query = "INSERT INTO designers (email, username, password, first_name, last_name, address, country, phone) VALUES (:email, :username, :password, :first_name, :last_name, :address, :country, :phone)";
         
        if($statement = $db->prepare($query)){
            // Bind variables to the prepared statement as parameters
            $statement->bindParam(":email", $param_email, PDO::PARAM_STR);
            $statement->bindParam(":username", $param_username, PDO::PARAM_STR);
            $statement->bindParam(":password", $param_password, PDO::PARAM_STR);
            $statement->bindParam(":first_name", $param_first_name, PDO::PARAM_STR);
            $statement->bindParam(":last_name", $param_last_name, PDO::PARAM_STR);
            $statement->bindParam(":address", $param_address, PDO::PARAM_STR);
            $statement->bindParam(":country", $param_country, PDO::PARAM_STR);
            $statement->bindParam(":phone", $param_phone, PDO::PARAM_STR);
            
            // Set parameters
            $param_email = $email;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_address = $address;
            $param_country = $country;
            $param_phone = $phone;
            
            // Attempt to execute the prepared statement
            if($statement->execute()){
                // Redirect to login page
                header("location: designerlogin.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($statement);
        }
    }
    
    // Close connection
    unset($db);
}
    function countries_dropdown($user_country_code='') {
        $option = "";
        foreach ($GLOBALS['countries_list'] as $key => $value) {
            $selected = ($key == $user_country_code ? ' selected' : '');
            $option .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>'."\n";
        }
        return $option;
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
            <a class="navbar-brand" href="#">African Designs</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                      <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Explore</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <a class="nav-link active" aria-current="page" href="login.php">Log in</a>
                    <button class="btn btn-default" type="submit">Submit a design</button>
                </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" required="required">
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>First Name</label>
                <input type="first_name" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                <span class="invalid-feedback"><?php echo $first_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="last_name" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="address" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                <span class="invalid-feedback"><?php echo $address_err; ?></span>
            </div>
            <div class="form-group">
                <label>Choose Country</label>
                <select id="country" name="country" class="form-control">
                    <option value ="" disabled selected>Select country</option>
                    <?php 
                    $user_country_code = '';
                    echo countries_dropdown($user_country_code);
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="phone" name="phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>">
                <span class="invalid-feedback"><?php echo $phone_err; ?></span>
            </div>
            <div class="form-group">
                <label class="checkbox-inline"><input type="checkbox" required="required"> I accept the <a href="#">Terms of Use</a> &amp; <a href="#">Privacy Policy</a></label>
            </div>
            <hr>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>