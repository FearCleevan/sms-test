<?php
session_start();
include("connect.php");

// Prevent caching to ensure the page is not cached and always fresh on each load
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login page if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="admin-dashboard.css">
    <link rel="stylesheet" href="user-info.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-header">
        <div class="header-container">
            <div class="header-left">
                <div class="header-value-left">
                    <a href="#"><img src="./image/apple-touch-icon.png" alt="logo"></a>
                    Samson Admin Dashboard
                </div>
            </div>
            <div class="header-right">
                <div class="header-value-right">
                    <div class="profile-image">
                        <a href="javascript:void(0);" id="profile-link">
                            <?php
                            // Check if session username is set
                            if (isset($_SESSION['username'])) {
                                $email = $_SESSION['username'];
                                $query = mysqli_query($conn, "SELECT * FROM admin_user WHERE username='$email'");

                                if ($row = mysqli_fetch_assoc($query)) {
                                    // Display profile image if it exists
                                    if (!empty($row['profile'])) {
                                        echo '<img src="' . htmlspecialchars($row['profile']) . '" alt="Profile Image">';
                                    } else {
                                        echo '<img src="./uploads/default-profile.jpg" alt="Default Profile Image">'; // Default image if no profile is found
                                    }
                                } else {
                                    echo '<img src="./uploads/default-profile.jpg" alt="Default Profile Image">'; // Default image for failed query
                                }
                            } else {
                                echo '<img src="./uploads/default-profile.jpg" alt="Default Profile Image">'; // Default image if no session
                            }
                            ?>
                        </a>
                    </div>

                    <div class="name-access">
                        <?php
                        // Check if session username is set
                        if (isset($_SESSION['username'])) {
                            $email = $_SESSION['username'];
                            $query = mysqli_query($conn, "SELECT * FROM admin_user WHERE username='$email'");

                            if ($row = mysqli_fetch_assoc($query)) {
                                // Display the first and last name with a space between them
                                echo "<p>" . htmlspecialchars($row['firstName']) . " " . htmlspecialchars($row['lastName']) . "</p>";

                                // Display the access level (admin or teacher)
                                if (!empty($row['access'])) {
                                    echo "<span>" . htmlspecialchars($row['access']) . "</span>";
                                }
                            } else {
                                echo "<p>Guest</p><span>Guest</span>";
                            }
                        } else {
                            echo "<p>Guest</p><span>Guest</span>";
                        }
                        ?>
                    </div>

                    <!-- Dropdown Menu -->
                    <div class="profile-menu" id="profile-menu">
                        <ul>
                            <li><a href="user-info.html"><i class="fas fa-cogs"></i> Account Settings</a></li>
                            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="user-info-container">
        <div class="user-info-header">
            <h2>User Information</h2>
        </div>
        <div class="user-info-content">
            <div class="user-info-form">
                <label>Email Address</label>
                <input title="wara" type="email" value="PPALAZAN@FASTGROUP.BIZ" readonly>
                
                <label>Password</label>
                <div class="password-field">
                    <span>Click here to change password</span>
                </div>
                
                <label>First Name</label>
                <input title="wara" type="text" value="PETER PAUL" readonly>
                
                <label>Middle Name</label>
                <input title="wara" type="text" value="ABILLAR" readonly>
                
                <label>Last Name</label>
                <input title="wara" type="text" value="LAZAN" readonly>
                
                <label>Business Unit</label>
                <input title="wara" type="text" value="DAVAO OPERATIONS" readonly>
                
                <label>User Type</label>
                <select title="wara" disabled>
                    <option selected>User</option>
                </select>
                
                <label>Status</label>
                <select title="wara" disabled>
                    <option selected>Active</option>
                </select>
            </div>
            <div class="user-profile-picture">
                <img src="profile-placeholder.png" alt="Profile Picture">
            </div>
        </div>
    </div>

    <script>
        // Get elements
        const profileLink = document.getElementById("profile-link");
        const profileMenu = document.getElementById("profile-menu");

        // Toggle the visibility of the profile menu when the profile image is clicked
        profileLink.addEventListener("click", function(e) {
            e.preventDefault(); // Prevent default link behavior
            profileMenu.style.display = profileMenu.style.display === "block" ? "none" : "block";
        });

        // Close the profile menu if clicking anywhere outside of the menu or the profile image
        window.addEventListener("click", function(e) {
            if (!profileLink.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.style.display = "none";
            }
        });
    </script>
    
</body>
</html>