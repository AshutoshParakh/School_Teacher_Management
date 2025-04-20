<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    if (empty($email)) {
        $error = "Please enter your email address.";
    } else {
        $query = "SELECT * FROM teachers WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $teacher = $result->fetch_assoc();
            
            // Generate a unique reset token
            $token = bin2hex(random_bytes(32));
            $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Save token and expiry to the database
            $updateQuery = "UPDATE teachers SET password_reset_token = ?, token_expiry = ? WHERE email = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("sss", $token, $expiry, $email);
            $updateStmt->execute();

            // Send reset email
            $resetLink = "http://yourdomain.com/reset_password.php?token=$token";
            $subject = "Password Reset Request";
            $message = "Click on the link below to reset your password:\n\n$resetLink\n\nThis link is valid for 1 hour.";
            $headers = "From: no-reply@yourdomain.com";

            if (mail($email, $subject, $message, $headers)) {
                $success = "A password reset link has been sent to your email.";
            } else {
                $error = "Failed to send email. Please try again.";
            }
        } else {
            $error = "No account found with that email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
</head>
<body>
    <h1>Forgot Password</h1>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color: green;'>$success</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>
