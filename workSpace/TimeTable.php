<html>
<head>
    <title>Exam Timetable</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></link>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center">Midterm Exam Timetable</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-r border-b">Subject</th>
                        <th class="py-2 px-4 border-r border-b">Date</th>
                        <th class="py-2 px-4 border-r border-b">Start Time</th>
                        <th class="py-2 px-4 border-r border-b">End Time</th>
                        <th class="py-2 px-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-2 px-4 border-r border-b">Mathematics</td>
                        <td class="py-2 px-4 border-r border-b">2023-10-15</td>
                        <td class="py-2 px-4 border-r border-b">09:00 AM</td>
                        <td class="py-2 px-4 border-r border-b">12:00 PM</td>
                        <td class="py-2 px-4 border-b text-center">
                            <button class="bg-red-500 text-white px-4 py-1 rounded">Delete</button>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="py-2 px-4 border-r border-b">History</td>
                        <td class="py-2 px-4 border-r border-b">2023-10-19</td>
                        <td class="py-2 px-4 border-r border-b">09:00 AM</td>
                        <td class="py-2 px-4 border-r border-b">12:00 PM</td>
                        <td class="py-2 px-4 border-b text-center">
                            <button class="bg-red-500 text-white px-4 py-1 rounded">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-r border-b">Geography</td>
                        <td class="py-2 px-4 border-r border-b">2023-10-20</td>
                        <td class="py-2 px-4 border-r border-b">09:00 AM</td>
                        <td class="py-2 px-4 border-r border-b">12:00 PM</td>
                        <td class="py-2 px-4 border-b text-center">
                            <button class="bg-red-500 text-white px-4 py-1 rounded">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-r border-b">English</td>
                        <td class="py-2 px-4 border-r border-b">2023-10-21</td>
                        <td class="py-2 px-4 border-r border-b">09:00 AM</td>
                        <td class="py-2 px-4 border-r border-b">12:00 PM</td>
                        <td class="py-2 px-4 border-b text-center">
                            <button class="bg-red-500 text-white px-4 py-1 rounded">Delete</button>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>










<?php
// Include database connection
include 'db_connection.php'; // Adjust the path as needed

// Set the exam_id for the midterm exam (replace with the actual exam_id)
$exam_id = 1; // Example exam_id for "Midterm Exam"

// Fetch exam name for the heading
$exam_query = "SELECT exam_name FROM exams WHERE exam_id = '$exam_id'";
$exam_result = mysqli_query($conn, $exam_query);

if ($exam_row = mysqli_fetch_assoc($exam_result)) {
    $exam_name = $exam_row['exam_name'];
} else {
    echo "Exam not found.";
    exit;
}

// Fetch subjects and timetable details for the specified exam
$query = "
    SELECT subjects.subject_name, exam_subjects.exam_date, 
           exam_subjects.start_time, exam_subjects.end_time
    FROM exam_subjects
    JOIN subjects ON exam_subjects.subject_id = subjects.subject_id
    WHERE exam_subjects.exam_id = '$exam_id'
    ORDER BY exam_subjects.exam_date ASC
";
$result = mysqli_query($conn, $query);

?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-center"><?php echo htmlspecialchars($exam_name); ?> Timetable</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Subject</th>
                    <th class="py-2 px-4 border-b">Date</th>
                    <th class="py-2 px-4 border-b">Start Time</th>
                    <th class="py-2 px-4 border-b">End Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td class='py-2 px-4 border-b'>" . htmlspecialchars($row['subject_name']) . "</td>";
                    echo "<td class='py-2 px-4 border-b'>" . htmlspecialchars($row['exam_date']) . "</td>";
                    echo "<td class='py-2 px-4 border-b'>" . htmlspecialchars(date("h:i A", strtotime($row['start_time']))) . "</td>";
                    echo "<td class='py-2 px-4 border-b'>" . htmlspecialchars(date("h:i A", strtotime($row['end_time']))) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Close the database connection
mysqli_close($conn);
?>
