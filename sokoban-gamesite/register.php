<?php
// Husker at inkludere config filen
require_once "config.php";
 
// Definerer variabler her og lader værdierne stå tomme
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Indlæser formdata når form bliver sendt
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validerer brugernavn
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Forbereder et select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Binder variabler til det forberedte statement som parametre
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Sætter parametre
            $param_username = trim($_POST["username"]);
            
            // Forsøger at execute statement
            if(mysqli_stmt_execute($stmt)){
                /* Gemmer resultat */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Luk statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validerer password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validerer confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Tjekker input fejl før den bliver sendt til databasen
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Forbereder et insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Binder variabler til det forberedte statement som parametre
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Sætter parametre
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Laver et password hash, til at beskytte koder
            
            // Forsøger at execute statement
            if(mysqli_stmt_execute($stmt)){
                // Videresend bruger til login-siden
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Luk statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Luk forbindelse
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body class="startpage-body">
    <div class="container">
        <div class="d-flex flex-row justify-content-center">
                <div class="d-flex flex-column col-md-4">
                    <div class="form-group" id="margin">          
                    <h2>Sign Up</h2>
                    <p>Please fill out the empty spaces to create an account.</p>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group" id="margin">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                        </div>    
                        <div class="form-group" id="margin">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>
                        <div class="form-group" id="margin">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                        </div>
                        <div class="form-group" id="margin">
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <input type="reset" class="btn btn-secondary ml-2" value="Reset">
                        </div>
                        <div class="form-group" id="margin"> 
                        <p>Already have an account? <a href="login.php"> <br>Login here</a></p>
                        </div>
                    </form>     
                </div>
        </div>
    </div> 
    <img class="box-background" src="img/background.png" alt="">
</body>
</html>