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
    $report_query = "SELECT * FROM assembly_performance_reports WHERE id = ?";
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
        $date = $_POST['date'];
        $activity = $_POST['activity'];
        $students_participated = $_POST['students_participated'];

        $update_query = "UPDATE assembly_performance_reports SET date = ?, activity = ?, students_participated = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssi", $date, $activity, $students_participated, $report_id);
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
    <title>Edit Assembly Performance Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Assembly Performance Report</h1>
        <form action="edit_assembly_performance.php?id=<?php echo $report_data['id']; ?>" method="POST">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($report_data['date']); ?>" required><br><br>

            <label for="activity">Activity:</label>
            <textarea id="activity" name="activity" required><?php echo htmlspecialchars($report_data['activity']); ?></textarea><br><br>

            <label for="students_participated">Students Participated:</label>
            <textarea id="students_participated" name="students_participated" required><?php echo htmlspecialchars($report_data['students_participated']); ?></textarea><br><br>

            <input type="submit" value="Update Report">
        </form>
        <a href="admin_view_reports.php">Back to Reports</a>
    </div>
</body>
</html>
