<!--Spring2024
Will Briggs
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
<script>
    $(document).ready(function() {
        $('#searchForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            // Get search query
            var searchQuery = $('#searchQuery').val();

            // Send AJAX request to search_tasks.php
            $.ajax({
                url: 'search_tasks.php',
                type: 'POST',
                data: { searchQuery: searchQuery },
                success: function(response) {
                    // Update task list with search results
                    $('#taskList').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error searching tasks. Please try again.');
                }
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        const tasks = document.querySelectorAll(".task-box");

        tasks.forEach(task => {
            task.addEventListener("dragstart", dragStart);
            task.addEventListener("dragover", dragOver);
            task.addEventListener("drop", drop);
            task.addEventListener("dragend", dragEnd);
        });
    });

    let draggedTask = null;

    function dragStart(event) {
        draggedTask = event.target;
        event.dataTransfer.setData("text/plain", ""); // Required for Firefox to initiate drag
        event.target.classList.add("dragging");

        // Store the original position of the dragged task
        draggedTask.originalIndex = Array.from(draggedTask.parentNode.children).indexOf(draggedTask);
    }

    function dragOver(event) {
        event.preventDefault();
    }

    function drop(event) {
        event.preventDefault();

        // Determine the current position of the dragged task
        const currentIndex = Array.from(event.target.parentNode.children).indexOf(event.target);

        // Insert the dragged task at its original position
        if (currentIndex !== draggedTask.originalIndex) {
            event.target.parentNode.insertBefore(draggedTask, event.target);
        }
    }

    function dragEnd(event) {
        event.target.classList.remove("dragging");
        draggedTask = null;
    }


    $(document).ready(function() {
    // Handle form submission via AJAX
    $('#addTaskForm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission

        // Get form data
        var formData = $(this).serialize();

        // Send AJAX request to insert_task.php
        $.ajax({
            url: 'insert_task.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Display success message or handle response as needed
                // Reload the task list
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Error adding task. Please try again.');
            }
        });
    });
});


    // JavaScript function to delete a task
    function deleteTask(taskId) {
        $.ajax({
            url: 'delete_task.php',
            type: 'POST',
            data: { task_id: taskId },
            success: function(response) {
                // Reload the page after successful deletion
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Error deleting task. Please try again.');
            }
        });
    }
</script>

</body>
</html>
