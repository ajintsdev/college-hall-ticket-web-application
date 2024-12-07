<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "examin");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Replace with a specific student's admission number or ID for a single hall ticket generation
$student_id = 3; // example student_id
$exam_id = 1;    // example exam_id

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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Hall Ticket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Roboto', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-blue-500 text-white text-center py-4">
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
                <p class="text-gray-600">Exam Name: <?php echo $examResult->fetch_assoc()['exam_name']; ?></p>
                <p class="text-gray-600">Exam Center: ABC High School</p>
                <p class="text-gray-600">Center Code: 7890</p>
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
                        <?php while($exam = $examResult->fetch_assoc()): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?php echo date("d/m/Y", strtotime($exam['exam_date'])); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo $exam['subject_name']; ?></td>
                                <td class="py-2 px-4 border-b"><?php echo date("h:i A", strtotime($exam['start_time'])) . ' - ' . date("h:i A", strtotime($exam['end_time'])); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo "  "; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-gray-100 text-center py-4">
            <p class="text-gray-600">Please bring this hall ticket to the exam center.</p>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
