<!--Spring2024
Will Briggs
This file database.php handle the insert task data, whether to put it on database or to delete it
Suraj Sedai
-->
<?php
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
    // Display success message after adding the task
    echo "<h2>Task Added Successfully</h2>";
}

// Function to delete a task from the database
function deleteTask($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute(array(':id' => $id));
}
