<?php
session_start();
include 'config.php';  

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin_login.html'); 
    exit();
}

// Fetch Data from teacher_reports Table
$teacher_report_query = "SELECT tr.id, tr.teacher_id, tr.class_id, tr.academic_session, tr.month, tr.class_teacher_name, tr.total_students, tr.submission_time,
                         t.name AS teacher_name, c.name AS class_name 
                         FROM teacher_reports tr
                         INNER JOIN teachers t ON tr.teacher_id = t.id
                         INNER JOIN classes c ON tr.class_id = c.id
                         ORDER BY tr.submission_time DESC";
$teacher_report_result = $conn->query($teacher_report_query);

// Fetch Data from assembly_performance_reports Table
$assembly_performance_query = "SELECT ap.id, ap.teacher_id, ap.class_id, ap.date, ap.activity, ap.students_participated, ap.created_at,
                               t.name AS teacher_name, c.name AS class_name 
                               FROM assembly_performance_reports ap
                               INNER JOIN teachers t ON ap.teacher_id = t.id
                               INNER JOIN classes c ON ap.class_id = c.id
                               ORDER BY ap.created_at DESC";
$assembly_performance_result = $conn->query($assembly_performance_query);

// Fetch Data from attendance_reports Table
$attendance_report_query = "SELECT ar.id, ar.teacher_id, ar.class_id, ar.total_classroom_days, ar.average_attendance, ar.students_with_3_or_more_absences, ar.created_at,
                            t.name AS teacher_name, c.name AS class_name
                            FROM attendance_reports ar
                            INNER JOIN teachers t ON ar.teacher_id = t.id
                            INNER JOIN classes c ON ar.class_id = c.id
                            ORDER BY ar.created_at DESC";
$attendance_report_result = $conn->query($attendance_report_query);

// Delete operation
if (isset($_GET['delete_teacher_report'])) {
    $delete_id = $_GET['delete_teacher_report'];
    $delete_query = "DELETE FROM teacher_reports WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: admin_view_reports.php"); // Redirect to refresh page after deletion
}

if (isset($_GET['delete_assembly_report'])) {
    $delete_id = $_GET['delete_assembly_report'];
    $delete_query = "DELETE FROM assembly_performance_reports WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: admin_view_reports.php");
}

if (isset($_GET['delete_attendance_report'])) {
    $delete_id = $_GET['delete_attendance_report'];
    $delete_query = "DELETE FROM attendance_reports WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: admin_view_reports.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View - Reports</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .table-container {
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            color: #333;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons a {
            text-decoration: none;
            padding: 5px 10px;
            color: white;
            background-color: #007BFF;
            border-radius: 5px;
        }
        .action-buttons .delete {
            background-color: #DC3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Reports</h1>
        
        <!-- Teacher Report Table -->
        <div class="table-container">
            <h2>Teacher Reports</h2>
            <?php if ($teacher_report_result && $teacher_report_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Teacher Name</th>
                            <th>Class</th>
                            <th>Academic Session</th>
                            <th>Month</th>
                            <th>Class Teacher Name</th>
                            <th>Total Students</th>
                            <th>Submission Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $teacher_report_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['academic_session']); ?></td>
                                <td><?php echo htmlspecialchars($row['month']); ?></td>
                                <td><?php echo htmlspecialchars($row['class_teacher_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['total_students']); ?></td>
                                <td><?php echo htmlspecialchars($row['submission_time']); ?></td>
                                <td class="action-buttons">
                                    <a href="edit_teacher_report.php?id=<?php echo $row['id']; ?>">Edit</a>
                                    <a href="?delete_teacher_report=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this report?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No teacher reports found.</p>
            <?php endif; ?>
        </div>

        <!-- Assembly Performance Table -->
        <div class="table-container">
            <h2>Assembly Performance Reports</h2>
            <?php if ($assembly_performance_result && $assembly_performance_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Teacher Name</th>
                            <th>Class</th>
                            <th>Date</th>
                            <th>Activity</th>
                            <th>Students Participated</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $assembly_performance_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td><?php echo htmlspecialchars($row['activity']); ?></td>
                                <td><?php echo htmlspecialchars($row['students_participated']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td class="action-buttons">
                                    <a href="edit_assembly_performance.php?id=<?php echo $row['id']; ?>">Edit</a>
                                    <a href="?delete_assembly_report=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this report?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No assembly performance reports found.</p>
            <?php endif; ?>
        </div>

        <!-- Attendance Report Table -->
        <div class="table-container">
            <h2>Attendance Reports</h2>
            <?php if ($attendance_report_result && $attendance_report_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Teacher Name</th>
                            <th>Class</th>
                            <th>Total Classroom Days</th>
                            <th>Average Attendance</th>
                            <th>Students with 3+ Absences</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $attendance_report_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['total_classroom_days']); ?></td>
                                <td><?php echo htmlspecialchars($row['average_attendance']); ?></td>
                                <td><?php echo htmlspecialchars($row['students_with_3_or_more_absences']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td class="action-buttons">
                                    <a href="edit_attendance_report.php?id=<?php echo $row['id']; ?>">Edit</a>
                                    <a href="?delete_attendance_report=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this report?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No attendance reports found.</p>
            <?php endif; ?>
        </div>

        <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
