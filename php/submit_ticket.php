<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketNum = $_POST['ticketNum'];
    $employeeID = $_POST['employeeID'];
    $deviceType = $_POST['deviceType'];
    $serialNum = $_POST['serialNum'];
    $clientID = $_POST['clientID'];
    $status = $_POST['status'];

    $sql = "INSERT INTO Ticket (TicketNum, EmployeeID, DeviceType, SerialNum, ClientID, Status) 
            VALUES ('$ticketNum', '$employeeID', '$deviceType', '$serialNum', '$clientID', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "Ticket submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
