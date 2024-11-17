<?php
// Include database connection file
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grade_level = null;
    $track = null;
    $course = null;
    $course_level = null;
    $lrn = null;
    $student_id = $_POST['student_id']; // Get generated student ID
    $first_name = $_POST['first_name']; // First Name
    $middle_name = $_POST['middle_name']; // Middle Name
    $last_name = $_POST['last_name']; // Last Name
    $email = $_POST['email']; // Email Address
    $phone = $_POST['phone']; // Cellphone Number
    $username = $_POST['username']; // Username
    $password = $_POST['password']; // Password (You should hash this password before saving)

    // Handle new fields
    $address = $_POST['address']; // Present Address
    $province = $_POST['province']; // Province
    $zip_code = $_POST['zip_code']; // ZIP Code
    $city = $_POST['city']; // City
    $emergency_name = $_POST['emergency_name']; // Emergency Contact Name
    $emergency_phone = $_POST['emergency_phone']; // Emergency Phone
    $relation = $_POST['relation']; // Emergency Contact Relation
    $enroll_date = $_POST['enroll_date']; // Enrollment Date
    $enroll_time = $_POST['enroll_time']; // Enrollment Time
    $session = $_POST['session'];

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Handle Grade 7 to Grade 10
    if (isset($_POST['grade_level_course'])) {
        $grade_level = $_POST['grade_level_course']; // Grade 7 to Grade 10
        if (in_array($grade_level, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'])) {
            $lrn = $_POST['lrn'] ?? null; // Get LRN if available
        }
    }

    // Handle Grade 11 and 12 with Tracks
    if (isset($_POST['track_grade_11']) && $_POST['grade_level_course'] === 'Grade 11') {
        $grade_level = 'Grade 11';
        $track = $_POST['track_grade_11'];
    }

    if (isset($_POST['track_grade_12']) && $_POST['grade_level_course'] === 'Grade 12') {
        $grade_level = 'Grade 12';
        $track = $_POST['track_grade_12'];
    }

    // Handle TVET with Tracks
    if (isset($_POST['track_tvet']) && $_POST['grade_level_course'] === 'TVET') {
        $grade_level = 'TVET';
        $track = $_POST['track_tvet'];
        $lrn = null; // No LRN for TVET
    }

    // Handle College with Course and Year Level
    if (isset($_POST['course_college']) && $_POST['grade_level_course'] === 'COLLEGE') {
        $course = $_POST['course_college'];
        $track = $_POST['course_college'];
        $course_level = $_POST['course_level'];
        $lrn = null; // No LRN for College
    }

    // Handle Profile Image Upload
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] == 0) {
        // Get the file info
        $imageName = $_FILES['profile']['name'];
        $imageTmpName = $_FILES['profile']['tmp_name'];
        $imageSize = $_FILES['profile']['size'];
        $imageType = $_FILES['profile']['type'];

        // Read the image file as binary data
        $imageData = file_get_contents($imageTmpName);

        // Ensure the image is a valid file type (optional, you can expand the validation)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imageType, $allowedTypes)) {
            die("Invalid image type. Please upload a JPG, PNG, or GIF image.");
        }

        // You can store the image type or name if you want
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
    } else {
        $imageData = null; // No image selected
    }

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO enrollments (student_id, first_name, middle_name, last_name, email, phone, username, password, grade_level, track, course, course_level, lrn, profile, address, province, zip_code, city, emergency_name, emergency_phone, relation, enroll_date, enroll_time, session) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssssssssssssssssssssssss",
        $student_id,
        $first_name,
        $middle_name,
        $last_name,
        $email,
        $phone,
        $username,
        $hashed_password,
        $grade_level,
        $track,
        $course,
        $course_level,
        $lrn,
        $imageData,
        $address,
        $province,
        $zip_code,
        $city,
        $emergency_name,
        $emergency_phone,
        $relation,
        $enroll_date,
        $enroll_time,
        $session // Bind the session value
    );

    if ($stmt->execute()) {
        echo "<script>
            alert('Enrollment successful! Student ID: $student_id');
            document.getElementById('student_id_input').value = '$student_id';
        </script>";
    } else {
        echo "<script>alert('Error: {$stmt->error}');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="enroll.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="./image/apple-touch-icon.png">

    <style>
        /* Default input styles */
        .subject-code,
        .description,
        .days,
        .time,
        .room-no,
        .units {
            height: 30px;
            border-radius: 3px;
            border: 1px solid #aaa;
            padding: 0 15px;
            font-size: 11px;
            outline: none;
        }

        /* Specific widths for each column */
        .subject-code {
            width: 100px;
        }

        .description {
            width: 600px;
        }

        .days {
            width: 100px;
        }

        .time {
            width: 140px;
        }

        .room-no {
            width: 80px;
        }

        .units {
            width: 80px;
        }

        /* Media query for laptop screens (1024px to 1366px) */
        @media (min-width: 1024px) and (max-width: 1366px) {
            .subject-code {
                width: 100px;
                /* Resize subject code for laptops */
            }

            .description {
                width: 300px;
                /* Reduce description width for laptops */
            }

            .days {
                width: 100px;
                /* Resize days for laptops */
            }

            .time {
                width: 140px;
                /* Resize time for laptops */
            }

            .room-no {
                width: 80px;
                /* Resize room number for laptops */
            }

            .units {
                width: 100px;
                /* Resize units for laptops */
            }
        }

        /* Media query for smaller screens (max-width: 768px) */
        @media (max-width: 768px) {
            .subject-code {
                width: 80px;
                /* Resize subject code */
            }

            .description {
                width: 100%;
                /* Make description take full width */
            }

            .days {
                width: 80px;
                /* Resize days */
            }

            .time {
                width: 120px;
                /* Resize time */
            }

            .room-no {
                width: 100px;
                /* Resize room number */
            }

            .units {
                width: 100px;
                /* Resize units */
            }
        }

        /* Media query for very small screens (max-width: 480px) */
        @media (max-width: 480px) {
            .subject-code {
                width: 70px;
                /* Resize subject code further */
            }

            .description {
                width: 100%;
                /* Full width for description */
            }

            .days {
                width: 70px;
                /* Resize days further */
            }

            .time {
                width: 100px;
                /* Resize time further */
            }

            .room-no {
                width: 70px;
                /* Resize room number */
            }

            .units {
                width: 70px;
                /* Resize units */
            }
        }
    </style>

</head>

<body>
    <!-- MAIN CONTAINER -->
    <div class="main-container">
        <div class="sub-main-container">
            <div class="school-calendar-header">
                <img src="./image/apple-touch-icon.png" alt="College Logo" class="college-logo">
                <h1 class="college-name">SAMSON POLYTECHNIC COLLEGE of DAVAO</h1>
                <p class="college-address">(Formerly Samson Technical Institute)</p>
                <p class="college-address">R. Magsaysay Avenue, 8000 Davao City</p>
                <p class="college-address">Tel. No. (082) 227-2392</p>
                <h2 class="department-title">STUDENT'S INFORMATION SHEET</h2>
            </div>
            <div class="main-container-header">
                <div class="sub-main-container-header start">
                    <i class="fa-solid fa-bars-progress"></i> <span> Student's Information Sheet</span>
                </div>
                <div class="sub-main-container-header end">
                    <button onclick="showForm('enrollForm')">Enroll</button>
                    <button onclick="showForm('loadingSubjectForm')">Loading Subject</button>
                    <button onclick="showForm('paymentMethodForm')">Payment Method</button>
                </div>
            </div>
            <div class="main-container-form">

                <div class="enroll-options">
                    <button id="enrollNewBtn"
                        style="height: 35px; margin-left: 100px; max-width: 300px; width: 100%; border: none; outline: none; color: white; border-radius: 5px; cursor: pointer; background-color: #4070f4; transition: opacity ease 0.3s;">
                        Enroll New Student
                    </button>
                    <button id="enrollExistingBtn"
                        style="height: 35px; margin-left: 100px; max-width: 300px; width: 100%; border: none; outline: none; color: white; border-radius: 5px; cursor: pointer; background-color: #4070f4; transition: opacity ease 0.3s;">
                        Enroll Existing Student
                    </button>
                </div>

                <script>
                    // Button event listeners for showing the correct form
                    document.getElementById('enrollNewBtn').addEventListener('click', () => {
                        // Hide the initial buttons and show the new enrollment form
                        document.getElementById('enrollNewBtn').style.display = 'none';
                        document.getElementById('enrollExistingBtn').style.display = 'none';
                        document.getElementById('enrollForm').style.display = 'block';
                    });

                    document.getElementById('enrollExistingBtn').addEventListener('click', () => {
                        // Hide the initial buttons and show the existing student ID popup
                        document.getElementById('enrollNewBtn').style.display = 'none';
                        document.getElementById('enrollExistingBtn').style.display = 'none';
                        document.getElementById('existingStudentPopup').style.display = 'block';
                    });

                    // Handle the submission of the existing student ID
                    document.getElementById('submitExistingID').addEventListener('click', () => {
                        const studentID = document.getElementById('existingStudentID').value;
                        if (studentsData[studentID]) {
                            populateForm(studentsData[studentID]);
                            document.getElementById('existingStudentPopup').style.display = 'none';
                            document.getElementById('enrollForm').style.display = 'block';
                        } else {
                            alert("Student ID not found.");
                        }
                    });
                </script>

                <form method="POST" action="" enctype="multipart/form-data" class="enrollForm" id="enrollForm" style="display: none;">
                    <div class="enroll-form first">
                        <div class="personal-details personal">
                            <span class="title">Grade Level Course:</span>
                            <div class="enroll-fields">
                                <div class="enrol-input-fields">
                                    <label for="grade7">Grade 7</label>
                                    <input type="checkbox" id="grade7" name="grade_level_course" value="Grade 7" onclick="handleCheckboxChange('grade7')">
                                </div>

                                <div class="enrol-input-fields">
                                    <label for="grade8">Grade 8</label>
                                    <input type="checkbox" id="grade8" name="grade_level_course" value="Grade 8" onclick="handleCheckboxChange('grade8')">
                                </div>

                                <div class="enrol-input-fields">
                                    <label for="grade9">Grade 9</label>
                                    <input type="checkbox" id="grade9" name="grade_level_course" value="Grade 9" onclick="handleCheckboxChange('grade9')">
                                </div>

                                <div class="enrol-input-fields">
                                    <label for="grade10">Grade 10</label>
                                    <input type="checkbox" id="grade10" name="grade_level_course" value="Grade 10" onclick="handleCheckboxChange('grade10')">
                                </div>

                                <div class="enrol-input-fields">
                                    <label for="grade11">Grade 11</label>
                                    <input type="checkbox" id="grade11" name="grade_level_course" value="Grade 11" onclick="handleCheckboxChange('grade11')">
                                    <select id="trackGrade11" name="track_grade_11" disabled style="height: 30px; border-radius: 3px; border: none; border: 1px solid #aaa; padding: 0 15px; font-size: 11px; outline: none;">
                                        <option value="">Select Track</option>
                                        <option value="GAS">GAS</option>
                                        <option value="STEM">STEM</option>
                                        <option value="WAS">WAS</option>
                                    </select>
                                </div>

                                <div class="enrol-input-fields">
                                    <label for="grade12">Grade 12</label>
                                    <input type="checkbox" id="grade12" name="grade_level_course" value="Grade 12" onclick="handleCheckboxChange('grade12')">
                                    <select id="trackGrade12" name="track_grade_12" disabled style="height: 30px; border-radius: 3px; border: none; border: 1px solid #aaa; padding: 0 15px; font-size: 11px; outline: none;">
                                        <option value="">Select Track</option>
                                        <option value="GAS">GAS</option>
                                        <option value="STEM">STEM</option>
                                        <option value="WAS">WAS</option>
                                    </select>
                                </div>

                                <div class="enrol-input-fields">
                                    <label for="tvet">TVET</label>
                                    <input type="checkbox" id="tvet" name="grade_level_course" value="TVET" onclick="handleCheckboxChange('tvet')">
                                    <select id="trackTvet" name="track_tvet" disabled style="height: 30px; border-radius: 3px; border: none; border: 1px solid #aaa; padding: 0 15px; font-size: 11px; outline: none;">
                                        <option value="">Select Track</option>
                                        <option value="Automotive">Automotive</option>
                                        <option value="Front Office">Front Office</option>
                                        <option value="Sample">Sample</option>
                                    </select>
                                </div>

                                <div class="enrol-input-fields">
                                    <label for="college">College</label>
                                    <input type="checkbox" id="college" name="grade_level_course" value="COLLEGE" onclick="handleCheckboxChange('college')">
                                    <select id="courseCollege" name="course_college" disabled style="height: 30px; border-radius: 3px; border: none; border: 1px solid #aaa; padding: 0 15px; font-size: 11px; outline: none;">
                                        <option value="">Select Course</option>
                                        <option value="BSIT">BSIT</option>
                                        <option value="BSHM">BSHM</option>
                                        <option value="BSBA">BSBA</option>
                                        <option value="BSTM">BSTM</option>
                                    </select>
                                    <select id="courseLevel" name="course_level" disabled style="height: 30px; border-radius: 3px; border: none; border: 1px solid #aaa; padding: 0 15px; font-size: 11px; outline: none;">
                                        <option value="">Select Level</option>
                                        <option value="1stYear">1st Year</option>
                                        <option value="2ndYear">2nd Year</option>
                                        <option value="3rdYear">3rd Year</option>
                                        <option value="4thYear">4th Year</option>
                                    </select>
                                </div>
                            </div>

                            <div class="enroll-fields">
                                <div class="enroll-input-fields">
                                    <label for="student_id_input">Student ID</label>
                                    <input type="text" id="student_id_input" name="student_id" placeholder="system generated" readonly
                                        style="height: 30px; border-radius: 3px; border: 1px solid #aaa; padding: 0 15px; font-size: 11px; outline: none;" />
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="Session">Session</label>
                                    <select id="Session" name="session"
                                        style="height: 30px; border-radius: 3px; border: none; border: 1px solid #aaa; padding: 0 15px; font-size: 11px; outline: none;">
                                        <option value="" selected disabled>Select Session</option>
                                        <option value="Morning">Morning - unavailable</option>
                                        <option value="Afternoon">Afternoon</option>
                                    </select>
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="lrn">LRN: (JHS/SHS only)</label>
                                    <input type="text" id="lrn" name="lrn" disabled>
                                </div>
                            </div>

                            <span class="title">Personal Data</span>

                            <div class="profile-photo-container" style="text-align: center;">
                                <span style="color: #333; font-weight: 500; font-size: 14px;">Profile Image</span>
                                <div class="profile-photo">
                                    <img id="profileDisplay" src="" alt="Profile Photo" style="display: none; width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 2px solid white;">
                                </div>
                                <label for="profile" class="upload-label">
                                    <span id="uploadText">SELECT NEW PHOTO</span>
                                </label>
                                <input type="file" id="profile" name="profile" accept="image/*" style="display: none;" onchange="previewImage(event)">
                            </div>

                            <style>
                                .upload-label {
                                    cursor: pointer;
                                    color: white;
                                    font-size: 14px;
                                    display: inline-block;
                                    margin-top: 10px;
                                    transition: opacity 0.3s ease;
                                    /* Smooth transition for opacity */
                                }

                                .upload-label:hover {
                                    opacity: 0.7;
                                    /* Full visibility on hover */
                                }

                                .profile-photo-container {
                                    font-family: Arial, sans-serif;
                                }
                            </style>

                            <script>
                                // Function to handle profile image preview and text update
                                function previewImage(event) {
                                    const file = event.target.files[0]; // Get the selected file
                                    const profileDisplay = document.getElementById('profileDisplay'); // Profile image preview element
                                    const uploadText = document.getElementById('uploadText'); // Upload button text element

                                    if (file) {
                                        const reader = new FileReader(); // Create a FileReader instance

                                        reader.onload = function() {
                                            // Update the profile display with the selected image
                                            profileDisplay.src = reader.result;
                                            profileDisplay.style.display = 'block'; // Show the image preview
                                            uploadText.textContent = 'CHANGE PHOTO'; // Update the button text
                                        };

                                        reader.readAsDataURL(file); // Read the selected file
                                    }
                                }
                            </script>


                            <div class="enroll-fields">
                                <div class="enroll-input-fields">
                                    <label for="firstName">First Name</label>
                                    <input type="text" id="firstName" name="first_name"
                                        placeholder="Enter your First Name">
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="middleName">Middle Name</label>
                                    <input type="text" id="middleName" name="middle_name"
                                        placeholder="Enter your Middle Name">
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" id="lastName" name="last_name"
                                        placeholder="Enter your Last Name">
                                </div>
                            </div>

                            <div class="enroll-fields">
                                <div class="enroll-input-fields">
                                    <label for="email">Email Address</label>
                                    <input title="email" type="email" id="email" name="email">
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="phone">Cellphone Number</label>
                                    <input type="text" id="phone" name="phone" placeholder="0912-345-6789"
                                        oninput="formatPhoneNumber(this)">
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="username">Username</label>
                                    <input title="email" type="email" id="username" name="username">
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="password">Password</label>
                                    <input title="password" type="password" id="password" name="password">
                                </div>
                            </div>

                            <div class="enroll-fields">
                                <div class="enroll-input-fields">
                                    <label for="address">Present Address</label>
                                    <input title="address" type="text" id="address" name="address">
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="province">Province</label>
                                    <input title="province" type="text" id="province" name="province">
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="zip_code">ZIP CODE:</label>
                                    <input title="zip_code" type="text" id="zip_code" name="zip_code">
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="city">City:</label>
                                    <input title="city" type="text" id="city" name="city">
                                </div>
                            </div>

                            <div class="enroll-fields">
                                <div class="enroll-input-fields">
                                    <label for="emergencyName">Incase of Emergency</label>
                                    <input title="emergencyName" type="text" id="emergencyName" name="emergency_name">
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="emergencyPhone">Cellphone Number:</label>
                                    <input type="text" id="emergencyPhone" name="emergency_phone"
                                        placeholder="0912-345-6789" oninput="formatPhoneNumber(this)">
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="relation">Relation</label>
                                    <select id="relation" name="relation"
                                        style="height: 30px; border-radius: 3px; border: none; border: 1px solid #aaa; padding: 0 15px; font-size: 11px; outline: none;">
                                        <option value="" selected disabled>Select Relation</option>
                                        <option value="Mother">Mother</option>
                                        <option value="Father">Father</option>
                                        <option value="Sister">Sister</option>
                                        <option value="Brother">Brother</option>
                                    </select>
                                </div>
                            </div>

                            <div class="enroll-fields">
                                <div class="enroll-input-fields">
                                    <label for="enrollDate">Enrollment Date</label>
                                    <input type="date" id="enrollDate" name="enroll_date"
                                        placeholder="Enter enrollment date" style="cursor: pointer;">
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="enrollTime">Enrollment Time</label>
                                    <input type="time" id="enrollTime" name="enroll_time" style="cursor: pointer;">
                                </div>
                            </div>

                            <!-- <div class="enroll-fields">
                                <div class="confirmation">
                                    <label for="confirmation">
                                        We <strong>HEREBY CERTIFY</strong> that the above information is true and
                                        correct to the best of our knowledge <a href="#" id="openModal">Privacy
                                            Policy</a>.
                                    </label>
                                    <input title="confirmation" type="checkbox" id="confirmation">
                                </div>
                            </div> -->

                            <!-- Student ID Field -->
                            <input type="hidden" id="studentID" name="student_id" value="">

                            <!-- <button title="Next" type="button" class="nextBtn" id="nextToLoadingSubjectBtn" disabled>
                                <span class="btnText">Proceed to Loading Subjects</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button> -->

                            <button type="submit">Submit</button>

                            <style>
                                button.nextBtn {
                                    background-color: #007bff;
                                    color: white;
                                    padding: 10px 20px;
                                    border: none;
                                    border-radius: 5px;
                                    cursor: not-allowed !important;
                                    /* Default cursor */
                                    opacity: 0.6;
                                    /* Default look for disabled */
                                    transition: opacity 0.3s ease, cursor 0.3s ease;
                                }

                                button.nextBtn.enabled {
                                    cursor: pointer !important;
                                    /* Enabled state cursor */
                                    opacity: 1;
                                    /* Normal look for enabled */
                                }

                                button.nextBtn.enabled:hover {
                                    opacity: 0.7;
                                }
                            </style>

                            <script>
                                // Select the checkbox and button elements
                                const checkbox = document.getElementById('confirmation');
                                const button = document.getElementById('nextToLoadingSubjectBtn');

                                // Function to toggle the button state
                                function toggleButtonState() {
                                    if (checkbox.checked) {
                                        button.disabled = false; // Enable button
                                        button.classList.add('enabled'); // Add enabled class for styling
                                    } else {
                                        button.disabled = true; // Disable button
                                        button.classList.remove('enabled'); // Remove enabled class
                                    }
                                }

                                // Add an event listener to the checkbox
                                checkbox.addEventListener('change', toggleButtonState);

                                // Initial state (in case the checkbox starts unchecked)
                                toggleButtonState();
                            </script>


                        </div>
                    </div>
                </form>

                <!-- Loading Subjects Form -->
                <form action="display_enrollments.php" class="loadingSubjectForm" id="loadingSubjectForm" style="display: none;">
                    <div class="enroll-form first">
                        <div class="personal-details personal">
                            <span class="title">Loading Subjects</span>

                            <div class="enroll-fields">
                                <div class="enroll-input-fields">
                                    <label for="enrollDate">Date of Enrollment</label>
                                    <input type="date" name="enrollDate" disabled>
                                </div>

                                <div class="enrol-input-fields">
                                    <label for="1stSem">1st SEM</label>
                                    <input type="checkbox" id="1stSem" name="1stSem"
                                        onclick="handleLevelChange('1stSem')">
                                    <label for="2ndSem">2nd SEM</label>
                                    <input type="checkbox" id="2ndSem" name="2ndSem"
                                        onclick="handleLevelChange('2ndSem')">
                                    <label for="summer">SUMMER</label>
                                    <input type="checkbox" id="summer" name="2ndSem"
                                        onclick="handleLevelChange('summer')">
                                </div>

                                <div class="enrol-input-fields">
                                    <span>SY</span>
                                    <label for="enrollDate">20</label>
                                    <input type="text" name="enrollDate"
                                        style="width: 30px; height: 30px; border-radius: 3px; border: none; border: 1px solid #aaa; padding: 0 5px; font-size: 11px; outline: none;">
                                    <label for="enrollDate">- 20</label>
                                    <input type="text" name="enrollDate"
                                        style="width: 30px; height: 30px; border-radius: 3px; border: none; border: 1px solid #aaa; padding: 0 5px; font-size: 11px; outline: none;">
                                </div>
                            </div>

                            <span class="title">Status (Please Check)</span>

                            <div class="enroll-fields">
                                <div class="enrol-input-fields">
                                    <label for="new">NEW</label>
                                    <input type="checkbox" id="new" onclick="handleStatusChange('new')">

                                    <label for="old">OLD</label>
                                    <input type="checkbox" id="old" onclick="handleStatusChange('old')">

                                    <label for="transferee">TRANSFEREE</label>
                                    <input type="checkbox" id="transferee" onclick="handleStatusChange('transferee')">

                                    <label for="returnee">RETURNEE</label>
                                    <input type="checkbox" id="returnee" onclick="handleStatusChange('returnee')">

                                    <label for="crossEnrollee">CROSS ENROLLEE</label>
                                    <input type="checkbox" id="crossEnrollee"
                                        onclick="handleStatusChange('crossEnrollee')">
                                </div>
                            </div>

                            <div class="enroll-fields">
                                <div class="enroll-input-fields">
                                    <label for="firstName">First Name</label>
                                    <input type="text" disabled>
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="middleName">Middle Name</label>
                                    <input type="text" disabled>
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" disabled>
                                </div>
                            </div>

                            <div class="enroll-fields">
                                <div class="enroll-input-fields">
                                    <label for="studentID">Student ID#</label>
                                    <input type="text" id="studentID" disabled>
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="course">Course</label>
                                    <input type="text" id="course" placeholder="BSIT / BSBA / BSHM / BSTM" disabled>
                                </div>

                                <div class="enroll-input-fields">
                                    <label for="yearLevel">Year Level</label>
                                    <select id="yearLevel"
                                        style="height: 30px; border-radius: 3px; border: none; border: 1px solid #aaa; padding: 0 15px; font-size: 11px;">
                                        <option value="" disabled selected>Select Year Level</option>
                                        <option value="1stYear">1st Year</option>
                                        <option value="2ndYear">2nd Year</option>
                                        <option value="3rdYear">3rd Year</option>
                                        <option value="4thYear">4th Year</option>
                                    </select>
                                </div>
                            </div>

                            <span class="title">FIRST TERM SUBJECTS</span>

                            <div class="enroll-fields">
                                <div class="enrol-input-fields">
                                    <table class="first_term_subjects">
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px; color: #2e2e2e; font-weight: 500;">Subject
                                                    Code</th>
                                                <th style="font-size: 12px; color: #2e2e2e; font-weight: 500;">
                                                    Description</th>
                                                <th style="font-size: 12px; color: #2e2e2e; font-weight: 500;">Days</th>
                                                <th style="font-size: 12px; color: #2e2e2e; font-weight: 500;">Time</th>
                                                <th style="font-size: 12px; color: #2e2e2e; font-weight: 500;">Room No
                                                </th>
                                                <th style="font-size: 12px; color: #2e2e2e; font-weight: 500;">Units
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- New rows will be added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <button type="button" onclick="addRow('first_term_subjects')" style="margin-top: 10px;">Add
                                First Term Subject</button>

                            <span class="title">SECOND TERM SUBJECTS</span>

                            <div class="enroll-fields">
                                <div class="enrol-input-fields">
                                    <table class="second_term_subjects">
                                        <thead>
                                            <tr>
                                                <th style="font-size: 12px; color: #2e2e2e; font-weight: 500;">Subject
                                                    Code</th>
                                                <th style="font-size: 12px; color: #2e2e2e; font-weight: 500;">
                                                    Description</th>
                                                <th style="font-size: 12px; color: #2e2e2e; font-weight: 500;">Days</th>
                                                <th style="font-size: 12px; color: #2e2e2e; font-weight: 500;">Time</th>
                                                <th style="font-size: 12px; color: #2e2e2e; font-weight: 500;">Room No
                                                </th>
                                                <th style="font-size: 12px; color: #2e2e2e; font-weight: 500;">Units
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- New rows will be added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <button type="button" onclick="addRow('second_term_subjects')" style="margin-top: 10px;">Add
                                Second Term Subject</button>

                            <br>

                            <button title="Next" type="button" class="nextBtn" id="nextToPaymentBtn">
                                <span class="btnText">Proceed to Payment</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>

                        </div>
                    </div>
                </form>

                <!-- Payment Method Form -->
                <form action="" class="paymentMethodForm" id="paymentMethodForm" style="display: none;">
                    <h3>Payment Method</h3>
                    <!-- Payment Method Form Fields -->
                    <label for="paymentType">Select Payment Type:</label>
                    <select id="paymentType" required>
                        <option value="">Choose a payment method</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cash">Cash</option>
                    </select>
                    <button title="Submit" type="submit" class="submitBtn">
                        <span class="btnText">Submit Enrollment</span>
                        <i class="fa-solid fa-check"></i>
                    </button>
                </form>

                <div id="existingStudentPopup" style="display: none;">
                    <label for="existingStudentID">Enter Student ID:</label>
                    <input type="text" id="existingStudentID" placeholder="Enter Student ID">
                    <button id="submitExistingID">Submit</button>
                </div>

            </div>
        </div>
    </div>
    </div>
    <!-- MAIN CONTAINER -->

    <script>
        // Move to the loading subjects form
        document.getElementById('nextToLoadingSubjectBtn').addEventListener('click', () => {
            document.getElementById('enrollForm').style.display = 'none';
            document.getElementById('loadingSubjectForm').style.display = 'block';
        });

        // Move to the payment method form
        document.getElementById('nextToPaymentBtn').addEventListener('click', () => {
            document.getElementById('loadingSubjectForm').style.display = 'none';
            document.getElementById('paymentMethodForm').style.display = 'block';
        });

        function handleLevelChange(selectedId) {
            const levels = ['1stSem', '2ndSem', 'summer']

            levels.forEach(id => {
                if (id !== selectedId) document.getElementById(id).checked = false;
            });
        }

        function handleStatusChange(selectedId) {
            const status = ['old', 'new', 'transferee', 'returnee', 'crossEnrollee']

            status.forEach(id => {
                if (id !== selectedId) document.getElementById(id).checked = false;
            });
        }

        function generateStudentID() {
            const year = new Date().getFullYear().toString().slice(-2); // Get the last two digits of the year
            const gradeLevel = document.querySelector('input[name="grade_level_course"]:checked');
            const courseCollege = document.getElementById('courseCollege').value;
            const track11 = document.getElementById('trackGrade11').value;
            const track12 = document.getElementById('trackGrade12').value;
            const tvetTrack = document.getElementById('trackTvet').value;
            const lrnField = document.getElementById('lrn'); // LRN field

            let prefix = '';
            if (gradeLevel) {
                const value = gradeLevel.value;
                if (value.startsWith('Grade')) {
                    prefix = value === 'Grade 11' ? 'GR11' : value === 'Grade 12' ? 'GR12' : `GR${value.slice(-2)}`;
                } else if (value === 'COLLEGE') {
                    prefix = courseCollege;
                } else if (value === 'TVET') {
                    prefix = 'TVET';
                }
            }

            // Get the last student number dynamically (placeholder, fetch from backend/database)
            const lastNumber = 1; // Replace with the last student number fetched dynamically
            const studentNumber = String(lastNumber).padStart(2, '0'); // Ensure 2 digits (e.g., 01, 02)

            // Generate final ID
            const studentID = `${prefix}${year}${studentNumber}`;
            document.getElementById('studentID').value = studentID;

            // Enable LRN field for Grade 7 to Grade 12, otherwise disable it
            if (gradeLevel && gradeLevel.value.startsWith('Grade')) {
                lrnField.disabled = false;
            } else {
                lrnField.disabled = true;
                lrnField.value = ''; // Clear LRN value when disabled
            }
        }

        function handleCheckboxChange(id) {
            const checkbox = document.getElementById(id);
            const trackField = document.getElementById(`track${id.charAt(0).toUpperCase() + id.slice(1)}`);
            const courseField = document.getElementById('courseCollege');
            const courseLevelField = document.getElementById('courseLevel');
            const lrnField = document.getElementById('lrn'); // LRN field

            // Deselect other checkboxes based on selected one
            const gradeLevels = ['grade7', 'grade8', 'grade9', 'grade10', 'grade11', 'grade12'];
            const tvetAndCollege = ['tvet', 'college'];

            gradeLevels.forEach((item) => {
                if (item !== id) document.getElementById(item).checked = false;
            });
            tvetAndCollege.forEach((item) => {
                if (item !== id) document.getElementById(item).checked = false;
            });

            // Disable all select fields initially
            document.getElementById('trackGrade11').disabled = true;
            document.getElementById('trackGrade12').disabled = true;
            document.getElementById('trackTvet').disabled = true;
            document.getElementById('courseCollege').disabled = true;
            document.getElementById('courseLevel').disabled = true;

            // Enable the LRN field only for grade levels
            if (['grade7', 'grade8', 'grade9', 'grade10', 'grade11', 'grade12'].includes(id)) {
                lrnField.disabled = false;
            } else {
                lrnField.disabled = true;
                lrnField.value = ''; // Clear LRN value when disabled
            }

            // Enable the select field associated with the checked checkbox
            if (id === 'grade11') {
                document.getElementById('trackGrade11').disabled = false;
            } else if (id === 'grade12') {
                document.getElementById('trackGrade12').disabled = false;
            } else if (id === 'tvet') {
                document.getElementById('trackTvet').disabled = false;
            } else if (id === 'college') {
                document.getElementById('courseCollege').disabled = false;
                document.getElementById('courseLevel').disabled = false;
            }

            // Trigger student ID generation
            generateStudentID();
        }

        function validateEnrollForm() {
            return true;
        }

        function setCurrentDateTime() {
            const dateField = document.getElementById('enrollDate');
            const timeField = document.getElementById('enrollTime');

            const now = new Date();

            dateField.value = now.toISOString().split('T')[0];

            timeField.value = now.toTimeString().slice(0, 5);
        }

        window.onload = setCurrentDateTime;

        function formatPhoneNumber(input) {

            let value = input.value.replace(/\D/g, '');

            if (value.length > 3 && value.length <= 7) {
                value = value.replace(/(\d{4})(\d{1,3})/, '$1-$2');
            } else if (value.length > 7) {
                value = value.replace(/(\d{4})(\d{3})(\d{1,4})/, '$1-$2-$3');
            }

            input.value = value;
        }
    </script>
    
</body>

</html>