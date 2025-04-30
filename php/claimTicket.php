<?php
include 'template.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve input values
    $ticketNum = $_POST['ticketNum'] ?? null;
    $employeeID = $_POST['employeeID'] ?? null;

    // Validate input
    if (empty($ticketNum) || empty($employeeID)) {
        die("Ticket Number and Employee ID are required.");
    }

    // Check if the EmployeeID exists
    $checkStmt = $conn->prepare("SELECT EmployeeID FROM Employee WHERE EmployeeID = ?");
    $checkStmt->bind_param("i", $employeeID);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows === 0) {
        die("Error: Employee ID #$employeeID does not exist.");
    }

    $checkStmt->close();

    // Check if the ticket exists and is open
    $ticketCheckStmt = $conn->prepare("SELECT Status FROM Ticket WHERE TicketNum = ?");
    $ticketCheckStmt->bind_param("i", $ticketNum);
    $ticketCheckStmt->execute();
    $ticketCheckStmt->bind_result($status);
    if ($ticketCheckStmt->fetch()) {
        if ($status !== 'Open') {
            die("Error: Ticket #$ticketNum is not open and cannot be claimed.");
        }
    } else {
        die("Error: Ticket #$ticketNum does not exist.");
    }
    $ticketCheckStmt->close();

    // Prepare the SQL query to update the ticket
    $stmt = $conn->prepare("UPDATE Ticket SET EmployeeID = ?, Status = 'In Progress' WHERE TicketNum = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ii", $employeeID, $ticketNum);

    // Execute the query
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Ticket #$ticketNum has been successfully claimed by Employee #$employeeID.";
        } else {
            echo "No ticket found with Ticket Number #$ticketNum.";
        }
    } else {
        echo "Error claiming ticket: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>