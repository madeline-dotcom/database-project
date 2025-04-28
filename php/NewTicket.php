<?php
$ticketNum = $_POST['ticketNum'];
$employeeID = $_POST['employeeID'];
$deviceType = $_POST['deviceType'];
$serialNum = $_POST['serialNum'];
$clientID = $_POST['clientID'];


//connection
$conn = new mysqli('localhost','root','pswd','DB name');
if ($conn->connect_error){
	die('Connection Failed : '.$conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO Ticket (ticketNum, employeeID, deviceType, serialNum, clientID) VALUES (?, ?, ?, ?, ?)");

if($stmt ==false){
	die('Prepare failed: ' . $conn->error);
}

$stmt->bind_param("iisss", $ticketNum, $employeeID, $deviceType, $serialNum, $clientID);

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Execution failed: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>