<?php
session_start();
include 'config.php';  

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin_login.html');
    exit();
}

if (isset($_GET['id'])) {
    $report_id = $_GET['id'];

    // Fetch the report details to edit
    $report_query = "SELECT * FROM attendance_reports WHERE id = ?";
    $stmt = $conn->prepare($report_query);
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $report_result = $stmt->get_result();
    $report_data = $report_result->fetch_assoc();

    if (!$report_data) {
        die('Report not found.');
    }

    // Handle form submission to update report
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $total_classroom_days = $_POST['total_classroom_days'];
        $average_attendance = $_POST['average_attendance'];
        $students_with_3_or_more_absences = $_POST['students_with_3_or_more_absences'];

        $update_query = "UPDATE attendance_reports SET total_classroom_days = ?, average_attendance = ?, students_with_3_or_more_absences = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("iisi", $total_classroom_days, $average_attendance, $students_with_3_or_more_absences, $report_id);
        $stmt->execute();

        header('Location: admin_view_reports.php'); // Redirect after update
    }
} else {
    die('Invalid report ID.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Attendance Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Attendance Report</h1>
        <form action="edit_attendance_report.php?id=<?php echo $report_data['id']; ?>" method="POST">
            <label for="total_classroom_days">Total Classroom Days:</label>
            <input type="number" id="total_classroom_days" name="total_classroom_days" value="<?php echo htmlspecialchars($report_data['total_classroom_days']); ?>" required><br><br>

            <label for="average_attendance">Average Attendance:</label>
            <input type="number" step="0.01" id="average_attendance" name="average_attendance" value="<?php echo htmlspecialchars($report_data['average_attendance']); ?>" required><br><br>

            <label for="students_with_3_or_more_absences">Students with 3 or More Absences:</label>
            <textarea id="students_with_3_or_more_absences" name="students_with_3_or_more_absences" required><?php echo htmlspecialchars($report_data['students_with_3_or_more_absences']); ?></textarea><br><br>

            <input type="submit" value="Update Report">
        </form>
        <a href="admin_view_reports.php">Back to Reports</a>
    </div>
</body>
</html>
