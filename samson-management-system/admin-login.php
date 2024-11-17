<?php
session_start();
include("connect.php");

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = md5($password); // Encrypt password

    // Check if user exists in the database
    $sql = "SELECT * FROM admin_user WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Set session variable for logged-in user
        $_SESSION['username'] = $username;

        // If 'Remember Me' is checked, set a cookie for username
        if (isset($_POST['remember_me'])) {
            setcookie('remember_username', $username, time() + (86400 * 30), "/"); // Expires in 30 days
        } else {
            setcookie('remember_username', '', time() - 3600, "/"); // Remove cookie if not checked
        }

        // Redirect to dashboard after successful login
        header("Location: admin-dashboard.php");
        exit();
    } else {
        // Store error message in session if login fails
        $_SESSION['error'] = "Invalid username or password.";
    }
}

// Clear the error message after the page is loaded
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']); // Unset the error message so it doesn't persist
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samson Polytechnic College of Davao - Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin-login.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="./image/apple-touch-icon.png" alt="logo">
            <h4>Samson Polytechnic College of Davao - Admin Login</h4>
        </div>
        <div class="login-content">
            <form action="" method="POST"> <!-- Form for login -->
                <div class="login-form">
                    <div class="input-container">
                        <i class="fa fa-user"></i>
                        <input type="text" name="username" placeholder="Username" value="<?php echo isset($_COOKIE['remember_username']) ? htmlspecialchars($_COOKIE['remember_username']) : ''; ?>" required>
                    </div>
                    <div class="input-container">
                        <i class="fa fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <!-- Display error message if set -->
                    <?php if (isset($error)) { echo "<p style='color:red; font-size: 10px; padding: 0;'>$error</p>"; } ?>
                    <div class="remember-me">
                        <input type="checkbox" name="remember_me" id="remember-me" <?php echo isset($_COOKIE['remember_username']) ? 'checked' : ''; ?>>
                        <label for="remember-me">Remember me?</label>
                    </div>
                    <button type="submit" name="login" class="login-btn">Log in</button>
                </div>
            </form>
            
            <div class="login-info">
                <h3>School Management System:</h3>
                <ul>
                    <li><i class="fa fa-check-circle"></i>Student Enrollment</li>
                    <li><i class="fa fa-check-circle"></i>Attendance Management</li>
                    <li><i class="fa fa-check-circle"></i>Grading System</li>
                    <li><i class="fa fa-check-circle"></i>Payment Management</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
