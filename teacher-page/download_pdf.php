<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "examin");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student_id and exam_id from URL parameters
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;
$exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 0;

if ($student_id === 0 || $exam_id === 0) {
    die("Invalid student or exam selection.");
}

// Fetch the file path of the generated PDF from the database
$query = "SELECT pdf_path FROM hall_tickets WHERE admission_number = (SELECT admission_number FROM students WHERE student_id = $student_id)";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pdfFilePath = $row['pdf_path'];

    // Check if the PDF file exists
    if (file_exists($pdfFilePath)) {
        // Set headers to force download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($pdfFilePath) . '"');
        header('Content-Length: ' . filesize($pdfFilePath));
        readfile($pdfFilePath);
        exit;
    } else {
        echo "Error: PDF file not found.";
    }
} else {
    echo "Error: Hall ticket not found in the database.";
}

$conn->close();
?>
