<?php
include 'security.php';  // Ensures only logged-in admins can access
include 'config.php';    // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = $_POST['teacher_id'];
    $class_id = $_POST['class_id'];

    // Insert the class assignment into the teacher_classes table
    $query = "INSERT INTO teacher_classes (teacher_id, class_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $teacher_id, $class_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $message = "Class assigned successfully.";
    } else {
        $message = "Failed to assign class.";
    }

    $stmt->close();
}

$teachers = $conn->query("SELECT id, name FROM teachers");
$classes = $conn->query("SELECT id, name FROM classes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Classes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h1 class="form-title">Assign Classes</h1>
        <?php if (isset($message)) echo "<p class='success-message'>$message</p>"; ?>
        
        <form method="POST" class="styled-form">
            <div class="form-group">
                <label for="teacher_id">Select Teacher:</label>
                <select id="teacher_id" name="teacher_id" required>
                    <?php while ($teacher = $teachers->fetch_assoc()) : ?>
                        <option value="<?php echo $teacher['id']; ?>"><?php echo $teacher['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="class_id">Select Class:</label>
                <select id="class_id" name="class_id" required>
                    <?php while ($class = $classes->fetch_assoc()) : ?>
                        <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="styled-button">Assign Class</button>
        </form>

        <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
