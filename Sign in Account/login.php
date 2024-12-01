<?php
session_start(); // Start the session

// Database connection details
$servername = "localhost";
$dbusername = "root"; // Your database username
$dbpassword = ""; // Your database password
$dbname = "puzzle";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch form data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Debugging: Log received inputs
error_log("Received Username: $username");
error_log("Received Password: $password");

// Check if the username exists
$sql = "SELECT password FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($stored_password);
    $stmt->fetch();

    // Debugging: Log the stored password retrieved from the database
    error_log("Stored Password from DB: $stored_password");

    // Compare passwords directly
    if ($password === $stored_password) {
        // Store the username in the session
        $_SESSION['username'] = $username;

        // Redirect to the welcome page
        echo "Login successful";
        exit;
    } else {
        echo "Invalid username or password. Password did not match.";
    }
} else {
    echo "Invalid username or password. Username not found.";
}

// Close connection
$stmt->close();
$conn->close();
?>
