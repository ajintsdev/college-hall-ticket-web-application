<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Database connection
$conn = new mysqli("localhost", "root", "", "examin");
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

if (!$student) {
    die("Student not found.");
}

// Fetch semester, course, and exam details
$semesterQuery = "SELECT semester_name FROM semesters WHERE semester_id = " . $student['semester_id'];
$semesterResult = $conn->query($semesterQuery);
$semester = $semesterResult->fetch_assoc();

$courseQuery = "SELECT course_name FROM courses WHERE course_id = " . $student['course_id'];
$courseResult = $conn->query($courseQuery);
$course = $courseResult->fetch_assoc();

$examQuery = "SELECT e.exam_name, es.exam_date, s.subject_name, es.start_time, es.end_time
              FROM exam_subjects es
              JOIN exams e ON es.exam_id = e.exam_id
              JOIN subjects s ON es.subject_id = s.subject_id
              WHERE e.exam_id = $exam_id";
$examResult = $conn->query($examQuery);

if (!$examResult || $examResult->num_rows == 0) {
    die("Exam details not found.");
}

// Capture HTML output
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: auto; background: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 8px; overflow: hidden; }
        .header { background-color: #9b00ca; color: white; text-align: center; padding: 15px; }
        .header h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .student-info, .exam-details, .time-table, .regulations { padding: 20px; font-size: 16px; color: #333; }
        .student-info img { width: 80px; height: 80px; border-radius: 50%; margin-right: 15px; }
        h2, h3 { margin: 0; font-size: 20px; font-weight: bold; }
        p { margin: 5px 0; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; font-weight: bold; }
        .regulations ul { list-style: disc; padding-left: 20px; }
        .regulations li { margin-bottom: 5px; }
    </style>
    <title>Hall Ticket</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Exam Hall Ticket</h1>
        </div>
        <div class="student-info">
            <div style="display: flex; align-items: center;">
                <img src="<?php echo $student['photo_path']; ?>" alt="Student's profile picture" width=200 height=250>
                <div>
                    <h2><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></h2>
                    <p>PRN: <?php echo $student['admission_number']; ?></p>
                    <p>Class: <?php echo $student['batch']; ?></p>
                    <p>Semester: <?php echo $semester['semester_name']; ?></p>
                    <p>Course: <?php echo $course['course_name']; ?></p>
                </div>
            </div>
        </div>
        <div class="exam-details">
            <h3>Exam Details</h3>
            <p>Exam Name: <?php echo $examResult->fetch_assoc()['exam_name']; ?></p>
            <p>Exam Center: Bishop Speechly College for Advanced Studies</p>
            <p>Center Code: 7890</p>
            <p>Center Place: Pallom</p>
        </div>
        <div class="time-table">
            <h3>Time Table</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Subject</th>
                        <th>Time</th>
                        <th>Invigilator's Sign</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($exam = $examResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date("d/m/Y", strtotime($exam['exam_date'])); ?></td>
                            <td><?php echo $exam['subject_name']; ?></td>
                            <td><?php echo date("h:i A", strtotime($exam['start_time'])) . ' - ' . date("h:i A", strtotime($exam['end_time'])); ?></td>
                            <td></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="regulations">
            <h2>Regulations</h2>
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
$html = ob_get_clean();

// Initialize Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Save the PDF
$pdfOutput = $dompdf->output();
$pdfFilePath = "hall_tickets/{$student['admission_number']}_{$exam_id}.pdf";
if (!is_dir("hall_tickets")) {
    mkdir("hall_tickets", 0777, true);
}
file_put_contents($pdfFilePath, $pdfOutput);

// Store the file path in the database
$admission_number = $student['admission_number'];
$insertQuery = "INSERT INTO hall_tickets (admission_number, pdf_path) VALUES ('$admission_number', '$pdfFilePath')
                ON DUPLICATE KEY UPDATE pdf_path = '$pdfFilePath'";

if ($conn->query($insertQuery) === TRUE) {

    header("Location: generateTicket.php");
    exit();
} else {
    echo "Error saving PDF: " . $conn->error;
}


$conn->close();
?>
