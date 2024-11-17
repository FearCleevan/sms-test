<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        <form method="post" action="admin-registration.php" enctype="multipart/form-data">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fName" id="fName" placeholder="First Name" required>
                <label for="fName">First Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                <label for="lName">Last Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="text" name="username" id="username" placeholder="Email" required>
                <label for="username">Username</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="file" name="profile" id="profile" placeholder="Profile" required>
                <label for="profile">Profile</label>
            </div>
            <div class="input-group">
                <i class="fas fa-cogs"></i>
                <select name="access" id="access" required>
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                </select>
                <label for="access">Access</label>
            </div>
            <input type="submit" class="btn" value="Sign Up" name="signUp">
        </form>

    </div>
</body>

</html>