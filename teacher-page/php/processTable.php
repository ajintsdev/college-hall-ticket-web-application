<?php 
// Database connection
$conn = new mysqli("localhost", "root", "", "examin");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $examName = $_POST['exam_name'];
    $maxRows = $_POST['max_rows'];

    // Convert exam name to a valid MySQL table name (replace spaces and invalid characters with underscores)
    $examName = preg_replace('/[^a-zA-Z0-9_]/', '_', $examName);

    // Check if the table already exists
    $checkTableSql = "SHOW TABLES LIKE '$examName'";
    $result = $conn->query($checkTableSql);

    if ($result->num_rows > 0) {
        echo "Error: Table '$examName' already exists!";
    } else {
        // SQL to create the table
        $sql = "CREATE TABLE `$examName` (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            student_name VARCHAR(255) NOT NULL,
            student_score INT(11),
            exam_date DATE,
            duration_minutes INT(11)
        )";

        // Execute the query
        if (!$conn->query($sql) === TRUE){
            echo "Error creating table: " . $conn->error;
        }
    }
}

$conn->close();
?>
