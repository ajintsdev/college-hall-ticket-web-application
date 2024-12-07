<?php
session_start();
include_once 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errors = []; // Array to store error messages
$success = []; // Array to store success messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $firstName = trim($_POST['fname']);
    $lastName = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $admissionNumber = trim($_POST['adm-number']);

    // Check if the admission number already exists
    $checkSql = "SELECT * FROM users WHERE admission_number = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('s', $admissionNumber);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "User already exists."; // Add error message to array
    } else {
        // Generate a username using the admission number
        $username = "user" . $admissionNumber;

        // Generate a random password
        $pass = bin2hex(random_bytes(2)); // 2 bytes = 4 characters
        $password = "BSCAS" . $pass;

        // Store user data in the database without hashing as specified
        $sql = "INSERT INTO users (first_name, last_name, email, admission_number, username, `password`) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssiss', $firstName, $lastName, $email, $admissionNumber, $username, $password);

        if ($stmt->execute()) {
            // Create the full name
            $fullName = $firstName . ' ' . $lastName;

            // Attempt to send the username and password to the email using PHPMailer
            if (sendEmail($email, $fullName, $username, $password)) {
                $success[] = "Sign-up successful. Your credentials have been sent to your email.";
            } else {
                $errors[] = "Sign-up successful, but there was an error sending the email.";
            }
        } else {
            $errors[] = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $checkStmt->close();
    $conn->close();

    // Store errors and success messages in session and redirect back to the form
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }
    if (!empty($success)) {
        $_SESSION['success'] = $success;
    }

}

// Function to send an email using PHPMailer
function sendEmail($recipientEmail, $fullName, $username, $password) {
    require '../vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ajintsdev@gmail.com';
        $mail->Password   = 'oydpmnfwpexrqhxa';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('ajintsdev@gmail.com', 'Exam In');
        $mail->addAddress($recipientEmail);

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
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
