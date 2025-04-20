<?php
// Database connection
$DB_HOST = 'localhost';
$DB_NAME = 'school_management';
$DB_USER = 'root';
$DB_PASS = ''; // Update according to your database configuration

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>