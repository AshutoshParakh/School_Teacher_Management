<?php
// Security check to ensure only logged-in admins can access
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .dashboard-container h1 {
            margin-bottom: 20px;
            color: #4CAF50;
        }

        .welcome-msg {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .styled-button {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            color: #fff;
            background-color: #4CAF50;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .styled-button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .logout-button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #f44336;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            box-shadow: 0 2px 5px rgba(109, 235, 81, 0.2);
        }

        .logout-button:hover {
            background-color: #e53935;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>
        <p class="welcome-msg">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</p>
        <div class="button-group">
            <a href="add_teacher.php" class="styled-button">Add Teacher</a>
            <a href="add_classes.php" class="styled-button">Add Class</a>
            <a href="assign_classes.php" class="styled-button">Assign Classes</a>
            <a href="view_teachers.php" class="styled-button">View Teacher Details</a>
            <a href="admin_subjective_reports.php" class="styled-button">View Subjective Report</a>
            <a href="admin_view_reports.php" class="styled-button">View All Report</a>
        </div>
        <!-- Logout button -->
        <form method="POST" action="logout.php">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
</body>
</html>
