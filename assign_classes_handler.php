<?php
include 'security.php';
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_id = $_POST['teacher_id'];
    $classes = $_POST['classes'];
   

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO teacher_classes (teacher_id, classes) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $teacher_id, $classes);

    if ($stmt->execute()) {
        echo "<p class='success'>Classes assigned successfully!</p>";
        echo "<a href='admin_dashboard.php' class='back-link'>Back to Dashboard</a>";
    } else {
        echo "<p class='error'>Error: " . $conn->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
