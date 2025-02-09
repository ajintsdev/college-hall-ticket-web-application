<?php
require 'vendor/autoload.php'; // Make sure to adjust the path if needed

use Dompdf\Dompdf;
use Dompdf\Options;

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
$examResult = $conn->query($examQuery);

// Check for exam details
if (!$examResult) {
    die("Query failed: " . $conn->error);
}

// Store the first row for exam name
$examDetails = $examResult->fetch_assoc();
if (!$examDetails) {
    die("Exam details not found.");
}

// Start output buffering
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Exam Hall Ticket</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { max-width: 800px; margin: auto; background: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 8px; overflow: hidden; }
        .header { background-color: #9b00ca; color: white; text-align: center; padding: 15px; }
        .header h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .student-info { display: flex; align-items: center; padding: 20px; font-size: 16px; color: #333; }
        .student-info img { width: 80px; height: 80px; border-radius: 50%; margin-right: 15px; }
        .student-info h2 { font-size: 20px; font-weight: bold; margin: 0; }
        .student-info p { margin: 5px 0; }
        .exam-details, .time-table, .regulations { padding: 20px; font-size: 16px; color: #333; }
        h3 { font-size: 18px; font-weight: bold; margin-top: 20px; }
        .exam-details p, .student-info p { margin: 5px 0; }
        .time-table table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .time-table th, .time-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .time-table th { background-color: #f2f2f2; font-weight: bold; }
        .regulations { background-color: #f9f9f9; padding: 15px; border-radius: 8px; }
        .regulations ul { list-style: disc; padding-left: 20px; }
        .regulations li { margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Exam Hall Ticket</h1>
        </div>
        <div class="student-info">
            <img src="path_to_student_image.jpg" alt="Student Photo">
            <div>
                <h2><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></h2>
                <p>PRN: <?php echo $student['admission_number']; ?></p>
                <p>Class: <?php echo $student['batch']; ?></p>
                <p>Semester: <?php echo $semester['semester_name']; ?></p>
                <p>Course: <?php echo $course['course_name']; ?></p>
            </div>
        </div>
        <div class="exam-details">
            <h3>Exam Details</h3>
            <p>Exam Name: <?php echo $examDetails['exam_name']; ?></p>
            <p>Exam Center: Bishop Speechly College for Advanced Studies</p>
            <p>Center Code: 7890</p>
            <p>Center Place: Pallom</p>
        </div>
        <div class="time-table">
            <h3>Time Table</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Subject</th>
                        <th>Time</th>
                        <th>Invigilator's Sign</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    do {
                        echo '<tr>';
                        echo '<td>' . date("d/m/Y", strtotime($examDetails['exam_date'])) . '</td>';
                        echo '<td>' . $examDetails['subject_name'] . '</td>';
                        echo '<td>' . date("h:i A", strtotime($examDetails['start_time'])) . ' - ' . date("h:i A", strtotime($examDetails['end_time'])) . '</td>';
                        echo '<td></td>';
                        echo '</tr>';
                    } while ($examDetails = $examResult->fetch_assoc());
                    ?>
                </tbody>
            </table>
        </div>
        <div class="regulations">
            <h3>Regulations</h3>
            <ul>
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
    </div>
</body>
</html>




<?php
// Capture the output and clean the buffer
$html = ob_get_clean();

// Initialize DOMPDF
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

// Load HTML content
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the PDF
$dompdf->render();

// Stream the PDF with the custom filename as the admission number
$dompdf->stream($student['admission_number'] . "_hall_ticket.pdf", ["Attachment" => true]);


// Close the database connection
$conn->close();
?>