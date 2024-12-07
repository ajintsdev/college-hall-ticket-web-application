<?php
session_start();
include_once 'php/config.php'; // Ensure this path is correct
$errors = array(); // Initialize an array to store errors

// Display errors if there are any
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']); // Clear errors after displaying
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Log In</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
</head>
<body>
    <div class="wrapper">
        <section class="form signup">
            <?php include('C:\wamp64\www\ExamIn\Login-Signup\Login\php\errors.php'); ?>
            <header>Exam In</header>
            <form id="login-form" method="POST" action="php/login.php" autocomplete="off">
                <div class="field input">
                    <label>Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter Your Username" />
                </div>

                <div class="field input">
                    <label>Password</label>
                    <input type="password" name="pass" id="pass" placeholder="Enter Password" />
                    <i class="fas fa-eye"></i>
                </div>

                <div class="field button">
                    <input type="submit" name="submit" value="Log In" />
                </div>
            </form>
            <div class="link">
                New Here? <a href="../Request/request.html">Signup now</a>
            </div>
        </section>
    </div>

    <script src="javascript/pass-show-hide.js"></script>
</body>
</html>
