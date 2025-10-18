<?php
$conn = new mysqli("localhost", "root", "", "hostel");

if ($conn->connect_error) {
    die("<script>alert('Database connection failed.');</script>");
}

if (isset($_POST['update_student'])) {
    $studentid = $_POST['studentid'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $new_room_no = $_POST['room_no'];
    $new_room_type = $_POST['room_type'];


    $current_stmt = $conn->prepare("SELECT room_no FROM students WHERE studentid = ?");
    $current_stmt->bind_param("i", $studentid);
    $current_stmt->execute();
    $current_result = $current_stmt->get_result();
    $current_data = $current_result->fetch_assoc();
    $current_room_no = $current_data['room_no'];
    $current_stmt->close();

  
    $check_stmt = $conn->prepare("SELECT * FROM rooms WHERE room_no = ? AND type = ?");
    $check_stmt->bind_param("is", $new_room_no, $new_room_type);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result && $check_result->num_rows > 0) {
        
        $stmt = $conn->prepare("UPDATE students SET name=?, gender=?, age=?, contact=?, email=?, room_no=?, room_type=? WHERE studentid=?");
        $stmt->bind_param("ssissssi", $name, $gender, $age, $contact, $email, $new_room_no, $new_room_type, $studentid);

        if ($stmt->execute()) {
            $stmt->close();

            
            if ($new_room_no != $current_room_no) {
                $conn->query("UPDATE rooms SET occupied = occupied - 1 WHERE room_no = $current_room_no AND occupied > 0");
                $conn->query("UPDATE rooms SET occupied = occupied + 1 WHERE room_no = $new_room_no");
            }

            echo "<script>alert('Student updated successfully!'); window.location.href='update_students.php';</script>";
        } 
        else {
            echo "<script>alert('Error updating student: " . $stmt->error . "');</script>";
            $stmt->close();
        }
    } 
    else {
        echo "<script>alert('Invalid Room Number or Room Type combination.'); window.history.back();</script>";
    }

    $check_stmt->close();
}


if (isset($_POST['delete_student'])) {
    $studentid = intval($_POST['delete_id']);

    $stmt = $conn->prepare("SELECT room_no FROM students WHERE studentid = ?");
    $stmt->bind_param("i", $studentid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $room_no = $row['room_no'];
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM fees WHERE studentid = ?");
        $stmt->bind_param("i", $studentid);
        if ($stmt->execute()) {
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM students WHERE studentid = ?");
            $stmt->bind_param("i", $studentid);
            if ($stmt->execute()) {
                $stmt->close();

                $stmt = $conn->prepare("UPDATE rooms SET occupied = occupied - 1 WHERE room_no = ? AND occupied > 0");
                $stmt->bind_param("i", $room_no);
                $stmt->execute();
                $stmt->close();

                echo "<script>alert('Student and related fees deleted successfully! Room occupancy updated.'); window.location.href='update_students.php';</script>";
            } else {
                echo "<script>alert('Error deleting student: " . $stmt->error . "');</script>";
                $stmt->close();
            }
        } else {
            echo "<script>alert('Error deleting related fees: " . $stmt->error . "');</script>";
            $stmt->close();
        }
    } else {
        echo "<script>alert('Student not found.'); window.location.href='update_students.php';</script>";
    }
}


if (isset($_POST['search_student'])) {
    $studentid = intval($_POST['search_id']);

    $stmt = $conn->prepare("SELECT * FROM students WHERE studentid = ?");
    $stmt->bind_param("i", $studentid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>

        <div class="container">
            <h2>Update Student Details</h2>
            <form method="POST" action="">
                <input type="hidden" name="studentid" value="<?= $row['studentid'] ?>">

                <label for="name">Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>

                <label for="gender">Gender:</label>
                <input type="text" name="gender" value="<?= htmlspecialchars($row['gender']) ?>" required>

                <label for="age">Age:</label>
                <input type="number" name="age" value="<?= htmlspecialchars($row['age']) ?>" required>

                <label for="contact">Contact:</label>
                <input type="text" name="contact" value="<?= htmlspecialchars($row['contact']) ?>" required>

                <label for="email">Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>

                <label for="room_no">Room No:</label>
                <input type="number" name="room_no" value="<?= htmlspecialchars($row['room_no']) ?>" required>

                <label for="room_type">Room Type:</label>
                <select name="room_type" required>
                    <option value="AC" <?= $row['room_type'] == 'AC' ? 'selected' : '' ?>>AC</option>
                    <option value="NAC" <?= $row['room_type'] == 'NAC' ? 'selected' : '' ?>>NAC</option>
                </select>

                <input type="submit" name="update_student" value="Update Student">
            </form>

            <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this student?');">
                <input type="hidden" name="delete_id" value="<?= $row['studentid'] ?>">
                <input type="submit" name="delete_student" value="Delete Student">
            </form>

            <div class="links">
                <a href="update_students.php" class="back">Search Another Student</a>
                <a href="index.html" class="back">← back to Dashboard</a>
            </div>
        </div>

        <?php
    } else {
        echo "<script>alert('Invalid Student ID or student not found.'); window.location.href='update_students.php';</script>";
        exit();
    }

    $stmt->close();
}
?>

<?php if (!isset($_POST['search_student']) && !isset($_POST['update_student']) && !isset($_POST['delete_student'])): ?>
    <div class="container">
        <h2>Search Student</h2>
        <form method="POST" action="">
            <label for="search_id">Enter Student ID:</label>
            <input type="number" name="search_id" required>
            <input type="submit" name="search_student" value="Search">
        </form>
        <a href="index.html" class="back">← back to Dashboard</a>
    </div>
<?php endif; ?>

<?php $conn->close(); ?>

<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f9;
        color: #333;
    }

    .container {
        max-width: 700px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
        color: #4CAF50;
        margin-bottom: 20px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    input, select {
        padding: 10px;
        font-size: 1rem;
        border-radius: 6px;
        border: 1px solid #ccc;
        width: 100%;
        background-color: white;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s ease;
        padding: 12px;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    .back {
        display: inline-block;
        margin-top: 20px;
        text-align: center;
        color: #4CAF50;
        font-weight: bold;
        text-decoration: none;
    }

    .back:hover {
        text-decoration: underline;
    }

    .links {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .links .back {
        text-decoration: none;
        color: #4CAF50;
        font-weight: bold;
    }

    .links .back:hover {
        text-decoration: underline;
    }
</style>
