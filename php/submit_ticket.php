<?php
include 'template.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and trim input values
    $ticketNum = isset($_POST['ticketNum']) ? trim($_POST['ticketNum']) : null;
    $deviceType = isset($_POST['deviceType']) ? trim($_POST['deviceType']) : null;
    $serialNum = isset($_POST['serialNum']) ? trim($_POST['serialNum']) : null;
    $clientID = isset($_POST['clientID']) ? trim($_POST['clientID']) : null;

    // Set the status to "Open"
    $status = "Open";

    // Validate input
    if (empty($ticketNum) || empty($deviceType) || empty($serialNum) || empty($clientID)) {
        die("Error: All fields are required.");
    }

    if (!is_numeric($ticketNum) || !is_numeric($serialNum) || !is_numeric($clientID)) {
        die("Error: Ticket Number, Serial Number, and Client ID must be valid numbers.");
    }

    // Check for duplicate TicketNum
    $checkStmt = $conn->prepare("SELECT TicketNum FROM Ticket WHERE TicketNum = ?");
    if ($checkStmt === false) {
        die("Error: Failed to prepare the SQL statement. " . $conn->error);
    }
    $checkStmt->bind_param("i", $ticketNum);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        die("Error: A ticket with Ticket Number $ticketNum already exists.");
    }
    $checkStmt->close();

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO Ticket (TicketNum, DeviceType, SerialNum, ClientID, Status) 
                            VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error: Failed to prepare the SQL statement. " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("isiss", $ticketNum, $deviceType, $serialNum, $clientID, $status);

    // Execute the query
    if ($stmt->execute()) {
        echo "Ticket submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
