<?php
// Establish database connection
$servername = "localhost";
$username_db = "root";  // Your database username
$password_db = "";  // Your database password
$dbname = "puzzle";

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the data from the POST request
$username = $_POST['username'];
$level = $_POST['level'];
$points = $_POST['points'];

// Prepare the SQL statement to insert the score data into the score table
$sql = "INSERT INTO score (username, level, points) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $username, $level, $points);

// Execute the statement
if ($stmt->execute()) {
    echo "Score saved successfully!";
} else {
    echo "Error saving score: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
