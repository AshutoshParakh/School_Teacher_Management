<?php
session_start();

// Database connection
$host = 'localhost';
$db = 'school_management';
$user = 'root';
$password = ''; // Update according to your database configuration

$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Check if user exists and is an admin
    $sql = "SELECT * FROM users WHERE email = '$email' AND role = 'admin'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_name'] = $row['name'];
            header("Location: admin_dashboard.php");
            exit;
        } else {
            echo "<p class='error'>Invalid password.</p>";
        }
    } else {
        echo "<p class='error'>No admin account found with this email.</p>";
    }
}
$conn->close();
?>
