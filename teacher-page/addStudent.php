<?php
session_start();

// Database connection
include 'config.php';

// Fetch semesters and courses
    $courseQuery = "SELECT course_id, course_name FROM courses";
    $courseResult = mysqli_query($conn, $courseQuery);

    $semesterQuery = "SELECT semester_id, semester_name FROM semesters";
    $semesterResult = mysqli_query($conn, $semesterQuery);

   

$error_messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs and validate
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $contact_number = trim($_POST['contact_number']);
    $guardian_name = trim($_POST['guardian_name']);
    $guardian_contact_number = trim($_POST['guardian_contact_number']);
    $address = trim($_POST['address']);
    $admission_number = trim($_POST['admission_number']);
    $date_of_birth = $_POST['date_of_birth'];
    $batch = trim($_POST['batch']);
    $year_of_study = $_POST['year_of_study'];
    $course_id = $_POST['course_id'];
    $semester_id = $_POST['semester_id'];

    // // Check required fields
    // if (empty($first_name) || empty($last_name) || empty($email) || empty($admission_number)) {
    //     $error_messages[] = "First name, last name, email, and admission number are required.";
    // }

    // Handle file upload with validation
    $photo_path = '';
    if (!empty($_FILES['photo']['name'])) {
        $photo_name = $_FILES['photo']['name'];
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $upload_dir = '../images';
        $photo_path = $upload_dir . basename($photo_name);

        // Check if file upload was successful
        if (!move_uploaded_file($photo_tmp, $photo_path)) {
            $error_messages[] = "Error uploading photo.";
        }
    }

    // Insert data only if no errors
    if (empty($error_messages)) {
        $sql = "INSERT INTO students (
                    first_name, last_name, email, contact_number, guardian_name, 
                    guardian_contact_number, `address`, admission_number, date_of_birth, 
                    batch, year_of_study, course_id, semester_id, photo_path
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssiiis",
            $first_name, $last_name, $email, $contact_number, $guardian_name, 
            $guardian_contact_number, $address, $admission_number, $date_of_birth, 
            $batch, $year_of_study, $course_id, $semester_id, $photo_path
        );

        if ($stmt->execute()) {
            echo "<script>alert('Student added successfully.');</script>";
        } else {
            $error_messages[] = "Database error: " . $stmt->error;
        }

        $stmt->close();
    }

// Fetch students
}
$sql = "SELECT * FROM students";
$result = $conn->query($sql);
    
?>

<!-- HTML Part -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></link>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .border-right {
        border-right: 1px solid #d1d5db; /* Change the color as needed */
        }
        .border-last {
        border-right: none; /* Remove right border for the last column */
        }
    </style>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="js/app.js" defer></script>
    <script type="text/javascript" src="js/studentValidation.js" defer></script>
</head>
<body>
<nav id="sidebar">
  <ul>
    <li>
      <span class="logo">Exam In</span>

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

    <li>
      <button onclick="toggleSubMenu(this)" class="dropdown-btn">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="24px"
          viewBox="0 -960 960 960"
          width="24px"
          fill="#5f6368"
        >
          <path
            d="M560-564v-68q33-14 67.5-21t72.5-7q26 0 51 4t49 10v64q-24-9-48.5-13.5T700-600q-38 0-73 9.5T560-564Zm0 220v-68q33-14 67.5-21t72.5-7q26 0 51 4t49 10v64q-24-9-48.5-13.5T700-380q-38 0-73 9t-67 27Zm0-110v-68q33-14 67.5-21t72.5-7q26 0 51 4t49 10v64q-24-9-48.5-13.5T700-490q-38 0-73 9.5T560-454ZM260-320q47 0 91.5 10.5T440-278v-394q-41-24-87-36t-93-12q-36 0-71.5 7T120-692v396q35-12 69.5-18t70.5-6Zm260 42q44-21 88.5-31.5T700-320q36 0 70.5 6t69.5 18v-396q-33-14-68.5-21t-71.5-7q-47 0-93 12t-87 36v394Zm-40 118q-48-38-104-59t-116-21q-42 0-82.5 11T100-198q-21 11-40.5-1T40-234v-482q0-11 5.5-21T62-752q46-24 96-36t102-12q58 0 113.5 15T480-740q51-30 106.5-45T700-800q52 0 102 12t96 36q11 5 16.5 15t5.5 21v482q0 23-19.5 35t-40.5 1q-37-20-77.5-31T700-240q-60 0-116 21t-104 59ZM280-494Z"
          />
        </svg>
        <span>Exam Menu</span>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="24px"
          viewBox="0 -960 960 960"
          width="24px"
          fill="#5f6368"
        >
          <path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z" />
        </svg>
      </button>
      <ul class="sub-menu">
        <div>
          <li>
            <a href="addExam.php"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                height="24px"
                viewBox="0 -960 960 960"
                width="24px"
                fill="#5f6368"
              >
                <path
                  d="M440-240h80v-120h120v-80H520v-120h-80v120H320v80h120v120ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"
                />
              </svg>
              Create Exam
            </a>
          </li>
          <li>
            <a href="addExamSubjects.php"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                height="24px"
                viewBox="0 -960 960 960"
                width="24px"
                fill="#5f6368"
              >
                <path
                  d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm80 240v-80h400v80H280Zm0 160v-80h280v80H280Z"
                />
              </svg>
              Exam Details</a
            >
          </li>
        </div>
      </ul>
    </li>
    <li>
      <button onclick="toggleSubMenu(this)" class="dropdown-btn">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="24px"
          viewBox="0 -960 960 960"
          width="24px"
          fill="#5f6368"
        >
          <path
            d="M480-120 200-272v-240L40-600l440-240 440 240v320h-80v-276l-80 44v240L480-120Zm0-332 274-148-274-148-274 148 274 148Zm0 241 200-108v-151L480-360 280-470v151l200 108Zm0-241Zm0 90Zm0 0Z"
          />
        </svg>
        <span>College Menu</span>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="24px"
          viewBox="0 -960 960 960"
          width="24px"
          fill="#5f6368"
        >
          <path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z" />
        </svg>
      </button>
      <ul class="sub-menu">
        <div>
          <li>
            <a href="addCourse.php"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                height="24px"
                viewBox="0 -960 960 960"
                width="24px"
                fill="#5f6368"
              >
                <path
                  d="M480-60q-72-68-165-104t-195-36v-440q101 0 194 36.5T480-498q73-69 166-105.5T840-640v440q-103 0-195.5 36T480-60Zm0-104q63-47 134-75t146-37v-276q-73 13-143.5 52.5T480-394q-66-66-136.5-105.5T200-552v276q75 9 146 37t134 75Zm0-436q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T560-760q0-33-23.5-56.5T480-840q-33 0-56.5 23.5T400-760q0 33 23.5 56.5T480-680Zm0-80Zm0 366Z"
                />
              </svg>
              Add Course
            </a>
          </li>
          <li>
            <a href="addSemester.php"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                height="24px"
                viewBox="0 -960 960 960"
                width="24px"
                fill="#5f6368"
              >
                <path
                  d="M240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h480q33 0 56.5 23.5T800-800v640q0 33-23.5 56.5T720-80H240Zm0-80h480v-640h-80v280l-100-60-100 60v-280H240v640Zm0 0v-640 640Zm200-360 100-60 100 60-100-60-100 60Z"
                />
              </svg>
              Add Semester</a
            >
          </li>
          <li >
            <a href="addSubjects.php"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                height="24px"
                viewBox="0 -960 960 960"
                width="24px"
                fill="#5f6368"
              >
                <path
                  d="M300-80q-58 0-99-41t-41-99v-520q0-58 41-99t99-41h500v600q-25 0-42.5 17.5T740-220q0 25 17.5 42.5T800-160v80H300Zm-60-267q14-7 29-10t31-3h20v-440h-20q-25 0-42.5 17.5T240-740v393Zm160-13h320v-440H400v440Zm-160 13v-453 453Zm60 187h373q-6-14-9.5-28.5T660-220q0-16 3-31t10-29H300q-26 0-43 17.5T240-220q0 26 17 43t43 17Z"
                />
              </svg>
              Add Subjects</a
            >
          </li>
        </div>
      </ul>
    </li>
    <li  class="active">
      <a href="addStudent.php">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="24px"
          viewBox="0 -960 960 960"
          width="24px"
          fill="#5f6368"
        >
          <path
            d="M720-400v-120H600v-80h120v-120h80v120h120v80H800v120h-80Zm-360-80q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z"
          />
        </svg>
        <span>Add Student</span>
      </a>
    </li>
    <li>
      <a href="generateTicket.php">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M720-120v-120H600v-80h120v-120h80v120h120v80H800v120h-80ZM160-560h640-640Zm80 440v-160H80v-240q0-51 35-85.5t85-34.5h560q51 0 85.5 34.5T880-520v32q-18-10-38-17.5T800-516q0-17-11.5-30.5T760-560H200q-17 0-28.5 11.5T160-520v160h80v-80h342q-16 17-28 37t-20 43H320v160h214q7 22 20 42t28 38H240Zm400-520v-120H320v120h-80v-200h480v200h-80Z"/></svg>
        <span>Generate</span>
      </a>
    </li>
    <div class="logout-container">
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
    </div>
  </ul>
</nav>
    <main>
    <?php if (!empty($error_messages)) : ?>
    <script>
        alert("<?php echo implode('\n', $error_messages); ?>");
    </script>
<?php endif; ?>


        <div class="formDetails container">
            <h1 class="text-3xl text-center mb-4 font-bold">Enter Student Details</h2>
            <form
                action="addStudent.php"
                method="post"
                enctype="multipart/form-data"
                id="studentForm"
            >
                <!-- First Name -->
                <div class="name-details">
                    <div class="name-group">
                        <label for="firstName">First Name:</label>
                        <input
                            type="text"
                            id="firstName"
                            name="first_name"
                            autocomplete="off"
                        />
                    </div>

                    <!-- Last Name -->
                    <div class="name-group">
                        <label for="lastName">Last Name:</label>
                        <input
                            type="text"
                            id="lastName"
                            name="last_name"
                            autocomplete="off"
                        />
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" autocomplete="off" />
                </div>

                <!-- Contact Number -->
                <div class="form-group">
                    <label for="contactNumber">Contact Number:</label>
                    <input
                        type="tel"
                        id="contactNumber"
                        name="contact_number"
                        autocomplete="off"
                    />
                </div>

                <!-- Guardian Name -->
                <div class="form-group">
                    <label for="guardianName">Guardian's Name:</label>
                    <input
                        type="text"
                        id="guardianName"
                        name="guardian_name"
                        autocomplete="off"
                    />
                </div>

                <!-- Guardian Contact Number -->
                <div class="form-group">
                    <label for="guardianContactNumber">Guardian's Contact Number:</label>
                    <input
                        type="tel"
                        id="guardianContactNumber"
                        name="guardian_contact_number"
                        autocomplete="off"
                    />
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        autocomplete="off"
                    ></textarea>
                </div>

                <!-- Admission Number -->
                <div class="form-group">
                    <label for="admissionNumber">Admission Number:</label>
                    <input
                        type="text"
                        id="admissionNumber"
                        name="admission_number"
                        autocomplete="off"
                    />
                </div>

                <!-- Date of Birth -->
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input
                        type="date"
                        id="dob"
                        name="date_of_birth"
                        autocomplete="off"
                    />
                </div>

                <!-- Batch -->
                <div class="form-group">
                    <label for="batch">Batch:</label>
                    <input
                        type="text"
                        id="batch"
                        name="batch"
                        placeholder="(e.g., 2022-25)"
                        autocomplete="off"
                    />
                </div>

                <!-- Year of Study -->
                <div class="form-group">
    <label for="yearOfStudy">Year of Study:</label>
    <select id="yearOfStudy" name="year_of_study">
        <option value="" disabled selected>Select year of study</option>
        <option value="1">First Year</option>
        <option value="2">Second Year</option>
        <option value="3">Third Year</option>
        <option value="4">Fourth Year</option>
    </select>
</div>


                
                 <!-- Course Enrolled Dropdown -->
                 <div class="form-group">
                    <label for="course_id">Course Enrolled:</label>
                    <select id="course_id" name="course_id">
                        <option value="" disabled selected>Select a course</option>
                        <?php while ($course = mysqli_fetch_assoc($courseResult)): ?>
                            <option value="<?= $course['course_id'] ?>"><?= htmlspecialchars($course['course_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Semester Dropdown -->
                <div class="form-group">
                    <label for="semester_id">Semester:</label>
                    <select id="semester_id" name="semester_id">
                        <option value="" disabled selected>Select a semester</option>
                        <?php while ($semester = mysqli_fetch_assoc($semesterResult)): ?>
                            <option value="<?= $semester['semester_id'] ?>"><?= htmlspecialchars($semester['semester_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                
                <!-- Upload Photo -->
                <div class="form-group">
                  <label for="photo">Upload Photo:</label>
                  <input
                  type="file"
                  id="photo"
                  name="photo"
                  accept="image/*"
                  />
                </div>
                <input type="submit" name="submit-btn" class="submit-btn" value="Submit" />
            </form>
        </div> 
        <div class="container mx-auto p-4">
   <h1 class="text-3xl font-bold text-center mb-8">
    Student Management
   </h1>
   <div class="bg-white shadow-md rounded-lg p-6">
   <table class="min-w-full bg-white">
    <thead>
        <tr>
            <th class="py-2 px-4 border-b-2 border-gray-300 border-right">Image</th>
            <th class="py-2 px-4 border-b-2 border-gray-300 border-right">Name</th>
            <th class="py-2 px-4 border-b-2 border-gray-300 border-right">Batch</th>
            <th class="py-2 px-4 border-b-2 border-gray-300 border-right">Admission Number</th>
            <th class="py-2 px-4 border-b-2 border-gray-300">Actions</th>
        </tr>
        <tr>
            <td colspan="5" class="py-2 px-4 border-b border-gray-200"></td>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td class="py-2 px-4 border-b border-gray-200 border-right">'; // Add border-right
                echo '<img alt="Profile picture of ' . $row["first_name"] . ' ' . $row["last_name"] . '" class="rounded-full" height="50" src="' . $row["photo_path"] . '" width="50"/>';
                echo '</td>';
                echo '<td class="py-2 px-4 border-b border-gray-200 border-right">' . $row["first_name"] . ' ' . $row["last_name"] . '</td>'; // Add border-right
                echo '<td class="py-2 px-4 border-b border-gray-200 border-right">' . $row["batch"] . '</td>'; // Add border-right
                echo '<td class="py-2 px-4 border-b border-gray-200 border-right">' . $row["admission_number"] . '</td>'; // Add border-right
                echo '<td class="py-2 px-4 border-b border-gray-200">';
                echo '<a class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" href="delete_student.php?student_id=' . $row["student_id"] . '" onclick="return confirm(\'Are you sure you want to delete this student?\')">';
                echo '<i class="fas fa-trash"></i> Delete';
                echo '</a>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="5" class="py-2 px-4 border-b border-gray-200 text-center">No students found</td></tr>';
        }
        ?>
    </tbody>
</table>
   </div>
</div>

<?php
$conn->close();
?>

    </main>
</body>
</html>
