<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $date_of_admission = $_POST['date_of_admission'];
    $status = $_POST['status'];
    $family_name = $_POST['family_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];
    $first_term_subjects = json_encode($_POST['first_term_subjects']); // Convert to JSON
    $second_term_subjects = json_encode($_POST['second_term_subjects']); // Convert to JSON

    // Prepare a prepared statement to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO student_enrollment (date_of_admission, status, family_name, first_name, middle_name, course, year_level, first_term_subjects, second_term_subjects) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("sssssssss", $date_of_admission, $status, $family_name, $first_name, $middle_name, $course, $year_level, $first_term_subjects, $second_term_subjects);

    // Execute the query
    if ($stmt->execute()) {
        echo "Record saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form Clone</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="form-container">
        <!-- Header -->
        <div class="header">
            <div>SAMSON POLYTECHNIC COLLEGE OF DAVAO</div>
            <div>Formerly SAMSON TECHNICAL INSTITUTE</div>
            <div>Magayaysay Avenue corner Chavez Street, Davao</div>
            <div>REGISTRATION FORM</div>
            <div><small>Completely Fill-out this Form</small></div>
        </div>

        <!-- Form Fields -->
        <form action="" method="POST">
            <div class="form-loading-subjects">
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label>Date of Admission/Enrollment:</label>
                        <input type="date" name="date_of_admission">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Status:</label>
                        <input type="text" placeholder="New / Old / Transferee / Returnee / Cross Enrollee" name="status">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label>Family Name:</label>
                        <input type="text" name="family_name">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>First Name:</label>
                        <input type="text" name="first_name">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Middle Name:</label>
                        <input type="text" name="middle_name">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label>Course (please check):</label>
                        <input type="text" placeholder="BSIT / BSBA-OM / BSHRM / BSTM" name="course">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Year Level:</label>
                        <input type="text" placeholder="1st / 2nd / 3rd / 4th" name="year_level">
                    </div>
                </div>

                <!-- First Term Table -->
                <h2>First Term Subjects</h2>
                <table class="first_term_subjects">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Description</th>
                            <th>Days</th>
                            <th>Time</th>
                            <th>Room No</th>
                            <th>Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- New rows will be added here -->
                    </tbody>
                </table>

                <button type="button" onclick="addRow('first_term_subjects')">Add First Term Subject</button>

                <!-- Second Term Table -->
                <h2>Second Term Subjects</h2>
                <table class="second_term_subjects">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Description</th>
                            <th>Days</th>
                            <th>Time</th>
                            <th>Room No</th>
                            <th>Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- New rows will be added here -->
                    </tbody>
                </table>

                <button type="button" onclick="addRow('second_term_subjects')">Add Second Term Subject</button>


                <input type="submit" value="Submits">
            </div>
        </form>

        <?php if (isset($success_message)) { ?>
            <div class="success-message">
                <h3><?php echo $success_message; ?></h3>
                <h4>Submitted Data:</h4>
                <ul>
                    <li>Date of Admission: <?php echo htmlspecialchars($date_of_admission); ?></li>
                    <li>Status: <?php echo htmlspecialchars($status); ?></li>
                    <li>Family Name: <?php echo htmlspecialchars($family_name); ?></li>
                    <li>First Name: <?php echo htmlspecialchars($first_name); ?></li>
                    <li>Middle Name: <?php echo htmlspecialchars($middle_name); ?></li>
                    <li>Course: <?php echo htmlspecialchars($course); ?></li>
                    <li>Year Level: <?php echo htmlspecialchars($year_level); ?></li>
                    <li>First Term Subjects: <?php echo htmlspecialchars($first_term_subjects); ?></li>
                    <li>Second Term Subjects: <?php echo htmlspecialchars($second_term_subjects); ?></li>
                </ul>
            </div>
        <?php } elseif (isset($error_message)) { ?>
            <div class="error-message">
                <h3><?php echo $error_message; ?></h3>
            </div>
        <?php } ?>
    </div>

    <script>
        // Function to add a new row to the table
        function addRow(term) {
            const table = document.querySelector(`table.${term} tbody`);
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" style="width: 100px; height: 30px;" name="${term}[][subject_code]"></td>
                <td><input type="text" style="width: 600px; height: 30px;" name="${term}[][description]"></td>
                <td><input type="text" style="width: 100px; height: 30px;" name="${term}[][days]"></td>
                <td><input type="text" style="width: 140px; height: 30px;" name="${term}[][time]"></td>
                <td><input type="text" style="width: 80px; height: 30px;" name="${term}[][room_no]"></td>
                <td><input type="text" style="width: 80px; height: 30px;" name="${term}[][units]"></td>
            `;
            table.appendChild(newRow);
        }
    </script>

</body>

</html>