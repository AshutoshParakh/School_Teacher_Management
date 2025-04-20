<?php
session_start();
session_unset(); // Clear session variables
session_destroy(); // Destroy the session

// Redirect to the admin login page
header("Location: index.html");
exit;
?>
