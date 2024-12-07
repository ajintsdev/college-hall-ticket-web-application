<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "examin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the student ID from the AJAX request
$student_id = $_POST['student_id'];

// Fetch student details
$student_query = "SELECT * FROM students WHERE student_id = '$student_id'";
$student_result = $conn->query($student_query);
$student = $student_result->fetch_assoc();

// Fetch course details
$course_id = $student['course_id'];
$course_query = "SELECT * FROM courses WHERE course_id = '$course_id'";
$course_result = $conn->query($course_query);
$course = $course_result->fetch_assoc();

// Fetch semester details
$semester_id = $student['semester_id'];
$semester_query = "SELECT * FROM semesters WHERE semester_id = '$semester_id'";
$semester_result = $conn->query($semester_query);
$semester = $semester_result->fetch_assoc();

// Fetch the subjects and exam details for the student
$exam_query = "
    SELECT es.exam_date, es.start_time, es.end_time, s.subject_name
    FROM exam_subjects es
    JOIN exams e ON es.exam_id = e.exam_id
    JOIN subjects s ON es.subject_id = s.subject_id
    WHERE e.course_id = '$course_id' AND e.semester_id = '$semester_id'
";
$exam_result = $conn->query($exam_query);

// Generate the hall ticket HTML content
$output = "
    <div class='max-w-3xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden'>
        <div class='bg-blue-500 text-white text-center py-4'>
            <h1 class='text-2xl font-bold'>Hall Ticket</h1>
        </div>
        <div class='p-6'>
            <div class='flex justify-between items-center mb-4'>
                <div>
                    <h2 class='text-xl font-bold'>Student Name: {$student['first_name']} {$student['last_name']}</h2>
                    <p class='text-gray-700'>Roll Number: {$student['admission_number']}</p>
                    <p class='text-gray-700'>Course: {$course['course_name']}</p>
                    <p class='text-gray-700'>Batch: {$student['batch']}</p>
                    <p class='text-gray-700'>Semester: {$semester['semester_name']}</p>
                </div>
                <div>
                    <img alt='Student Photo' class='rounded-full' height='100' src='{$student['photo_path']}' width='100'/>
                </div>
            </div>
            <div class='mb-4'>
                <h3 class='text-lg font-bold'>Examination Details</h3>
                <table class='min-w-full bg-white'>
                    <thead>
                        <tr>
                            <th class='py-2 px-4 bg-gray-200'>Subject Name</th>
                            <th class='py-2 px-4 bg-gray-200'>Date</th>
                            <th class='py-2 px-4 bg-gray-200'>Time</th>
                        </tr>
                    </thead>
                    <tbody>";

while ($exam = $exam_result->fetch_assoc()) {
    $output .= "
                    <tr>
                        <td class='border px-4 py-2'>{$exam['subject_name']}</td>
                        <td class='border px-4 py-2'>{$exam['exam_date']}</td>
                        <td class='border px-4 py-2'>{$exam['start_time']} - {$exam['end_time']}</td>
                    </tr>";
}

$output .= "
                    </tbody>
                </table>
            </div>
            <div class='mb-4'>
                <h3 class='text-lg font-bold'>Examination Regulations</h3>
                <ul class='list-disc list-inside text-gray-700'>
                    <li>Students must carry their hall ticket and college ID card to the examination hall.</li>
                    <li>Electronic gadgets such as mobile phones, smartwatches, and calculators are not allowed.</li>
                    <li>Students should report to the examination hall at least 30 minutes before the scheduled time.</li>
                    <li>Any form of malpractice will result in disqualification from the examination.</li>
                    <li>Students are not allowed to leave the examination hall before the end of the examination time.</li>
                    <li>Ensure that you have all the necessary stationery items before the examination starts.</li>
                    <li>Follow the instructions given by the invigilators during the examination.</li>
                </ul>
            </div>
        </div>
    </div>";

echo $output;

// Close connection
$conn->close();
?>
