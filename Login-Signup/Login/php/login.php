<?php
session_start();
include_once 'config.php';

$errors = array(); // Initialize an array to store errors

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['pass']);

    // Validate input
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    // Proceed only if there are no validation errors
    if (empty($errors)) {
        // Prepare and bind
        $stmt = $conn->prepare("SELECT id, `password`, `role` FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Check if the user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashedPassword, $role);
            $stmt->fetch();

            // Verify password
            if ($password == $hashedPassword) { // For production, use `password_verify($password, $hashedPassword)`
                // Password is correct, start a session
                $_SESSION['user_id'] = $id;
                $_SESSION['role'] = $role; // Store user role in session
                $_SESSION['success'] = "You are now logged in";

                // Redirect based on role
                if ($role === 'student') {
                    header("Location: http://localhost/ExamIn/student-page/index.html");
                } elseif ($role === 'admin') {
                    header("Location: http://localhost/ExamIn/teacher-page/index.html");
                } else {
                    array_push($errors, "Unknown role.");
                }
                exit();
            } else {
                array_push($errors, "Incorrect username or password.");
            }
        } else {
            array_push($errors, "User not found.");
        }

        // Close statement
        $stmt->close();
    }

    // Store errors in session and redirect back to login page
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors; 
        header("Location: http://localhost/ExamIn/Login-Signup/Login/login.php "); // Redirect back to login page
        exit();
    }

    // Close connection
    $conn->close();
}
?>
