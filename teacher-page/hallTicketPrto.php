<?php
// Include database connection
include 'config.php';

$admission_number = isset($_POST['admission_number']) ? $_POST['admission_number'] : '';

if ($admission_number) {
    // Fetch student details
    $student_query = "SELECT * FROM students WHERE admission_number = ?";
    $stmt = $conn->prepare($student_query);
    $stmt->bind_param("s", $admission_number);
    $stmt->execute();
    $student_result = $stmt->get_result();
    $student = $student_result->fetch_assoc();

    if ($student) {
        // Fetch course details
        $course_id = $student['course_id'];
        $semester_id = $student['semester_id'];

        $course_query = "SELECT * FROM courses WHERE course_id = ?";
        $stmt = $conn->prepare($course_query);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $course_result = $stmt->get_result();
        $course = $course_result->fetch_assoc();

        // Fetch subjects and exam details
        $exam_query = "
            SELECT es.exam_date, es.start_time, es.end_time, s.subject_name 
            FROM exam_subjects es
            JOIN subjects s ON es.subject_id = s.subject_id
            JOIN exams e ON es.exam_id = e.exam_id
            WHERE e.course_id = ? AND e.semester_id = ?
            ORDER BY es.exam_date";
        $stmt = $conn->prepare($exam_query);
        $stmt->bind_param("ii", $course_id, $semester_id);
        $stmt->execute();
        $exam_result = $stmt->get_result();
    } else {
        echo "Student not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Ticket</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-blue-500 text-white text-center py-4">
                <h1 class="text-2xl font-bold">Hall Ticket</h1>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="text-xl font-bold">Student Name: <?php echo $student['first_name'] . ' ' . $student['last_name']; ?></h2>
                        <p class="text-gray-700">Roll Number: <?php echo $student['admission_number']; ?></p>
                        <p class="text-gray-700">Course: <?php echo $course['course_name']; ?></p>
                    </div>
                    <div>
                        <img alt="Student Photo" class="rounded-full" height="100" src="<?php echo $student['photo_path']; ?>" width="100"/>
                    </div>
                </div>
                < <div class="mb-4">
                    <h3 class="text-lg font-bold">Examination Details</h3>
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 bg-gray-200">Subject Code</th>
                                <th class="py-2 px-4 bg-gray-200">Subject Name</th>
                                <th class="py-2 px-4 bg-gray-200">Date</th>
                                <th class="py-2 px-4 bg-gray-200">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($exam = $exam_result->fetch_assoc()): ?>
                                <tr>
                                    <td class="border px-4 py-2"><?php echo $exam['subject_id']; ?></td>
                                    <td class="border px-4 py-2"><?php echo $exam['subject_name']; ?></td>
                                    <td class="border px-4 py-2"><?php echo $exam['exam_date']; ?></td>
                                    <td class="border px-4 py-2"><?php echo date('h:i A', strtotime($exam['start_time'])) . ' - ' . date('h:i A', strtotime($exam['end_time'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-bold">Examination Regulations</h3>
                    <ul class="list-disc list-inside text-gray-700">
                        <li>Students must carry their hall ticket and college ID card to the examination hall.</li>
                        <li>Electronic gadgets such as mobile phones, smartwatches, and calculators are not allowed.</li>
                        <li>Students should report to the examination hall at least 30 minutes before the scheduled time.</li>
                        <li>Any form of malpractice will result in disqualification from the examination.</li>
                        <li>Students are not allowed to leave the examination hall before the end of the examination time.</li>
                        <li>Ensure that you have all the necessary stationery items before the examination starts.</li>
                        <li>Follow the instructions given by the invigilators during the examination.</li>
                    </ul>
                </div>
                <div class="text-center mt-6">
                    <button class="bg-blue-500 text-white py-2 px-4 rounded">Download Hall Ticket</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
