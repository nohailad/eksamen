<?php
// Starter sessionen
session_start();
 
// Tjekker om brugeren er logget ind, hvis ja så bliver han videresendt til login.php
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Husker at inkludere config filen
require_once "config.php";
 
// Definerer variabler her og lader værdierne stå tomme
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Indlæser formdata når form bliver sendt
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validerer nyt password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validerer confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Tjekker input fejl før database bliver updatet
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Forbereder et update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Binder variabler til det forberedte statement som parametre
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Sætter parametre
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Forsøger at execute statement
            if(mysqli_stmt_execute($stmt)){
                // Password er opdateret, ødelægger sessionen og sender dem videre til login-siden
                session_destroy();
                header("location: login.php");
                exit();
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
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body class="startpage-body">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="d-flex flex-column col-md-4">
                <div class="form-wrapper">
                    <div class="form-group" id="margin">
                    <h2>Reset Password</h2>
                    <p>Please fill out this form to reset your password.</p>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                        <div class="form-group" id="margin">
                            <label>New Password</label>
                            <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                            <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                        </div>
                        <div class="form-group" id="margin">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                        </div>
                        <div class="form-group" id="margin">
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <a class="btn btn-link ml-2" href="welcome.php">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>               
</body>
<img class="box-background" src="img/background.png" alt="">
</html>