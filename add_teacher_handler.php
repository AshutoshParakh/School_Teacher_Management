<?php
include 'security.php';
require 'config.php'; // Database configuration

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

   

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert teacher into the database
    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'teacher')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        echo "<p class='success'>Teacher added successfully!</p>";
        echo "<a href='admin_dashboard.php' class='back-link'>Back to Dashboard</a>";
    } else {
        echo "<p class='error'>Error: " . $conn->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
