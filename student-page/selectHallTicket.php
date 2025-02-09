<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "examin");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available exams for the dropdown
$examsQuery = "SELECT exam_id, exam_name FROM exams";
$examsResult = $conn->query($examsQuery);

// Initialize variables
$selectedExamId = 0;
$admissionNumber = '';
$error = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedExamId = intval($_POST['exam_id']);
    $admissionNumber = $conn->real_escape_string($_POST['admission_number']);

    // Fetch student details based on admission number
    $studentQuery = "SELECT * FROM students WHERE admission_number = '$admissionNumber'";
    $studentResult = $conn->query($studentQuery);

    // Check for errors in the query
    if (!$studentResult) {
        die("Query failed: " . $conn->error);
    }

    $student = $studentResult->fetch_assoc();

    if ($student) {
        // Redirect to the hall ticket page
        header("Location: hallTicket.php?student_id=" . $student['student_id'] . "&exam_id=" . $selectedExamId);
        exit();
    } else {
        $error = "Invalid admission number.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="style.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></link>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <script type="text/javascript" src="app.js" defer></script>
  </head>
  <body>
  <nav id="sidebar">
      <ul>
        <li>
          <span class="logo">ExamIn</span>

          <button onclick="toggleSidebar()" id="toggle-btn">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="24px"
              viewBox="0 -960 960 960"
              width="24px"
              fill="#5f6368"
            >
              <path
                d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"
              />
            </svg>
          </button>
        </li>
        <li >
          <a href="index.html">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="24px"
              viewBox="0 -960 960 960"
              width="24px"
              fill="#5f6368"
            >
              <path
                d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"
              />
            </svg>
            <span>Home</span>
          </a>
        </li>

        
       
        <li class="active">
          <a href="selectHallTicket.php">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="24px"
              viewBox="0 -960 960 960"
              width="24px"
              fill="#5f6368"
            >
              <path
                d="M320-240h320v-80H320v80Zm0-160h320v-80H320v80ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"
              />
            </svg>
          
            <span>Hall Ticket</span>
          </a>
        </li>
        <li>
        <a href="../Login-Signup/Login/login.php">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="24px"
              viewBox="0 -960 960 960"
              width="24px"
              fill="#5f6368"
            >
              <path
                d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"
              />
            </svg>
            <span>Logout</span>
          </a>
        </li>
      </ul>
    </nav>
   <main>
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Generate Hall Ticket</h1>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="dropdown" class="block text-gray-700 text-sm font-bold mb-2">Select Exam:</label>
                <select id="dropdown" name="exam_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Select an exam</option>
                    <?php while ($exam = $examsResult->fetch_assoc()): ?>
                        <option value="<?php echo $exam['exam_id']; ?>"><?php echo $exam['exam_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="inputBox" class="block text-gray-700 text-sm font-bold mb-2">Admission Number:</label>
                <input type="text" id="inputBox" name="admission_number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter admission number" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-[#9b00ca] hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Submit
                </button>
            </div>
        </form>
    </div>
    </main>
</body>
</html>

<?php
$conn->close();
?>