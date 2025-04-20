<?php
session_start();
include 'config.php';  

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin_login.html'); 
    exit();
}

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM subjective_reports WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Report deleted successfully!'); window.location.href = 'admin_subjective_reports.php';</script>";
    } else {
        echo "<script>alert('Error deleting the report.');</script>";
    }
}

// Handle Edit Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $subject = $_POST['subject'];
    $lesson_completed = $_POST['lesson_completed'];
    $activities = $_POST['activities'];

    $update_query = "UPDATE subjective_reports 
                     SET subject = ?, lesson_completed = ?, activities = ?
                     WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssi", $subject, $lesson_completed, $activities, $edit_id);
    if ($stmt->execute()) {
        echo "<script>alert('Report updated successfully!'); window.location.href = 'admin_subjective_reports.php';</script>";
    } else {
        echo "<script>alert('Error updating the report.');</script>";
    }
}

// Fetch All Reports
$query = "SELECT sr.id, t.name AS teacher_name, c.name AS class_name, sr.subject, sr.lesson_completed, sr.activities, sr.created_at 
          FROM subjective_reports sr
          INNER JOIN teachers t ON sr.teacher_id = t.id
          INNER JOIN classes c ON sr.class_id = c.id
          ORDER BY sr.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View - Subjective Reports</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        h1 {
            display: block;
            font-size: 24px;
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            max-width: 500px;
            width: 100%;
        }
        .close-modal {
            float: right;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>All Subjective Reports</h1>

        <?php if ($result && $result->num_rows > 0): ?>
            <table border="1" cellspacing="0" cellpadding="10">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Teacher Name</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Lesson Completed</th>
                        <th>Activities</th>
                        <th>Submission Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['lesson_completed'])); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['activities'])); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>| 
                                <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this report?')" style="text-decoration: none;">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No reports found.</p>
        <?php endif; ?>

        <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
            <h2>Edit Report</h2>
            <form method="POST" action="admin_subjective_reports.php">
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" name="subject" id="edit_subject" required>
                </div>
                <div class="form-group">
                    <label for="lesson_completed">Lesson Completed:</label>
                    <textarea name="lesson_completed" id="edit_lesson_completed" required></textarea>
                </div>
                <div class="form-group">
                    <label for="activities">Activities:</label>
                    <textarea name="activities" id="edit_activities" required></textarea>
                </div>
                <button type="submit" class="styled-button">Update</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('editModal');

        function openEditModal(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_subject').value = data.subject;
            document.getElementById('edit_lesson_completed').value = data.lesson_completed;
            document.getElementById('edit_activities').value = data.activities;

            modal.style.display = 'flex';
        }

        function closeEditModal() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>
