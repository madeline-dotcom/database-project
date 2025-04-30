<?php
include 'template.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketNum = $_POST['ticketNum'] ?? null;

    // Validate input
    if (empty($ticketNum)) {
        die("Ticket Number is required.");
    }

    // Check if the ticket exists and is not already closed
    $checkStmt = $conn->prepare("SELECT Status FROM Ticket WHERE TicketNum = ?");
    $checkStmt->bind_param("i", $ticketNum);
    $checkStmt->execute();
    $checkStmt->bind_result($currentStatus);
    if ($checkStmt->fetch()) {
        if ($currentStatus === 'Closed') {
            die("Error: Ticket #$ticketNum is already closed.");
        }
    } else {
        die("Error: Ticket #$ticketNum does not exist.");
    }
    $checkStmt->close();

    // Update the ticket status to 'Closed'
    $stmt = $conn->prepare("UPDATE Ticket SET Status = 'Closed' WHERE TicketNum = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $ticketNum);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Ticket #$ticketNum has been successfully closed.";
        } else {
            echo "No ticket found with Ticket Number #$ticketNum.";
        }
    } else {
        echo "Error closing ticket: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
