<?php
require 'config.php'; // Include database connection

if (isset($_GET['exam_id'])) {
    $exam_id = intval($_GET['exam_id']);
    
    // Prepare and execute deletion query
    $query = "DELETE FROM exam_subjects WHERE exam_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $exam_id);
    
    if ($stmt->execute()) {
        header("Location: addExamSubjects.php?message=Timetable deleted successfully");
        exit;
    } else {
        echo "Error deleting timetable: " . $stmt->error;
        echo '<br><a href="addExamSubjects.php" class="text-blue-500 underline">Go back to Timetable</a>';
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
    echo '<br><a href="addExamSubjects.php" class="text-blue-500 underline">Go back to Timetable</a>';
}
?>
