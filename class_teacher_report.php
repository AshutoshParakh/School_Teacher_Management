<?php
session_start();
include 'config.php';  

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header('Location: teacher_login.php'); 
    exit();
}

// Fetch classes assigned to the teacher
$teacher_id = $_SESSION['teacher_id'];
$query = "SELECT c.id, c.name FROM classes c 
          INNER JOIN teacher_classes tc ON c.id = tc.class_id 
          WHERE tc.teacher_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch current academic session
$academic_sessions = ['2024-2025', '2025-2026', '2026-2027']; 

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $class_id = $_POST['class'];
    $academic_session = $_POST['academic_session'];
    $month = $_POST['month'];
    $class_teacher_name = $_POST['class_teacher'];
    $total_students = $_POST['total_students'];

    // Insert the data into the database
    $insert_query = "INSERT INTO teacher_reports (teacher_id, class_id, academic_session, month, class_teacher_name, total_students)
                     VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iisssi", $teacher_id, $class_id, $academic_session, $month, $class_teacher_name, $total_students);
    
    if ($stmt->execute()) {
        echo "<script>alert('Report submitted successfully!'); window.location.href = 'report_options.php';</script>";
    } else {
        echo "<script>alert('Error submitting the report. Please try again later.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Teacher Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Class Teacher Report</h1>
        <form method="POST" action="class_teacher_report.php">
            <div class="form-group">
                <label for="class">Select Class:</label>
                <select name="class" id="class" required>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="academic_session">Academic Session:</label>
                <select name="academic_session" id="academic_session" required>
                    <?php foreach ($academic_sessions as $session): ?>
                        <option value="<?php echo $session; ?>"><?php echo $session; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="month">Select Month:</label>
                <select name="month" id="month" required>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
            </div>

            <div class="form-group">
                <label for="class_teacher">Class Teacher Name:</label>
                <input type="text" name="class_teacher" id="class_teacher" required style="width: 90%; padding: 10px;
                margin-bottom: 10px;border: 1px solid #ccc;border-radius: 5px;font-size: 16px;">
            </div>

            <div class="form-group">
                <label for="total_students">Total Number of Students:</label>
                <input type="number" name="total_students" id="total_students" required style="width: 90%; padding: 10px;
                margin-bottom: 10px;border: 1px solid #ccc;border-radius: 5px;font-size: 16px;">
            </div>

            <button type="submit" class="styled-button">Submit</button>
        </form>
        <a href="report_options.php" class="back-link">Skip to Report Page</a>
        <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
