<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'Company');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Remove the following block if the `name` table is not needed
/*
$name = $_POST['name'];
$stmt = $conn->prepare("INSERT INTO name(name) VALUES(?)");
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->close();
*/

// $conn->close();
?>