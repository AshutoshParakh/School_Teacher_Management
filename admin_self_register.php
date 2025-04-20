<?php
// Database connection
$host = 'localhost';
$db = 'school_management';
$user = 'root';
$password = '';
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if an admin already exists
    $checkAdmin = "SELECT * FROM users WHERE role = 'admin'";
    $result = $conn->query($checkAdmin);

    if ($result->num_rows > 0) {
        echo "<p class='error'>An admin account already exists. Self-registration is disabled.</p>";
    } else {
        // Insert admin into database
        $sql = "INSERT INTO users (name, email, role, password) VALUES ('$name', '$email', 'admin', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "<p class='success'>Admin registered successfully!</p>";
            echo "<a href='admin_login.html' class='login-link'>Go to Login</a>";
        } else {
            echo "<p class='error'>Error: " . $conn->error . "</p>";
        }
    }
}
$conn->close();
?>
