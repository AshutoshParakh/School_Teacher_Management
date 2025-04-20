<?php
session_start();

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header('Location: teacher_login.php'); // Redirect to login page if not logged in
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Options</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Choose Report Type</h1>
        <div class="options-container">
            <a href="monthly_attendance.php" class="styled-button">Monthly Attendance</a>
            <a href="assembly_performance.php" class="styled-button">Assembly Performance</a>
            <a href="subjective_report.php" class="styled-button">Subjective Report</a>
        </div>
        <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
