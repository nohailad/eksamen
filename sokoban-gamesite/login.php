<?php
// Starter sessionen
session_start();
 
// Tjekker om brugeren er logget ind, hvis ja så bliver han videresendt til welcome.php
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Husker at inkludere config filen
require_once "config.php";
 
// Definerer variabler her og lader værdierne stå tomme
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Indlæser formdata når form bliver sendt
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Tjekker om username er tomt
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Tjekker om password er tomt
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validerer oplysninger
    if(empty($username_err) && empty($password_err)){
        // Forbereder et select statement til databasen
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Binder variabler til det forberedte statement som parametre
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Sætter parametre
            $param_username = $username;
            
            // Forsøger at execute statement
            if(mysqli_stmt_execute($stmt)){
                // Gemmer resultat
                mysqli_stmt_store_result($stmt);
                
                // Tjekker om username eksisterer, hvis ja så godkender den password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Binder resultat variabler
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password er korrekt, så starter en ny session
                            session_start();
                            
                            // Gemmer data i session variabler
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Videresend bruger til welcome-siden
                            header("location: web/welcome.php");
                        } else{
                            // Password virker ikke
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username eksisterer ikke
                    $login_err = "Invalid username or password.";
                }
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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="web/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body class="startpage-body">
    <div class="container">
        <div class="d-flex flex-row justify-content-center">
            <div class="d-flex flex-column col-md-4">
                    <div class="form-group" id="margin">
                    <h2>Login</h2>
                    <p>Fill in your details to play the game!</p>
                    </div>
                    <?php 
                    if(!empty($login_err)){
                        echo '<div class="alert alert-danger">' . $login_err . '</div>';
                    }        
                    ?>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group" id="margin">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                        </div>    
                        <div class="form-group" id="margin">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>
                        <div class="form-group" id="margin">
                            <input type="submit" class="btn btn-primary" value="Login">
                        </div>
                        <div class="form-group" id="margin">
                        <p class = "signup-text">Don't have an account? <a href="register.php">Sign up now</a>.</p>
                        </div>
                    </form>

            </div>
        </div>
    </div> 
    <img class="box-background2" src="img/background.png" alt="">          
</body>
</html>