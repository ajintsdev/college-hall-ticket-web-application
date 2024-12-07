<?php
// Include PHPMailer for email functionality
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include database connection
include_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $firstName = trim($_POST['fname']);
    $lastName = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $admissionNumber = trim($_POST['adm-number']);
    // $contactNumber = trim($_POST['contact-number']);

    // Check if the admission number already exists
    $checkSql = "SELECT * FROM users WHERE admission_number = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('s', $admissionNumber);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // User already exists
        $errorMessage = "user already exists.";
        echo json_encode(['error' => true, 'message' => $errorMessage]);
    } else {
        // Generate a username using the admission number
        $username = "user" . $admissionNumber;

        // Generate a random password
        $pass = bin2hex(random_bytes(2)); // 2 bytes = 4 characters
        $password = "BSCAS" . $pass;

        // Hash the password before storing it
        // $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Store user data in the database
        $sql = "INSERT INTO users (first_name, last_name, email, admission_number, username, `password`) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssiss', $firstName, $lastName, $email, $admissionNumber, $username, $password);


        if ($stmt->execute()) {
        // Create the full name
        $fullName = $firstName . ' ' . $lastName;

        // Send username and password to the email using PHPMailer
        if (sendEmail($email, $fullName, $username, $password)) {
        // Respond with success
        echo json_encode(['error' => false, 'message' => "Sign-up successful. Your credentials have been sent to your email."]);
        } else {
        // Handle email sending error
        echo json_encode(['error' => true, 'message' => "Sign-up successful, but there was an error sending the email."]);
        }
        } else {
        echo json_encode(['error' => true, 'message' => "Error: " . $stmt->error]);
        }
        // Close statement
        $stmt->close();
    }

    // Close the connection
    $checkStmt->close();
    $conn->close();
}
// Function to send an email using PHPMailer
function sendEmail($recipientEmail, $fullName, $username, $password) {
    require '../vendor/autoload.php'; // Make sure PHPMailer is installed via Composer

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ajintsdev@gmail.com'; // SMTP username
        $mail->Password   = 'oydpmnfwpexrqhxa';    // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable implicit TLS encryption
        $mail->Port       = 587; 

        // Recipients
        $mail->setFrom('ajintsdev@gmail.com', 'Exam In');
        $mail->addAddress($recipientEmail);  // Add recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Account Credentials';
        $mail->Body    = "
            <h3>Welcome, {$fullName}!</h3>
            <p>Your account has been created successfully. Below are your login details:</p>
            <p><strong>Username:</strong> {$username}</p>
            <p><strong>Password:</strong> {$password}</p>
            <p>Login here: <a href='../../Login/index.html'>Login</a></p>
        ";

        $mail->send();
        return true; // Return true if email sent successfully
    } catch (Exception $e) {
        // Log or handle the error appropriately, but do not echo
        return false; // Return false if there was an error sending the email
    }
}

?>