<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

$teacherId = $_SESSION['teacher_id'];
$teacherName = $_SESSION['teacher_name']; // Assuming this is set during login
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('your-background-image.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 100px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .welcome {
            text-align: center;
            margin-bottom: 30px;
            color: #555;
            font-size: 18px;
        }
        .options {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .option {
            flex: 1;
            text-align: center;
            padding: 20px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: background 0.3s;
        }
        .option:hover {
            background: #0056b3;
        }
        .logout {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #d9534f;
            text-decoration: none;
            font-size: 16px;
        }
        .logout:hover {
            color: #c9302c;
        }
    </style>
     <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Teacher Dashboard</h1>
        <p class="welcome">Welcome, <?php echo htmlspecialchars($teacherName); ?>!</p>
        <div class="options">
            <a href="class_teacher_report.php" class="option">Class Teacher Report</a>
            <a href="subjective_report.php" class="option">Subjective Report</a>
        </div>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</body>
</html>
