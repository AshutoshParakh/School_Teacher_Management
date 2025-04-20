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
    $date = $_POST['date'];
    $activity = $_POST['activity'];
    $students_participated = $_POST['students_participated'];

    $insert_query = "INSERT INTO assembly_performance_reports (teacher_id, class_id, date, activity, students_participated)
                     VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iisss", $teacher_id, $class_id, $date, $activity, $students_participated);
    
    if ($stmt->execute()) {
        echo "<script>alert('Assembly performance report submitted successfully!'); window.location.href = 'report_options.php';</script>";
    } else {
        echo "<script>alert('Error submitting the assembly performance report. Please try again later.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assembly Performance</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Assembly Performance</h1>
        <form method="POST" action="assembly_performance.php">
            <div class="form-group">
                <label for="class">Select Class:</label>
                <select name="class" id="class" required>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" required style="width: 90%; padding: 10px;
                margin-bottom: 10px;border: 1px solid #ccc;border-radius: 5px;font-size: 16px;">
            </div>

            <div class="form-group">
                <label for="activity">Activity:</label>
                <textarea name="activity" id="activity" rows="3" required style="width: 90%; padding: 10px;
                margin-bottom: 10px;border: 1px solid #ccc;border-radius: 5px;font-size: 16px;"></textarea>
            </div>

            <div class="form-group">
                <label for="students_participated">Students Participated:</label>
                <textarea name="students_participated" id="students_participated" rows="3" required style="width: 90%; padding: 10px;
                margin-bottom: 10px;border: 1px solid #ccc;border-radius: 5px;font-size: 16px;"></textarea>
            </div>

            <button type="submit" class="styled-button">Submit</button>
        </form>
        <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
