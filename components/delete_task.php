<?php
// Include the database connection
require_once 'database.php';

// Check if the task ID is provided
if (isset($_POST['task_id'])) {
    // Get the task ID from the POST data
    $task_id = $_POST['task_id'];

    try {
        // Call the deleteTask() function to delete the task from the database
        deleteTask($pdo, $task_id);

        // Respond with a success message
        echo "Task deleted successfully!";
    } catch (PDOException $e) {
        // If an error occurs, respond with an error message
        echo "Error deleting task: " . $e->getMessage();
    }
} else {
    // If task ID is not provided, respond with an error message
    echo "Task ID not provided!";
}
?>
