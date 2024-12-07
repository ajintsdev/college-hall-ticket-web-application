<?php
// Start the session to store messages
include 'config.php';  // Include database connection settings

// Check if the form is submitted
if (isset($_POST['submit-btn'])) {
    // Sanitize and validate form data
    $course_name = trim($_POST['course_name']);
    $course_code = trim($_POST['course_code']);

    // Initialize message variable
    $message = '';

    if (empty($course_name) || empty($course_code)) {
        $message = "Course Name and Course Code are required.";
    } else {
        // Prepare SQL query to insert the values into the Courses table
        $sql = "INSERT INTO Courses (course_name, course_code) VALUES (?, ?)";

        // Prepare and bind the SQL statement
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $course_name, $course_code);

            // Execute the query
            if ($stmt->execute()) {
                $message = "Course added successfully!";
            } else {
                $message = "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            $message = "Error preparing the query.";
        }
    }

    // Close connection
    $conn->close();

    // Display the message
    echo "<div class='message'>$message</div>";
}
?>

