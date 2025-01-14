<?php
require_once 'database.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $dueDate = $_POST['due_date'];

    try {
        // Prepare and execute the SQL statement to insert a new task
        $stmt = $pdo->prepare("INSERT INTO tasks (title, due_date) VALUES (:title, :due_date)");
        $stmt->execute(array(':title' => $title, ':due_date' => $dueDate));

        // Return success message
        echo "Task added successfully!";
    } catch (PDOException $e) {
        // Return error message if insertion fails
        echo "Error adding task: " . $e->getMessage();
    }
}
?>
