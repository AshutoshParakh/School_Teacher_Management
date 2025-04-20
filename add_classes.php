<?php
// Include security check
include 'security.php'; // Ensures only logged-in admins can access

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

// Handle form submission to insert a new class
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = $conn->real_escape_string($_POST['class_name']);

    // Check if class already exists in the database
    $check_sql = "SELECT * FROM classes WHERE name = '$class_name'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        $error_message = "Class '$class_name' already exists.";
    } else {
        // Insert class into database
        $sql = "INSERT INTO classes (name) VALUES ('$class_name')";
        if ($conn->query($sql) === TRUE) {
            $success_message = "Class added successfully!";
        } else {
            $error_message = "Error adding class: " . $conn->error;
        }
    }
}

// Handle class deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM classes WHERE id = $delete_id";
    if ($conn->query($delete_sql) === TRUE) {
        $success_message = "Class deleted successfully!";
    } else {
        $error_message = "Error deleting class: " . $conn->error;
    }
}

// Fetch all classes
$classes = $conn->query("SELECT * FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Classes</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            margin-bottom: 30px;
        }

        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            color: green;
        }

        .error {
            text-align: center;
            margin-bottom: 20px;
            color: red;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #f8f9fa;
        }

        .delete-btn {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-btn:hover {
            color: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Classes</h1>
        <?php if (!empty($success_message)) : ?>
            <div class="message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)) : ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Form to add a class -->
        <form method="POST">
            <label for="class_name">Class Name:</label>
            <input type="text" id="class_name" name="class_name" placeholder="Enter class name (e.g., 1A, 2B)" required>
            <button type="submit">Add Class</button>
        </form>

        <!-- List of existing classes -->
        <h2>Existing Classes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Class Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($classes && $classes->num_rows > 0) : ?>
                    <?php while ($class = $classes->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $class['id']; ?></td>
                            <td><?php echo $class['name']; ?></td>
                            <td><a href="add_classes.php?delete_id=<?php echo $class['id']; ?>" class="delete-btn">Delete</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3">No classes found.</td>
                    </tr>
                <?php endif; ?>
                
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>    
    </div>
</body>
</html>
