<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "hostel");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM students");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List - Hostel Management System</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ccc;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        footer {
            text-align: center;
            padding: 20px;
            margin-top: 40px;
            background: #f9f9f9;
            font-size: 0.9rem;
            color: #666;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #4CAF50;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <header>
        <h1>🏨 Hostel Booking Management System</h1>
    </header>

    <div class="container">
        <?php
        if ($result && $result->num_rows > 0) {
            echo "<h2>Student List</h2>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Room</th><th>Room Type</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['studentid']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['room_no']}</td>
                        <td>{$row['room_type']}</td> 
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='text-align:center;'>No students found.</p>";
        }
        ?>

        <a href="index.html" class="back">← Back to Dashboard</a>
    </div>

    <footer>
        &copy; 2025 Hostel Management System | Developed by Your Team
    </footer>

</body>
</html>

<?php
$conn->close();
?>