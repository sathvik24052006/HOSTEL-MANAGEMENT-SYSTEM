<?php

$conn = new mysqli("localhost", "root", "", "hostel");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$studentid = $_POST['studentid'];
$name = $_POST['name'];
$gender = $_POST['gender'];
$age = $_POST['age'];
$contact = $_POST['contact'];
$email = $_POST['email'];
$room_no = $_POST['room_no'];
$room_type = $_POST['room_type'];


if (!preg_match("/^\d{10}$/", $contact)) 
{
    echo "<script>alert('Please enter a valid 10-digit contact number.'); window.history.back();</script>";
    exit();
}


if ($room_type == 'AC' || $room_type == 'NAC') 
{

  
    $room_sql = "SELECT capacity, occupied FROM rooms WHERE room_no = ? AND type = ?";
    $stmt = $conn->prepare($room_sql);
    $stmt->bind_param("ss", $room_no, $room_type);
    $stmt->execute();
    $room_result = $stmt->get_result();

    if ($room_result->num_rows > 0) {
        $room_data = $room_result->fetch_assoc();
        $capacity = $room_data['capacity'];
        $occupied = $room_data['occupied'];

        if ($capacity > $occupied) {
            
            echo "<script>alert('Room is available. Proceeding with registration...');</script>";
        } 
        else {
           
            echo "<script>alert('The selected room is already full. Please choose another.'); window.history.back();</script>";
            exit();
        }
    } 
    else {
        
        echo "<script>alert('Invalid room number or type.'); window.history.back();</script>";
        exit();
    }

}
 else {
    echo "<script>alert('Invalid room type selected. Please choose AC or NAC.'); window.history.back();</script>";
    exit();
}


$check_sql = "SELECT * FROM students WHERE studentid = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("s", $studentid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Student already exists!'); window.history.back();</script>";
} 
else {
  
    $insert_sql = "INSERT INTO students (studentid, name, gender, age, contact, email, room_no, room_type) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssssssss", $studentid, $name, $gender, $age, $contact, $email, $room_no, $room_type);
    
    if ($stmt->execute()) {
        
        $update_sql = "UPDATE rooms SET occupied = occupied + 1 WHERE room_no = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("s", $room_no);
        $stmt->execute();

        echo "<script>alert('Student registered successfully!'); window.location.href='fees.html';</script>";
    } 
    else {
        echo "<script>alert('Error during registration: " . $stmt->error . "'); window.history.back();</script>";
    }
}

$conn->close();
?>
