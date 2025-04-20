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
    $subject = ($_POST['subject'] === 'Other') ? $_POST['other_subject'] : $_POST['subject'];
    $lesson_completed = $_POST['lesson_completed'];
    $activities = $_POST['activities'];

    $insert_query = "INSERT INTO subjective_reports (teacher_id, class_id, subject, lesson_completed, activities)
                     VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iisss", $teacher_id, $class_id, $subject, $lesson_completed, $activities);
    
    if ($stmt->execute()) {
        echo "<script>alert('Subjective report submitted successfully!'); window.location.href = 'teacher_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error submitting the subjective report. Please try again later.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjective Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Subjective Report</h1>
        <form method="POST" action="subjective_report.php">
            <div class="form-group">
                <label for="class">Select Class:</label>
                <select name="class" id="class" required>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="subject">Select Subject:</label>
                <select name="subject" id="subject" onchange="toggleOtherSubjectField()" required>
                    <option value="Hindi">Hindi</option>
                    <option value="English">English</option>
                    <option value="Science">Science</option>
                    <option value="Social Science">Social Science</option>
                    <option value="Maths">Maths</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group" id="other-subject-group" style="display: none;">
                <label for="other_subject">Enter Subject:</label>
                <input type="text" name="other_subject" id="other_subject" style="width: 90%; padding: 10px;
                margin-bottom: 10px;border: 1px solid #ccc;border-radius: 5px;font-size: 16px;">
            </div>

            <div class="form-group">
                <label for="lesson_completed">Lesson Completed:</label>
                <textarea name="lesson_completed" id="lesson_completed" rows="3" required style="width: 90%; padding: 10px;
                margin-bottom: 10px;border: 1px solid #ccc;border-radius: 5px;font-size: 16px;"></textarea>
            </div>

            <div class="form-group">
                <label for="activities">Activities Conducted:</label>
                <textarea name="activities" id="activities" rows="3" required style="width: 90%; padding: 10px;
                margin-bottom: 10px;border: 1px solid #ccc;border-radius: 5px;font-size: 16px;"></textarea>
            </div>

            <button type="submit" class="styled-button">Submit</button>
        </form>
        <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>

    <script>
        function toggleOtherSubjectField() {
            const subjectDropdown = document.getElementById('subject');
            const otherSubjectGroup = document.getElementById('other-subject-group');
            if (subjectDropdown.value === 'Other') {
                otherSubjectGroup.style.display = 'block';
            } else {
                otherSubjectGroup.style.display = 'none';
            }
        }
    </script>
</body>
</html>
