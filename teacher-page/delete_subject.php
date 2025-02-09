<?php
// Include database connection
include 'config.php'; // Adjust the path as needed

// Check if subject_id is provided in the URL
if (isset($_GET['id'])) {
    // Sanitize the subject_id to prevent SQL injection
    $subject_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Delete query
    $delete_query = "DELETE FROM subjects WHERE subject_id = '$subject_id'";
    
    // Execute the delete query
    if (mysqli_query($conn, $delete_query)) {
        // Redirect to the original page after deletion
        header("Location: addSubjects.php"); // Replace 'addSubjects.php' with your actual page name
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}

// Close the database connection
mysqli_close($conn);
?>
