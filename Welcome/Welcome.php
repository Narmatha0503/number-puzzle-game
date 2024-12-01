<?php
session_start(); // Start the session

// Check if the username is set in the session
if (!isset($_SESSION['username'])) {
    // If not, redirect to the login page
    header("Location: ../Sign in Account/Sign-in.html");
    exit;
}

// Retrieve the username from the session
$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Number Puzzle Game</title>
    <link rel="stylesheet" href="Welcome.css">
</head>
<body>

    <div class="container">
        <div class="game-card">
            <h1 class="center-align">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

            <div class="button-section">
                <button class="btn" onclick="location.href='../Game/game.php'">Play Game</button>
                <button class="btn" onclick="location.href='../Score List/score.php'">Scorecard</button>
                <button class="btn" onclick="location.href='logout.php'">Log Out</button>
            </div>
        </div>
    </div>
</body>
</html>
