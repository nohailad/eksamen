<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body class="startpage-body">
    <div class="container">
           
            <div class="d-flex col-md-12">
                <h2 class="welcome-title" id="margin">Hey there <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>! Welcome to my game site.</h2>
            </div>
            </div>
                <div class="d-flex flex-row justify-content-center">
                <div class="d-flex col-md-4">
                    <p>
                    <a href="spil.php" class="btn btn-success text-white" id="margin">Play the game!</a>
                    <a href="reset-password.php" class="btn btn-warning text-white" id="margin">Reset your password</a>
                    <a href="logout.php" class="btn btn-danger ml-3" id="margin">Sign out of your account</a>
                    </p>
                </div>
               
    </div>
    <img class="box-background4" src="img/background.png" alt="">        
</body>
</html>