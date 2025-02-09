<?php
// Include database connection
require 'config.php';

// Check if student_id is set in the URL
if (isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']); // Convert to integer to prevent SQL injection

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);

    if ($stmt->execute()) {
        // Redirect back to the main page with a success message (optional)
        header("Location: addStudent.php?message=Student+deleted+successfully");
    } else {
        // Redirect back with an error message (optional)
        header("Location: addStudent.php?error=Unable+to+delete+student");
    }

    $stmt->close();
} else {
    // Redirect if no student_id is provided
    header("Location: addStudent.php?error=Invalid+student+ID");
}

$conn->close();
exit();
?>
