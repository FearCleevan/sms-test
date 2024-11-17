<?php

include "connect.php";

if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $access = $_POST['access'];
    $password = md5($password);

    // Handle file upload
    $targetDir = "uploads/"; // Directory where the file will be saved
    $fileName = basename($_FILES["profile"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Check if file is an image
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
    if (in_array(strtolower($fileType), $allowedTypes)) {
        // Upload file to server
        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFilePath)) {
            // File uploaded successfully, now save user data in database
            $checkEmail = "SELECT * FROM admin_user WHERE username='$username'";
            $result = $conn->query($checkEmail);
            
            if ($result->num_rows > 0) {
                echo "Username already exists!";
            } else {
                $insertQuery = "INSERT INTO admin_user (firstName, lastName, username, password, profile, access) VALUES ('$firstName', '$lastName', '$username', '$password', '$targetFilePath', '$access')";
                if ($conn->query($insertQuery) === TRUE) {
                    header("Location: admin-login.php");
                    exit();
                } else {
                    echo "Error: " . $conn->error;
                }
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
}

if (isset($_POST['signIn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = md5($password);

    $sql = "SELECT * FROM admin_user WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Not Found";
    }
}

?>
