<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "hostel"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$contact = $_POST['contact'];
$relation = $_POST['relation'];
$date = $_POST['date'];
$studentId = $_POST['studentId'];


if (!preg_match("/^\d{10}$/", $contact)) 
{
    echo "<script>alert('Please enter a valid 10-digit contact number.'); window.history.back();</script>";
    exit();
}

$sql_check_student = "SELECT * FROM students WHERE studentId = '$studentId'";

$result = $conn->query($sql_check_student);

if ($result->num_rows > 0) 
{
   
    $sql_insert_visitor = "INSERT INTO visitor (name, contact, relation, date, studentid) 
                           VALUES ('$name', '$contact', '$relation', '$date', '$studentId')";

    if ($conn->query($sql_insert_visitor) === TRUE) 
    {
        echo "<script>alert('Visitor registered successfully!');window.location.href='index.html';</script>";
    } 
    else {
        echo "Error: " . $sql_insert_visitor . "<br>" . $conn->error;
    }
} 
else {
    echo "<script>alert('Invalid Student ID');window.history.back();</script>";
}

$conn->close();
?>
