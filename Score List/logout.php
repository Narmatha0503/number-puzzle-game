<?php
session_start(); // Start the session
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session
header("Location: ../Sign in Account/Sign-in.html"); // Redirect to the login page
exit;
?>
