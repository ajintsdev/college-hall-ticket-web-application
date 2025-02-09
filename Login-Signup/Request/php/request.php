<?php
session_start();
include_once 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errors = array();
$success = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['fname']);
    $lastName = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $admissionNumber = trim($_POST['adm-number']);

    $checkSql = "SELECT * FROM users WHERE admission_number = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('s', $admissionNumber);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        array_push($errors, "User already exists.");
    } else {
        $username = "user" . $admissionNumber;
        $pass = bin2hex(random_bytes(2));
        $password = "BSCAS" . $pass;

        $sql = "INSERT INTO users (first_name, last_name, email, admission_number, username, `password`) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssiss', $firstName, $lastName, $email, $admissionNumber, $username, $password);

        if ($stmt->execute()) {
            $fullName = $firstName . ' ' . $lastName;
            if (sendEmail($email, $fullName, $username, $password)) {
                array_push($success, "Sign-up successful. Your credentials have been sent to your email.");
            } else {
                array_push($errors, "Sign-up successful, but there was an error sending the email.");
            }
        } else {
            array_push($errors, "Error: " . $stmt->error);
        }
        $stmt->close();
    }
    $checkStmt->close();
    $conn->close();
}

// Outputting styled divs with messages
if (!empty($errors)) {
    echo '<div class="error-text">';
    foreach ($errors as $error) {
        echo '<p>' . htmlspecialchars($error) . '</p>';
    }
    echo '</div>';
}
if (!empty($success)) {
    echo '<div class="success-text">';
    foreach ($success as $succes) {
        echo '<p>' . htmlspecialchars($succes) . '</p>';
    }
    echo '</div>';
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
