<?php
$conn = new mysqli("localhost", "root", "", "hostel");

if ($conn->connect_error) {
    die("<script>alert('Database connection failed.');</script>");
}


$studentid = $_POST['studentid'];
$amount = $_POST['amount'];
$due_date = $_POST['due_date'];
$status = $_POST['status'];


$checkSql = "SELECT * FROM students WHERE studentid = '$studentid'";
$result = $conn->query($checkSql);

if ($result->num_rows > 0) {
   
    $sql = "INSERT INTO fees (studentid, amount, due_date, status) VALUES ('$studentid', '$amount', '$due_date', '$status')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Fee record added successfully!'); window.location.href='index.html';</script>";
    } 
    else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
else {
    echo "<script>alert('Invalid Student ID!'); window.history.back();</script>";
}

$conn->close();
?>
