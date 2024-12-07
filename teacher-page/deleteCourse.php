<?php
include 'config.php'; // Include your database connection file

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    // Sanitize the course ID
    $course_id = intval($_GET['id']);

    // Prepare the SQL DELETE statement
    $delete_sql = "DELETE FROM Courses WHERE course_id = ?";

    if ($stmt = $conn->prepare($delete_sql)) {
        $stmt->bind_param("i", $course_id);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to the main page with a success message
            echo "<script>alert('Course deleted successfully!'); window.location.href='addCourse.php';</script>";
        } else {
            // Error message if the deletion fails
            echo "<script>alert('Error: Could not delete the course.'); window.location.href='addCourse.php';</script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing the delete query.'); window.location.href='addCourse.php';</script>";
    }
} else {
    // Redirect to the main page if no ID is found in the URL
    echo "<script>alert('Invalid course ID.'); window.location.href='addCourse.php';</script>";
}

// Close the database connection
$conn->close();
?>
