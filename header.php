<!--
This file header.php contain the header element of the main page
also it contain the login and registation stuffs in it and all the database to handle 
that things
Suraj Sedai
-->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start PHP session if not already started
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle registration
if(isset($_POST['register'])) {
    $reg_username = $_POST['reg_username'];
    $reg_password = $_POST['reg_password'];

    // Hash the password for security
    $hashed_password = password_hash($reg_password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (username, password) VALUES ('$reg_username', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle login
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user data from database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User exists, verify password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, set session variables
            $_SESSION['username'] = $username;
            echo "Login successful!";
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User does not exist!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Task Tracker</title>
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="Task Tracker Logo">
        </div>
        <nav>
            
                <button id="dark-mode-toggle-button" onclick="toggleDarkMode()">Light Mode</button>

            
            <?php 
            if (!isset($_SESSION['username'])): ?>
            <div class="login-form" id="login-form">
                <h2>Login</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="text" id="username" placeholder="Username" name="username" required><br>
                    <input type="password" id="password" placeholder="Password" name="password" required><br>
                    <input type="submit" value="Login" name="login">
                </form>
                <p style="font-size: 12px;">Don't have an account? <a href="#" id="show-register">Register</a></p>
            </div>
            <div class="register-form" id="register-form" style="display: none;">
                <h2>Register</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="text" id="reg-username" placeholder="Username" name="reg_username" required><br>
                    <input type="password" id="reg-password" placeholder="Password" name="reg_password" required><br>
                    <input type="submit" value="Register" name="register">
                </form>
                <p style="font-size: 12px;">Already have an account? <a href="#" id="show-login">Login</a></p>
            </div>
            <?php else: ?>
            <div class="user-info">
                <p>Welcome, <?php echo $_SESSION['username']; ?>!</p><br>
                <a href="logout.php">Logout</a>
            </div>
            <?php endif; ?>
        </nav>
    </header>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
