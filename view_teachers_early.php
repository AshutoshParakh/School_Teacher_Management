<?php
include 'security.php'; // Admin authentication
require 'config.php';   // Database configuration

// Handle teacher removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_teacher_id'])) {
    $teacherId = intval($_POST['remove_teacher_id']);
    

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete teacher and their assigned classes
    $deleteClasses = "DELETE FROM teacher_classes WHERE teacher_id = ?";
    $deleteTeacher = "DELETE FROM users WHERE id = ? AND role = 'teacher'";
    $stmt1 = $conn->prepare($deleteClasses);
    $stmt2 = $conn->prepare($deleteTeacher);

    $stmt1->bind_param("i", $teacherId);
    $stmt2->bind_param("i", $teacherId);

    $stmt1->execute();
    $stmt2->execute();

    $stmt1->close();
    $stmt2->close();
    $conn->close();

    header("Location: view_teachers.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Teacher Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Additional inline styles for customization */
        .view-teachers-container {
            max-width: 900px;
            margin: 40px auto;
            text-align: center;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .teacher-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }

        .teacher-table th, .teacher-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .teacher-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        .teacher-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .teacher-table tr:hover {
            background-color: #ddd;
        }

        .remove-button {
            padding: 5px 10px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .remove-button:hover {
            background-color: #ff1a1a;
        }

        .styled-button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .styled-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="view-teachers-container">
        <h1>Teacher Details</h1>
        <table class="teacher-table">
            <thead>
                <tr>
                    <th>Teacher Name</th>
                    <th>Email</th>
                    <th>Assigned Classes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT u.id, u.name, u.email, tc.classes 
                        FROM users u
                        LEFT JOIN teacher_classes tc ON u.id = tc.teacher_id
                        WHERE u.role = 'teacher'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>" . ($row['classes'] ? $row['classes'] : "Not Assigned") . "</td>
                                <td>
                                    <form method='POST' style='display: inline;'>
                                        <input type='hidden' name='remove_teacher_id' value='{$row['id']}'>
                                        <button type='submit' class='remove-button'>Remove</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No teachers found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="styled-button">Back to Dashboard</a>
    </div>
</body>
</html>
