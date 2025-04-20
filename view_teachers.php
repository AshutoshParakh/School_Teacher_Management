<?php
include 'security.php';
include 'config.php';

$query = "SELECT teachers.id, teachers.name, GROUP_CONCAT(classes.name) AS assigned_classes
          FROM teachers
          LEFT JOIN teacher_classes ON teachers.id = teacher_classes.teacher_id
          LEFT JOIN classes ON teacher_classes.class_id = classes.id
          GROUP BY teachers.id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Teachers</title>
    <style>
        /* Global Styling */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif; /* Simpler font */
            background: linear-gradient(to bottom right, #87CEFA, #B0E0E6);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #444;
        }

        .container {
            max-width: 1000px;
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 2em; /* Reduced font size */
            color: #333;
            margin-bottom: 20px;
        }

        /* Table Styling */
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-top: 30px;
        }

        .teachers-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px; /* Smaller font size */
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .teachers-table th, .teachers-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .teachers-table th {
            background-color: #007bff;
            color: white;
        }

        .teachers-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .teachers-table tr:hover {
            background-color: #f1f1f1;
        }

        .teachers-table td {
            font-size: 14px;
            color: #555;
        }

        /* Remove Button Styling */
        .delete-button {
            background-color: #ff4d4d;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .delete-button:hover {
            background-color: #e60000;
        }

        /* Back Link Styling */
        .back-link {
            display: inline-block;
            margin-top: 20px;
            font-size: 1em;
            color: #007bff;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .back-link:hover {
            background-color: #f1f1f1;
            color: #0056b3;
        }

        /* Responsive Design for Mobile */
        @media screen and (max-width: 768px) {
            h1 {
                font-size: 1.5em;
            }

            .teachers-table th, .teachers-table td {
                font-size: 12px; /* Smaller font for mobile */
                padding: 10px;
            }

            .back-link {
                font-size: 0.9em;
                padding: 6px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View Teachers</h1>
        <div class="table-container">
            <table class="teachers-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Assigned Classes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['assigned_classes'] ?: 'None'; ?></td>
                        <td>
                            <a href="delete_teacher.php?id=<?php echo $row['id']; ?>" class="delete-button">Remove</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
