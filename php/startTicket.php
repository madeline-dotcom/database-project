<?php
include 'db_config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketNum = $_POST['ticketNum'];
    $employeeID = $_POST['employeeID'];
    $deviceType = $_POST['deviceType'];
    $serialNum = $_POST['serialNum'];
    $clientID = $_POST['clientID'];
    $status = $_POST['status'];

    // Basic validation
    if (is_numeric($ticketNum) && is_numeric($employeeID) && !empty($deviceType) && !empty($serialNum) && is_numeric($clientID) && !empty($status)) {
        $stmt = $conn->prepare("INSERT INTO Tickets (TicketNum, EmployeeID, DeviceType, SerialNum, ClientID, Status) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissis", $ticketNum, $employeeID, $deviceType, $serialNum, $clientID, $status);

        if ($stmt->execute()) {
            echo "Ticket started successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Please fill in all fields correctly.";
    }
}

$conn->close();
?>
