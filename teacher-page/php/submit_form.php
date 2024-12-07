<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "examin";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["submit-btn"])) {
    // Initialize error messages
    $error_messages = [];

    // Retrieve data from the form
    $admission_number = $conn->real_escape_string($_POST['admission_number']);
    $dob = $conn->real_escape_string($_POST['date_of_birth']);
    $batch = $conn->real_escape_string($_POST['batch']);
    $year_of_study = $conn->real_escape_string($_POST['year_of_study']);
    $course = $conn->real_escape_string($_POST['course']);

    // Server-side validation
    if (empty($admission_number)) {
        $error_messages[] = "Admission number is required.";
    }
    if (empty($dob)) {
        $error_messages[] = "Date of birth is required.";
    }
    if (empty($batch)) {
        $error_messages[] = "Batch is required.";
    }
    if (empty($year_of_study) || $year_of_study < 1 || $year_of_study > 3) {
        $error_messages[] = "Year of study must be between 1 and 3.";
    }
    if (empty($course)) {
        $error_messages[] = "Course is required.";
    }

    // Check if there are any validation errors
    if (!empty($error_messages)) {
        $_SESSION['messages'] = $error_messages; // Store errors in session
        header("Location: addStudent.php"); // Redirect to addStudent.php
        exit;
    }

    // Check if the photo is uploaded
    if (isset($_FILES['photo'])) {
        $img_name = $_FILES['photo']['name'];
        $img_type = $_FILES['photo']['type'];
        $tmp_name = $_FILES['photo']['tmp_name'];

        $img_explode = explode('.', $img_name);
        $img_ext = end($img_explode);

        $allowed_extensions = ["jpeg", "png", "jpg"];
        if (in_array($img_ext, $allowed_extensions)) {
            $allowed_types = ["image/jpeg", "image/jpg", "image/png"];
            if (in_array($img_type, $allowed_types)) {
                $new_img_name = time() . "_" . $img_name; // Added underscore for better file name
                $upload_path = "../images/" . $new_img_name;

                if (move_uploaded_file($tmp_name, $upload_path)) {
                    // Insert data into the student_information table
                    $sql = "INSERT INTO student_information (user_id, admission_number, photo, date_of_birth, batch, year_of_study, course) 
                            VALUES (NULL, '$admission_number', '$new_img_name', '$dob', '$batch', '$year_of_study', '$course')";

                    if ($conn->query($sql) === TRUE) {
                        $_SESSION['messages'] = ["Student record inserted successfully!"];
                    } else {
                        $_SESSION['messages'] = ["Error: " . $sql . "<br>" . $conn->error];
                    }
                } else {
                    $_SESSION['messages'] = ["Failed to upload the image."];
                }
            } else {
                $_SESSION['messages'] = ["Please upload a valid image file (jpeg, png, jpg)."];
            }
        } else {
            $_SESSION['messages'] = ["Invalid image extension. Allowed types are jpeg, png, jpg."];
        }
    } else {
        $_SESSION['messages'] = ["Please upload an image."];
    }

    header("Location: addStudent.php"); // Redirect to addStudent.php
    exit;
}

$conn->close();
?>
