<?php
// checkExam.php

// Database connection
$conn = new mysqli("localhost", "root", "", "examin");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student_id and exam_id from GET parameters
$exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 0;

$response = ["hasSubjects" => false]; // Default response

if ($exam_id > 0) {
    // Check if there are subjects for the selected exam
    $examSubjectsQuery = "SELECT COUNT(*) as count FROM exam_subjects WHERE exam_id = $exam_id";
    $examSubjectsResult = $conn->query($examSubjectsQuery);
    $examSubjectsCount = $examSubjectsResult->fetch_assoc()["count"];

    // If there are subjects, set hasSubjects to true
    if ($examSubjectsCount > 0) {
        $response["hasSubjects"] = true;
    }
}

echo json_encode($response);
$conn->close();
