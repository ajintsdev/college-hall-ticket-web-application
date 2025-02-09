<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "examin");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student_id and exam_id from URL parameters
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;
$exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 0;

if ($student_id === 0 || $exam_id === 0) {
    die("Invalid student or exam selection.");
}


// Fetch student details
$studentQuery = "SELECT * FROM students WHERE student_id = $student_id";
$studentResult = $conn->query($studentQuery);
$student = $studentResult->fetch_assoc();

// Fetch semester name
$semesterQuery = "SELECT semester_name FROM semesters WHERE semester_id = " . $student['semester_id'];
$semesterResult = $conn->query($semesterQuery);
$semester = $semesterResult->fetch_assoc();

// Fetch course name
$courseQuery = "SELECT course_name FROM courses WHERE course_id = " . $student['course_id'];
$courseResult = $conn->query($courseQuery);
$course = $courseResult->fetch_assoc();

// Fetch exam and subject details
$examQuery = "SELECT e.exam_name, es.exam_date, s.subject_name, es.start_time, es.end_time
              FROM exam_subjects es
              JOIN exams e ON es.exam_id = e.exam_id
              JOIN subjects s ON es.subject_id = s.subject_id
              WHERE e.exam_id = $exam_id AND es.exam_id = $exam_id";
// Fetch exam and subject details
$examResult = $conn->query($examQuery);
if (!$examResult) {
    die("Query failed: " . $conn->error);
}

// Store the first row for exam name
$examDetails = $examResult->fetch_assoc();
if (!$examDetails) {
    die("Exam details not found.");
}
include 'header.php';
?>

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
        <li>
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

    <div class="container max-w-3xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-[#9b00ca] text-white text-center py-4">
            <h1 class="text-2xl font-bold">Exam Hall Ticket</h1>
        </div>
        <div class="p-6">
            <div class="flex items-center mb-4">
                <img src="<?php echo $student['photo_path']; ?>" alt="Student's profile picture" class="w-24 h-24 rounded-full mr-4">
                <div>
                    <h2 class="text-xl font-bold"><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></h2>
                    <p class="text-gray-600">PRN: <?php echo $student['admission_number']; ?></p>
                    <p class="text-gray-600">Class: <?php echo $student['batch']; ?></p>
                    <p class="text-gray-600">Semester: <?php echo $semester['semester_name']; ?> </p>
                    <p class="text-gray-600">Course: <?php echo $course['course_name']; ?></p>
                </div>
            </div>
            <div class="mb-4">
                <h3 class="text-lg font-bold mb-2">Exam Details</h3>
                <p class="text-gray-600">Exam Name: <?php echo $examDetails['exam_name']; ?>  </p>
                <p class="text-gray-600">Exam Center: Bishop Speechly College for Advanced Studies </p>
                <p class="text-gray-600">Center Code: 7890</p>
                <p class="text-gray-600">Center Place: Pallom</p>
            </div>
            <div>
                <h3 class="text-lg font-bold mb-2">Time Table</h3>
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Date</th>
                            <th class="py-2 px-4 border-b">Subject</th>
                            <th class="py-2 px-4 border-b">Time</th>
                            <th class="py-2 px-4 border-b">Invigilator's <br> Sign </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Now, we can loop through the remaining rows
                        do {
                            // Display the timetable for each subject
                            echo '<tr>';
                            echo '<td class="py-2 px-4 border-b">' . date("d/m/Y", strtotime($examDetails['exam_date'])) . '</td>';
                            echo '<td class="py-2 px-4 border-b">' . $examDetails['subject_name'] . '</td>';
                            echo '<td class="py-2 px-4 border-b">' . date("h:i A", strtotime($examDetails['start_time'])) . ' - ' . date("h:i A", strtotime($examDetails['end_time'])) . '</td>';
                            echo '<td class="py-2 px-4 border-b"> </td>';
                            echo '</tr>';
                        } while ($examDetails = $examResult->fetch_assoc()); // Fetch the next row
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Regulations Section -->
    <div class="p-6 bg-gray-100" >
        <h2 class="text-lg font-bold mb-2">Regulations</h2>
        <ul class="list-disc list-inside text-gray-600">
            <li>Students must arrive at the exam center at least 30 minutes before the exam starts.</li>
            <li>Students must bring a valid ID along with this hall ticket.</li>
            <li>Electronic devices are not allowed in the exam hall.</li>
            <li>Students should maintain silence and discipline during the exam.</li>
            <li>Students are not allowed to leave the exam hall until the exam is over.</li>
            <li>Any form of malpractice will lead to disqualification.</li>
            <li>Students must follow the instructions given by the invigilators.</li>
            <li>Students are not allowed to carry any study materials or notes into the exam hall.</li>
        </ul>
    </div>
    <div class="text-center py-4">
      <a href="download_pdf.php?student_id=<?php echo $student_id; ?>&exam_id=<?php echo $exam_id; ?>" class="bg-[#9b00ca] text-white mt-3 py-2 px-4 rounded inline-block">Download PDF</a>
    </div>
</div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
