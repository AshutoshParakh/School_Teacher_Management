<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.html");
    exit;
}

// Display admin dashboard
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo $_SESSION['admin_name']; ?>!</h1>
        <h2>Admin Dashboard</h2>
        <form action="add_teacher.php" method="POST" class="styled-form">
            <h3>Add a New Teacher</h3>
            <div class="form-group">
                <label for="name">Teacher Name</label>
                <input type="text" id="name" name="name" placeholder="Enter the teacher's name" required>
            </div>
            <div class="form-group">
                <label for="email">Teacher Email</label>
                <input type="email" id="email" name="email" placeholder="Enter the teacher's email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter a secure password" required>
            </div>
            <button type="submit" class="styled-button">Add Teacher</button>
        </form>
    </div>
</body>
</html>
