<!--
This file index.php contain the main element of the main page
also have different function to peform the database stuffs, including deleteting, also 
it looks for all other things except header and footer
Suraj Sedai
-->
<?php
session_start();

// Database connection parameters
$host = "localhost";
$dbname = "task_database";
$username = "root";
$password = "";

try {
    // Create a PDO instance to establish a database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode to throw exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Display error message if connection fails
    die("Error: " . $e->getMessage());
}

// Function to retrieve tasks from the database
function getTasks($pdo) {
    $stmt = $pdo->query("SELECT * FROM tasks");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to insert a new task into the database
function insertTask($pdo, $title, $due_date) {
    $stmt = $pdo->prepare("INSERT INTO tasks (title, due_date) VALUES (:title, :due_date)");
    $stmt->execute(array(':title' => $title, ':due_date' => $due_date));
}

// Function to delete a task from the database
function deleteTask($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute(array(':id' => $id));
}

// Handle adding a new task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['due_date'])) {
    // Get form data
    $title = $_POST['title'];
    $due_date = $_POST['due_date'];

    // Call the insertTask function
    insertTask($pdo, $title, $due_date);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Tracker</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .dragging {
            opacity: 0.5;
        }
    </style>
</head>
<body>

<header>
    <?php require_once 'header.php'; ?>
</header>

<main>
    <div class="container">

        <h2>Add Task</h2>
        <form id="addTaskForm" method="post">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" required><br>
            <label for="due_date">Due Date:</label><br>
            <input type="date" id="due_date" name="due_date" required><br>
            <input type="submit" value="Add Task">
        </form>

        <?php foreach (getTasks($pdo) as $task): ?>
            <div class="task-box" draggable="true">
                <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                <p>Due Date: <?php echo htmlspecialchars($task['due_date']); ?></p>
                <button class="delete-button" onclick="deleteTask(<?php echo $task['id']; ?>)">Delete</button>
            </div>
        <?php endforeach; ?>
    </div>
</main>
<?php require_once 'footer.php'; ?>
<script src="script.js"></script>

</body>
</html>
