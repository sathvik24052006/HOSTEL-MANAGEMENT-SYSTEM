<?php

$conn = new mysqli("localhost", "root", "", "hostel");


if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$typeFilter = '';
$rooms = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $typeFilter = $_POST['room_type'];
  $stmt = $conn->prepare("SELECT room_no, capacity, occupied, type FROM rooms WHERE type = ?");
  $stmt->bind_param("s", $typeFilter);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
  }

  $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Room Management - Hostel Management System</title>
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

    form {
      text-align: center;
      margin-bottom: 20px;
    }

    select {
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    button {
      padding: 10px 20px;
      margin-left: 10px;
      border: none;
      border-radius: 6px;
      background-color: #4CAF50;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color: #45a049;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #4CAF50;
      color: white;
    }

    tr:hover {
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
      margin-top: 20px;
      text-align: center;
      color: #4CAF50;
      font-weight: bold;
      text-decoration: none;
    }

    .back:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <header>
    <h1>🏨 Hostel Booking Management System</h1>
  </header>

  <div class="container">
    <h2>Room Management</h2>

    <form method="POST" action="">
      <label for="room_type">Select Room Type:</label>
      <select name="room_type" id="room_type" required>
        <option value="" disabled selected>-- Choose Room Type --</option>
        <option value="AC" <?php if ($typeFilter == 'AC') echo 'selected'; ?>>AC</option>
        <option value="NAC" <?php if ($typeFilter == 'NAC') echo 'selected'; ?>>Non AC</option>
      </select>
      <button type="submit">Show Rooms</button>
    </form>

    <?php if (!empty($rooms)) { ?>
      <table>
        <tr>
          <th>Room No</th>
          <th>Capacity</th>
          <th>Occupied</th>
        </tr>
        <?php foreach ($rooms as $room): ?>
          <tr>
            <td><?= htmlspecialchars($room['room_no']) ?></td>
            <td><?= htmlspecialchars($room['capacity']) ?></td>
            <td><?= htmlspecialchars($room['occupied']) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php } elseif ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
      <p style="text-align:center; color:red;">No rooms found for the selected type.</p>
    <?php } ?>

   
    <a href="index.html" class="back">← Back to Dashboard</a>
  </div>


</body>
</html>
