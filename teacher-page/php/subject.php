<?php
// Include database connection file
include('config.php'); // Ensure you have the correct path for your db connection

// Check if the form is submitted
if (isset($_POST['submit-btn'])) {
    // Get the form data
    $subject_name = mysqli_real_escape_string($conn, $_POST['subject_name']);
    $course_id = (int)$_POST['course_id'];
    $semester_id = (int)$_POST['semester_id'];

    // SQL query to insert data into the Subjects table
    $query = "INSERT INTO Subjects (subject_name, course_id, semester_id) VALUES ('$subject_name', $course_id, $semester_id)";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo "Subject added successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>
