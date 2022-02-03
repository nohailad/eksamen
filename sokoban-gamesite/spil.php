
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="game-style.css">
    <script src="main.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
        <img class="boxwall1" src="img/boxwall2.png" alt="boxwall">
        <img class="boxwall2" src="img/boxwall.png" alt="boxwall">
        <img class="boxroof1" src="img/boxroof.png" alt="boxwall">

        <img class="boxroof2" src="img/boxroof.png" alt="boxwall">
        <h1>SOKOBAN</h1>
            <p>--- HOW TO PLAY! ---<br>
            Control the player by using your arrow keys <br>
            Move the boxes into the marked positions to win the game <br>
            Reset the game by hitting space</p>
            <center>
            <form action="welcome.php">
             <input type="submit" value="EXIT" /></form>
                <div id="game-container">
                    <div id="dimmer-div">
                        <p id="winner-text">You are a winner!</p>
                    </div>
                    <canvas width="500" height="500"></canvas>
                    
                </div>
            </center>
</body>
</html>