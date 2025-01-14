function toggleDarkMode() {
    var body = document.body;
    body.classList.toggle("dark-mode");
    
    var button = document.getElementById("dark-mode-toggle-button");
    var isDarkMode = body.classList.contains("dark-mode");
    
    if (isDarkMode) {
        button.textContent = "Light Mode";
    } else {
        button.textContent = "Dark Mode";
    }
}

// Check local storage for dark mode preference on page load
window.onload = function() {
    const isDarkMode = JSON.parse(localStorage.getItem("darkMode"));
    if (isDarkMode) {
        document.body.classList.add("dark-mode");
    }
}
document.querySelectorAll('.expand-button').forEach(button => {
 button.addEventListener('click', () => {
const expandedInfo = button.nextElementSibling;
expandedInfo.classList.toggle('show'); // Toggle visibility
});
});


$(document).ready(function() {
    // Show registration form
    $('#show-register').click(function(e) {
        e.preventDefault();
        $('#login-form').hide();
        $('#register-form').show();
    });

    // Show login form
    $('#show-login').click(function(e) {
        e.preventDefault();
        $('#login-form').show();
        $('#register-form').hide();
    });
});

// -------------------------------
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