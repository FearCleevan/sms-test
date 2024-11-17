<?php
session_start();
include("connect.php");

// Fetching the data from the database
$sql = "SELECT * FROM student_enrollment";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Create an array to hold the fetched rows
    $enrollments = [];
    while ($row = $result->fetch_assoc()) {
        // Decode the JSON data
        $row['first_term_subjects'] = json_decode($row['first_term_subjects'], true);
        $row['second_term_subjects'] = json_decode($row['second_term_subjects'], true);
        $enrollments[] = $row;
    }
} else {
    $enrollments = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Data</title>
    <link rel="stylesheet" href="index.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        li {
            margin-bottom: 5px;
        }

        .subject-info {
            display: flex;
            flex-wrap: wrap;
            /* Allow content to wrap to the next line if necessary */
            gap: 10px;
            /* Space between the flex items */
            margin-bottom: 10px;
            /* Space between each subject */
        }

        .subject-info span {
            display: inline-block;
            min-width: 120px;
            /* Minimum width for each piece of subject info */
            padding: 5px;
            background-color: #f4f4f4;
            /* Background for each item */
            border-radius: 5px;
            /* Rounded corners for each field */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            /* Light shadow for each field */
        }
    </style>
</head>

<body>
    <div class="table-container">
        <h2>Enrollment Data</h2>
        <?php if (count($enrollments) > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date of Admission</th>
                        <th>Status</th>
                        <th>Family Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>First Term Subjects</th>
                        <th>Second Term Subjects</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $enrollment) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($enrollment['id']); ?></td>
                            <td><?php echo htmlspecialchars($enrollment['date_of_admission']); ?></td>
                            <td><?php echo htmlspecialchars($enrollment['status']); ?></td>
                            <td><?php echo htmlspecialchars($enrollment['family_name']); ?></td>
                            <td><?php echo htmlspecialchars($enrollment['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($enrollment['middle_name']); ?></td>
                            <td><?php echo htmlspecialchars($enrollment['course']); ?></td>
                            <td><?php echo htmlspecialchars($enrollment['year_level']); ?></td>
                            <td>
                                <?php
                                if (!empty($enrollment['first_term_subjects'])) {
                                    echo "<ul>";
                                    foreach ($enrollment['first_term_subjects'] as $subject) {
                                        // Check if each field is set and not empty, otherwise skip displaying that field
                                        $subject_code = isset($subject['subject_code']) && !empty($subject['subject_code']) ? htmlspecialchars($subject['subject_code']) : '';
                                        $description = isset($subject['description']) && !empty($subject['description']) ? htmlspecialchars($subject['description']) : '';
                                        $days = isset($subject['days']) && !empty($subject['days']) ? htmlspecialchars($subject['days']) : '';
                                        $time = isset($subject['time']) && !empty($subject['time']) ? htmlspecialchars($subject['time']) : '';
                                        $room_no = isset($subject['room_no']) && !empty($subject['room_no']) ? htmlspecialchars($subject['room_no']) : '';
                                        $units = isset($subject['units']) && !empty($subject['units']) ? htmlspecialchars($subject['units']) : '';

                                        // Only display elements that have values
                                        echo "<li>
                                                <div class='subject-info'>";

                                        if ($subject_code) {
                                            echo "<span><strong>Code:</strong> $subject_code</span>";
                                        }
                                        if ($description) {
                                            echo "<span><strong>Description:</strong> $description</span>";
                                        }
                                        if ($days) {
                                            echo "<span><strong>Days:</strong> $days</span>";
                                        }
                                        if ($time) {
                                            echo "<span><strong>Time:</strong> $time</span>";
                                        }
                                        if ($room_no) {
                                            echo "<span><strong>Room:</strong> $room_no</span>";
                                        }
                                        if ($units) {
                                            echo "<span><strong>Units:</strong> $units</span>";
                                        }

                                        echo "</div>
                                        </li>";
                                    }
                                    echo "</ul>";
                                } else {
                                    echo "No subjects listed.";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (!empty($enrollment['second_term_subjects'])) {
                                    echo "<ul>";
                                    foreach ($enrollment['second_term_subjects'] as $subject) {
                                        // Check if each field is set and not empty, otherwise skip displaying that field
                                        $subject_code = isset($subject['subject_code']) && !empty($subject['subject_code']) ? htmlspecialchars($subject['subject_code']) : '';
                                        $description = isset($subject['description']) && !empty($subject['description']) ? htmlspecialchars($subject['description']) : '';
                                        $days = isset($subject['days']) && !empty($subject['days']) ? htmlspecialchars($subject['days']) : '';
                                        $time = isset($subject['time']) && !empty($subject['time']) ? htmlspecialchars($subject['time']) : '';
                                        $room_no = isset($subject['room_no']) && !empty($subject['room_no']) ? htmlspecialchars($subject['room_no']) : '';
                                        $units = isset($subject['units']) && !empty($subject['units']) ? htmlspecialchars($subject['units']) : '';

                                        // Only display elements that have values
                                        echo "<li>
                                                <div class='subject-info'>";

                                        if ($subject_code) {
                                            echo "<span><strong>Code:</strong> $subject_code</span>";
                                        }
                                        if ($description) {
                                            echo "<span><strong>Description:</strong> $description</span>";
                                        }
                                        if ($days) {
                                            echo "<span><strong>Days:</strong> $days</span>";
                                        }
                                        if ($time) {
                                            echo "<span><strong>Time:</strong> $time</span>";
                                        }
                                        if ($room_no) {
                                            echo "<span><strong>Room:</strong> $room_no</span>";
                                        }
                                        if ($units) {
                                            echo "<span><strong>Units:</strong> $units</span>";
                                        }

                                        echo "</div></li>";
                                    }
                                    echo "</ul>";
                                } else {
                                    echo "No subjects listed.";
                                }
                                ?>


                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No enrollment data found.</p>
        <?php } ?>
    </div>
</body>

</html>