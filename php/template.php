<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'Company');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}
?>