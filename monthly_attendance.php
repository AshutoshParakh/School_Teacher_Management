<?php
session_start();
include 'config.php';  

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header('Location: teacher_login.php'); 
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch classes assigned to the teacher
$query = "SELECT c.id, c.name FROM classes c 
          INNER JOIN teacher_classes tc ON c.id = tc.class_id 
          WHERE tc.teacher_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class'];
    $total_classroom_days = $_POST['total_classroom_days'];
    $average_attendance = $_POST['average_attendance'];
    $students_absent = $_POST['students_absent'];

    $insert_query = "INSERT INTO attendance_reports (teacher_id, class_id, total_classroom_days, average_attendance, students_with_3_or_more_absences)
                     VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iiiss", $teacher_id, $class_id, $total_classroom_days, $average_attendance, $students_absent);
    
    if ($stmt->execute()) {
        echo "<script>alert('Attendance report submitted successfully!'); window.location.href = 'report_options.php';</script>";
    } else {
        echo "<script>alert('Error submitting the attendance report. Please try again later.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Attendance</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Monthly Attendance</h1>
        <form method="POST" action="monthly_attendance.php">
            <div class="form-group">
                <label for="class">Select Class:</label>
                <select name="class" id="class" required>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="total_classroom_days">Total Number of Classroom Days:</label>
                <input type="number" name="total_classroom_days" id="total_classroom_days" required style="width: 90%; padding: 10px;
                margin-bottom: 10px;border: 1px solid #ccc;border-radius: 5px;font-size: 16px;">
            </div>

            <div class="form-group">
                <label for="average_attendance">Average Attendance (%):</label>
                <input type="number" step="0.1" name="average_attendance" id="average_attendance" required style="width: 90%; padding: 10px;
                margin-bottom: 10px;border: 1px solid #ccc;border-radius: 5px;font-size: 16px;">
            </div>

            <div class="form-group">
                <label for="students_absent">Students with 3 or More Absences:</label>
                <textarea name="students_absent" id="students_absent" rows="3" required style="width: 90%; padding: 10px;
                margin-bottom: 10px;border: 1px solid #ccc;border-radius: 5px;font-size: 16px;"></textarea>
            </div>

            <button type="submit" class="styled-button">Submit</button>
        </form>
        <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
