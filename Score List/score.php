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

// Connect to the database
$servername = "localhost"; // Replace with your database server
$dbusername = "root";      // Replace with your database username
$dbpassword = "";          // Replace with your database password
$dbname = "puzzle";       // Replace with your database name

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the last game record for the logged-in user
$sql_last_game = "SELECT * FROM score WHERE username = '$username' ORDER BY id DESC LIMIT 1";
$result_last_game = $conn->query($sql_last_game);

// Fetch the highest score of all records
$sql_high_score = "SELECT MAX(points) AS high_score FROM score";
$result_high_score = $conn->query($sql_high_score);

$last_game = $result_last_game->fetch_assoc();
$high_score = $result_high_score->fetch_assoc();

$gameid = $last_game ? $last_game['id'] : 0;
$currentScore = $last_game ? $last_game['points'] : 0;
$highScore = $high_score['high_score'] ? $high_score['high_score'] : 0;

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Number Puzzle Game - Score Page</title>
  <link rel="stylesheet" href="score.css">
</head>
<body>
  <div class="container">
    <div class="game-card">
      <h1>Number Puzzle Game</h1>

      <!-- Username Input -->
      <div class="username-section">
        <label for="username">Username:</label>
        <input type="text" id="username" value="<?php echo htmlspecialchars($username); ?>" disabled />
      </div>

      <!-- Score Display -->
      <div class="score-section">
        <h2>Game Score</h2>
        <p>Game ID: <span id="gameid"><?php echo $gameid; ?></span></p>
        <p>Your Score: <span id="currentScore"><?php echo $currentScore; ?></span></p>
        <p>High Score: <span id="highScore"><?php echo $highScore; ?></span></p>
      </div>

      <!-- Game Controls -->
      <button class="btn start-btn" onclick="location.href='../Game/game.php'">Start New Game</button>
      <button class="btn reset-btn" onclick="location.href='logout.php'">Logout</button>
    </div>
  </div>

</body>
</html>
