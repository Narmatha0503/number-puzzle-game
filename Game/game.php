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
    <title>Number Puzzle Game</title>
    <link rel="stylesheet" href="game.css">
</head>
<body>

<script>
    
    const username = "<?php echo htmlspecialchars($username); ?>";

    // Game state variables
    let level = 1;
    let lives = 3;
    let points = 0;
    let time = 120;  // 2 minutes default time
    let solution;    // To store the correct solution
    let timerInterval;

 
    function startGame() {



        // Fetch the image URL and solution from the API
        fetch("https://marcconrad.com/uob/banana/api.php")
            .then(response => response.json())  // Parse the response as JSON
            .then(data => {
                const imageUrl = data.question;  // Get the question image URL
                solution = data.solution;  // Get the solution from API

                // Set the image URL in the img element
                const imageElement = document.getElementById('bananaImage');
                imageElement.src = imageUrl;

                // Display the current game status
                document.getElementById('level').textContent = level;
                document.getElementById('lives').textContent = lives;
                document.getElementById('points').textContent = points;
                document.getElementById('timer').textContent = formatTime(time);

                // Start the countdown timer
                startTimer();
            })
            .catch(error => {
                console.error("Error fetching the image:", error);
            });
    }

    // Timer function to count down from 2 minutes
    function startTimer() {
        timerInterval = setInterval(function() {
            time--;
            document.getElementById('timer').textContent = formatTime(time);

            // If the time runs out, stop the game
            if (time <= 0) {
                clearInterval(timerInterval);
                endGame();
            }
        }, 1000);
    }

    // Format time from seconds to mm:ss
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const secondsLeft = seconds % 60;
        return `${minutes}:${secondsLeft < 10 ? '0' + secondsLeft : secondsLeft}`;
    }

    // Handle the player's answer
    function checkAnswer(answer) {
        if (parseInt(answer) === solution) {
            // Correct answer
            points += 10;
            level++;
            time += 10;  // Increase time by 10 seconds for correct answer

            // Update the game status
            document.getElementById('level').textContent = level;
            document.getElementById('points').textContent = points;
            alert("Correct! +10 Points, Level Up!");

            // Load the next question
            loadNextQuestion();
        } else {
            // Wrong answer
            lives--;
            document.getElementById('lives').textContent = lives;

            if (lives <= 0) {
                endGame();
            } else {
                alert("Wrong answer! Try again.");
            }
        }
    }

    // End the game (either timeout or no lives left)
    function endGame() {
        // Send game data to the backend
        saveScoreToDatabase(username, level, points);
        alert("Game Over! Your score has been saved.");
        
        // Redirect to the welcome page after saving
        window.location.href = "../Score List/score.php";
    }

    // Function to save score to the backend (send via AJAX)
    function saveScoreToDatabase(username, level, points) {
        fetch("save_score.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `username=${username}&level=${level}&points=${points}`
        })
        .then(response => response.text())
        .then(data => {
            console.log("Score saved:", data);
        })
        .catch(error => {
            console.error("Error saving score:", error);
        });
    }

    // Load the next question from the API
    function loadNextQuestion() {
        fetch("https://marcconrad.com/uob/banana/api.php")
            .then(response => response.json())  // Parse the response as JSON
            .then(data => {
                const imageUrl = data.question;  // Get the next question image URL
                solution = data.solution;  // Get the new solution

                // Set the new image in the img element
                const imageElement = document.getElementById('bananaImage');
                imageElement.src = imageUrl;

                // Reset the input box for the next question
                document.getElementById('answer-box').value = "";
            })
            .catch(error => {
                console.error("Error fetching the next question:", error);
            });
    }

    // Initialize the game
    window.onload = startGame;
</script>

<div class="container">
    <!-- Left Side: Game Info -->
    <div class="game-info">
        <h1>Number Puzzle Game</h1>

        <!-- User Info Section -->
        <div id="user-info">
            <!-- Display the username in the text field and disable it -->
            <label for="player-name">Player Name:</label>
            <input type="text" id="player-name" value="<?php echo htmlspecialchars($username); ?>" disabled>
        </div>

        <!-- Game Info Section -->
        <div id="game-info" style="display: block;">
            <p><strong>Level:</strong> <span id="level">1</span></p>
            <p><strong>Lives:</strong> <span id="lives">3</span></p>
            <p><strong>Points:</strong> <span id="points">0</span></p>
            <p><strong>Time:</strong> <span id="timer">02:00</span></p>
            <button id="logout-btn" onclick="location.href='logout.php'">Log Out</button>
        </div>
    </div>

    <!-- Right Side: Puzzle Board -->
     <div style="display: grid;">
    <div class="puzzle-container" style="text-align: center;">
        <img id="bananaImage" alt="Banana Image" style="max-width: 50%; height: auto;">
    </div>

    <!-- Answer Boxes -->
    <div class="answer-container" style="text-align: center;">
        <label for="answer-box">Choose an answer (0-9):</label>
        <input type="number" id="answer-box" min="0" max="9">
       <!--   <button onclick="checkAnswer(document.getElementById('answer-box').value)">Submit Answer</button>-->
    </div>
    <div class="answer-container" style="text-align: center;">
        <button onclick="checkAnswer(document.getElementById('answer-box').value)">Submit Answer</button>
    </div>
    </div>


</div>

</body>
</html>
