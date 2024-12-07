<?php
// Database connection parameters
include 'config.php';

// Check if exam_id is set in the URL
if (isset($_GET['exam_id'])) {
    $exam_id = intval($_GET['exam_id']); // Sanitize input

    // Prepare SQL statement to delete the exam
    $sql = "DELETE FROM exams WHERE exam_id = ?";
    
    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $exam_id);

    // Execute the statement
    if ($stmt->execute()) {
        $message = "Exam deleted successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    $message = "No exam ID provided.";
}

// Close the database connection
$conn->close();

// Redirect back to the exam schedule page with a message
header("Location: addExam.php?message=" . urlencode($message));
exit();
?>