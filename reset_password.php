<?php
include 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $query = "SELECT * FROM teachers WHERE password_reset_token = ? AND token_expiry > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = trim($_POST['password']);
            $confirmPassword = trim($_POST['confirm_password']);

            if (empty($newPassword) || empty($confirmPassword)) {
                $error = "Please fill in all fields.";
            } elseif ($newPassword !== $confirmPassword) {
                $error = "Passwords do not match.";
            } else {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                // Update the password and clear the reset token
                $updateQuery = "UPDATE teachers SET password = ?, password_reset_token = NULL, token_expiry = NULL WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("si", $hashedPassword, $teacher['id']);
                $updateStmt->execute();

                $success = "Password reset successfully. You can now <a href='teacher_login.php'>login</a>.";
            }
        }
    } else {
        $error = "Invalid or expired token.";
    }
} else {
    $error = "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color: green;'>$success</p>"; ?>
    <?php if (!isset($success)) : ?>
    <form method="POST">
        <input type="password" name="password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Reset Password</button>
    </form>
    <?php endif; ?>
</body>
</html>
