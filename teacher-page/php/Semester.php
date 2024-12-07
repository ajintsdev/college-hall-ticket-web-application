<?php
// Include database connection
include 'config.php'; // Ensure this file contains the DB connection

if (isset($_POST['submit-btn'])) {
    // Capture form data
    $semester_name = $_POST['semester_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['End_date'];

    // Validation
    $errors = [];

    if (empty($semester_name)) {
        $errors[] = "Semester name is required.";
    }
    if (empty($start_date)) {
        $errors[] = "Start date is required.";
    }
    if (empty($end_date)) {
        $errors[] = "End date is required.";
    }
    if ($start_date && $end_date && $start_date > $end_date) {
        $errors[] = "Start date cannot be later than end date.";
    }

    // If there are validation errors
    if (!empty($errors)) {
        echo '<div class="error-text">' . implode('<br>', $errors) . '</div>';
    } else {
        // Insert into the database if no errors
        $stmt = $conn->prepare("INSERT INTO Semesters (semester_name, `start_date`, end_date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $semester_name, $start_date, $end_date);

        if ($stmt->execute()) {
            echo '<div class="success-text">Semester added successfully!</div>';
        } else {
            echo '<div class="error-text">Failed to add semester. Please try again.</div>';
        }

        $stmt->close();
    }
}

// Close DB connection
$conn->close();
?>
