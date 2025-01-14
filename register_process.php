<!--
This file register_process.php contain the register stuffs and when user rigester 
that will put the username and password in database and handle this things
Suraj Sedai
-->
<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Perform your registration logic here
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Save user information to a database or file
    // You should also perform validation and hashing of passwords
    
    // For demonstration purposes, let's just store in session
    $_SESSION['registered_users'][$username] = $password;

    // Redirect to login page after registration
    header("Location: login.php");
    exit();
}
?>
