<?php
include 'security.php';  // Ensures only logged-in admins can access
include 'config.php';    // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password before saving it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the teacher into the teachers table along with email and password
    $query = "INSERT INTO teachers (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $name, $email, $hashed_password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $message = "Teacher added successfully.";
    } else {
        $message = "Failed to add teacher.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Teacher</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h1 class="form-title">Add Teacher</h1>
        <?php if (isset($message)) echo "<p class='success-message'>$message</p>"; ?>
        <form method="POST" class="styled-form">
            <div class="form-group">
                <label for="name">Teacher Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Teacher Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="styled-button">Add Teacher</button>
        </form>
        <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
