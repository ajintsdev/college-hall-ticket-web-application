<?php
// Include database connection file
include 'config.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $admissionNumber = $_GET['id'];
    
    // Prepare the SQL DELETE statement
    $sql = "DELETE FROM hall_tickets WHERE admission_number = ?";
    
    // Prepare and bind
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $admissionNumber);
        
        // Execute the statement
        if ($stmt->execute()) {
            // If successful, delete the file from the server
            $stmt->close();
            
            // Fetch the file path to delete the PDF file from the server
            $fetchPdfQuery = "SELECT pdf_path FROM hall_tickets WHERE admission_number = ?";
            if ($stmt = $conn->prepare($fetchPdfQuery)) {
                $stmt->bind_param("s", $admissionNumber);
                $stmt->execute();
                $stmt->bind_result($pdfPath);
                $stmt->fetch();
                $stmt->close();
                
                // Delete the file if it exists
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }
            }

            // Redirect to the table page with a success message
            header("Location: generateTicket.php?message=deleted");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "No admission number specified.";
}

// Close the connection
$conn->close();
?>
