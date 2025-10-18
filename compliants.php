<?php
$conn = new mysqli("localhost", "root", "", "hostel");

if ($conn->connect_error) {
    die("<script>alert('Database connection failed.');</script>");
}

$studentid = $_POST['studentid'];
$complaint = $_POST['complaint'];
$cdate = $_POST['cdate'];


$checkSql = "SELECT * FROM students WHERE studentid = '$studentid'";
$result = $conn->query($checkSql);

if ($result->num_rows > 0) 
{
    
    $sql = "INSERT INTO complaints (studentid, complaint, cdate) VALUES ('$studentid', '$complaint', '$cdate')";
    if ($conn->query($sql) === TRUE) 
    {
        echo "<script>alert('Complaint added successfully!');window.location.href='index.html';</script>";
    } 
    else 
    {
        echo "<script>alert('Error adding complaint: " . $conn->error . "'); window.history.back();</script>";
    }
} 
else 
{
    echo "<script>alert('Invalid Student ID!'); window.history.back();</script>";
}

$conn->close();
?>
