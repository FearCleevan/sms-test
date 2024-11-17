<?php
// Include database connection file
include 'connect.php';

// Query to fetch all enrollment data from the database, including the new fields
$query = "SELECT student_id, grade_level, track, course, course_level, lrn, profile, first_name, middle_name, last_name, email, phone, username, password, address, province, zip_code, city, emergency_name, emergency_phone, relation, enroll_date, enroll_time FROM enrollments";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Initialize tables for each category
    $grade7_10Table = '';
    $grade11_12Table = '';
    $tvetTable = '';
    $collegeTable = '';

    // Loop through the result set and categorize the data into the respective tables
    while ($row = $result->fetch_assoc()) {
        $student_id = $row['student_id'];
        $grade_level = $row['grade_level'];
        $track = $row['track'];
        $course = $row['course'];
        $course_level = $row['course_level'];
        $lrn = $row['lrn'];
        $profileImage = $row['profile']; // Image stored as binary data
        $first_name = $row['first_name'];
        $middle_name = $row['middle_name'];
        $last_name = $row['last_name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $username = $row['username'];
        $password = $row['password'];
        $address = $row['address'];
        $province = $row['province'];
        $zip_code = $row['zip_code'];
        $city = $row['city'];
        $emergency_name = $row['emergency_name'];
        $emergency_phone = $row['emergency_phone'];
        $relation = $row['relation'];
        $enroll_date = $row['enroll_date'];
        $enroll_time = $row['enroll_time'];

        // Convert the binary image data to base64 for inline display
        if ($profileImage) {
            $base64Image = base64_encode($profileImage);
            $imageSrc = 'data:image/jpeg;base64,' . $base64Image;
            $profileImgTag = "<img src='$imageSrc' alt='Profile Image' width='50' height='50'>";
        } else {
            $profileImgTag = "No image available";
        }

        // Categorize the record based on grade level and other parameters
        if (in_array($grade_level, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])) {
            $grade7_10Table .= "<tr>
                                    <td>$student_id</td>
                                    <td>$profileImgTag</td>
                                    <td>$first_name $middle_name $last_name</td>
                                    <td>$email</td>
                                    <td>$phone</td>
                                    <td>$username</td>
                                    <td>$grade_level</td>
                                    <td>$lrn</td>
                                    <td>$address</td>
                                    <td>$province</td>
                                    <td>$zip_code</td>
                                    <td>$city</td>
                                    <td>$emergency_name</td>
                                    <td>$emergency_phone</td>
                                    <td>$relation</td>
                                    <td>$enroll_date</td>
                                    <td>$enroll_time</td>
                                </tr>";
        } elseif (in_array($grade_level, ['Grade 11', 'Grade 12'])) {
            $grade11_12Table .= "<tr>
                                    <td>$student_id</td>
                                    <td>$profileImgTag</td>
                                    <td>$first_name $middle_name $last_name</td>
                                    <td>$email</td>
                                    <td>$phone</td>
                                    <td>$username</td>
                                    <td>$grade_level</td>
                                    <td>$track</td>
                                    <td>$lrn</td>
                                    <td>$address</td>
                                    <td>$province</td>
                                    <td>$zip_code</td>
                                    <td>$city</td>
                                    <td>$emergency_name</td>
                                    <td>$emergency_phone</td>
                                    <td>$relation</td>
                                    <td>$enroll_date</td>
                                    <td>$enroll_time</td>
                                </tr>";
        } elseif ($grade_level == 'TVET') {
            $tvetTable .= "<tr>
                             <td>$student_id</td>
                             <td>$profileImgTag</td>
                             <td>$first_name $middle_name $last_name</td>
                             <td>$email</td>
                             <td>$phone</td>
                             <td>$username</td>
                             <td>$grade_level</td>
                             <td>$track</td>
                             <td>$address</td>
                             <td>$province</td>
                             <td>$zip_code</td>
                             <td>$city</td>
                             <td>$emergency_name</td>
                             <td>$emergency_phone</td>
                             <td>$relation</td>
                             <td>$enroll_date</td>
                             <td>$enroll_time</td>
                           </tr>";
        } elseif ($grade_level == 'COLLEGE') {
            $collegeTable .= "<tr>
                                <td>$student_id</td>
                                <td>$profileImgTag</td>
                                <td>$first_name $middle_name $last_name</td>
                                <td>$email</td>
                                <td>$phone</td>
                                <td>$username</td>
                                <td>$grade_level</td>
                                <td>$course</td>
                                <td>$course_level</td>
                                <td>$address</td>
                                <td>$province</td>
                                <td>$zip_code</td>
                                <td>$city</td>
                                <td>$emergency_name</td>
                                <td>$emergency_phone</td>
                                <td>$relation</td>
                                <td>$enroll_date</td>
                                <td>$enroll_time</td>
                              </tr>";
        }
    }
    
    // Display the tables
    if ($grade7_10Table) {
        echo "<h3>Grade 7 to Grade 10 Enrollments</h3>";
        echo "<table border='1'>
                <tr>
                    <th>Student ID</th>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Username</th>
                    <th>Grade Level</th>
                    <th>LRN</th>
                    <th>Address</th>
                    <th>Province</th>
                    <th>Zip Code</th>
                    <th>City</th>
                    <th>Emergency Name</th>
                    <th>Emergency Phone</th>
                    <th>Relation</th>
                    <th>Enrollment Date</th>
                    <th>Enrollment Time</th>
                </tr>
                $grade7_10Table
              </table><br>";
    }
    
    if ($grade11_12Table) {
        echo "<h3>Grade 11 and Grade 12 Enrollments</h3>";
        echo "<table border='1'>
                <tr>
                    <th>Student ID</th>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Username</th>
                    <th>Grade Level</th>
                    <th>Track</th>
                    <th>LRN</th>
                    <th>Address</th>
                    <th>Province</th>
                    <th>Zip Code</th>
                    <th>City</th>
                    <th>Emergency Name</th>
                    <th>Emergency Phone</th>
                    <th>Relation</th>
                    <th>Enrollment Date</th>
                    <th>Enrollment Time</th>
                </tr>
                $grade11_12Table
              </table><br>";
    }
    
    if ($tvetTable) {
        echo "<h3>TVET Enrollments</h3>";
        echo "<table border='1'>
                <tr>
                    <th>Student ID</th>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Username</th>
                    <th>Grade Level</th>
                    <th>Track</th>
                    <th>Address</th>
                    <th>Province</th>
                    <th>Zip Code</th>
                    <th>City</th>
                    <th>Emergency Name</th>
                    <th>Emergency Phone</th>
                    <th>Relation</th>
                    <th>Enrollment Date</th>
                    <th>Enrollment Time</th>
                </tr>
                $tvetTable
              </table><br>";
    }
    
    if ($collegeTable) {
        echo "<h3>College Enrollments</h3>";
        echo "<table border='1'>
                <tr>
                    <th>Student ID</th>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Username</th>
                    <th>Grade Level</th>
                    <th>Course</th>
                    <th>Course Level</th>
                    <th>Address</th>
                    <th>Province</th>
                    <th>Zip Code</th>
                    <th>City</th>
                    <th>Emergency Name</th>
                    <th>Emergency Phone</th>
                    <th>Relation</th>
                    <th>Enrollment Date</th>
                    <th>Enrollment Time</th>
                </tr>
                $collegeTable
              </table><br>";
    }
} else {
    echo "No enrollment data found.";
}

$conn->close();
?>
