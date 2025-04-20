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
    $report_query = "SELECT * FROM teacher_reports WHERE id = ?";
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
        $academic_session = $_POST['academic_session'];
        $month = $_POST['month'];
        $class_teacher_name = $_POST['class_teacher_name'];
        $total_students = $_POST['total_students'];

        $update_query = "UPDATE teacher_reports SET academic_session = ?, month = ?, class_teacher_name = ?, total_students = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssii", $academic_session, $month, $class_teacher_name, $total_students, $report_id);
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
    <title>Edit Teacher Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Teacher Report</h1>
        <form action="edit_teacher_report.php?id=<?php echo $report_data['id']; ?>" method="POST">
            <label for="academic_session">Academic Session:</label>
            <input type="text" id="academic_session" name="academic_session" value="<?php echo htmlspecialchars($report_data['academic_session']); ?>" required><br><br>

            <label for="month">Month:</label>
            <input type="text" id="month" name="month" value="<?php echo htmlspecialchars($report_data['month']); ?>" required><br><br>

            <label for="class_teacher_name">Class Teacher Name:</label>
            <input type="text" id="class_teacher_name" name="class_teacher_name" value="<?php echo htmlspecialchars($report_data['class_teacher_name']); ?>" required><br><br>

            <label for="total_students">Total Students:</label>
            <input type="number" id="total_students" name="total_students" value="<?php echo htmlspecialchars($report_data['total_students']); ?>" required><br><br>

            <input type="submit" value="Update Report">
        </form>
        <a href="admin_view_reports.php">Back to Reports</a>
    </div>
</body>
</html>
