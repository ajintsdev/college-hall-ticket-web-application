<?php
session_start();
include 'config.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $exam_name = mysqli_real_escape_string($conn, $_POST['exam_name']);
    $course_id = intval($_POST['course_id']);
    $semester_id = intval($_POST['semester_id']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // SQL query to insert data
    $sql = "INSERT INTO Exams (exam_name, course_id, semester_id, start_date, end_date) 
            VALUES ('$exam_name', '$course_id', '$semester_id', '$start_date', '$end_date')";

    // Execute query and set success or error message
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Exam created successfully!";
    } else {
        $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);

    // Redirect back to the form page
    header("Location: ../addExam.php"); // Replace with the actual path to your form page
    exit();
}
?>
