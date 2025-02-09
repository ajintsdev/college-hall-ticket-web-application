<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "examin");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $examName = $_POST['exam_name'];
    $subjectName = $_POST['subject_name'];
    $examCode = $_POST['exam-code'];
    $examDate = $_POST['exam_date'];
    $examDuration = $_POST['exam_duration'];

    // Convert exam name to a valid MySQL table name (e.g., replace spaces with underscores)
    $examName = preg_replace('/[^a-zA-Z0-9_]/', '_', $examName);

    // Check if the table exists
    $checkTableSql = "SHOW TABLES LIKE '$examName'";
    $result = $conn->query($checkTableSql);

    if ($result->num_rows == 0) {
        // If the table does not exist, handle the error
        echo "Error: Table '$examName' does not exist!";
    } else {
        // SQL query to insert form data into the specified table
        $sql = "INSERT INTO `$examName` (subject_name, exam_code, exam_date, duration_minutes) 
                VALUES (?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("sssi", $subjectName, $examCode, $examDate, $examDuration);

            // Execute the statement
            if ($stmt->execute()) {
                echo "Record inserted successfully into '$examName' table!";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing the SQL statement: " . $conn->error;
        }
    }
}

// Close the connection
$conn->close();
?>
