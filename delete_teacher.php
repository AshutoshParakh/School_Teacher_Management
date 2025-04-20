<?php
// Include the necessary files for security and database connection
include 'security.php';  // Ensures only logged-in admins can access
include 'config.php';     // Database connection file

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $teacher_id = $_GET['id'];

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // First, delete the records from teacher_classes (remove assignments)
        $delete_assignments = "DELETE FROM teacher_classes WHERE teacher_id = ?";
        $stmt = $conn->prepare($delete_assignments);
        $stmt->bind_param('i', $teacher_id);
        $stmt->execute();

        // Then, delete the teacher from the teachers table
        $delete_teacher = "DELETE FROM teachers WHERE id = ?";
        $stmt = $conn->prepare($delete_teacher);
        $stmt->bind_param('i', $teacher_id);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Redirect to the view teachers page with a success message
        header("Location: view_teachers.php?message=Teacher removed successfully");
        exit;
    } catch (Exception $e) {
        // Rollback the transaction in case of any errors
        $conn->rollback();

        // Redirect to the view teachers page with an error message
        header("Location: view_teachers.php?error=Failed to remove teacher");
        exit;
    }
} else {
    // If no ID is provided, redirect to the view teachers page with an error message
    header("Location: view_teachers.php?error=No teacher ID provided");
    exit;
}

?>
