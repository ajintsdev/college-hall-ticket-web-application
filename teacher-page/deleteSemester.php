<?php
// Start the session (if needed)
session_start();

// Include database connection file
include 'config.php'; // Make sure to change this to your actual database connection file

// Check if the semester_id is set in the URL
if (isset($_GET['semester_id'])) {
    // Get the semester_id from the URL
    $semester_id = intval($_GET['semester_id']); // Sanitize the input

    // Prepare the SQL DELETE statement
    $sql = "DELETE FROM semesters WHERE semester_id = ?";
    
    // Create a prepared statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameter
        $stmt->bind_param("i", $semester_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Success message
            $_SESSION['message'] = "Semester deleted successfully.";
        } else {
            // Error message
            $_SESSION['message'] = "Error deleting semester: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error preparing the statement
        $_SESSION['message'] = "Error preparing statement: " . $conn->error;
    }
} else {
    // Error: semester_id not set
    $_SESSION['message'] = "Invalid request.";
}

// Close the database connection
$conn->close();

// Redirect back to the previous page (or any page you want)
header("Location: addSemester.php"); // Change this to your desired redirect page
exit();
?>