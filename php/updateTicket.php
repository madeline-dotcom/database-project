<?php
include 'template.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketNum = $_POST['ticketNum'];
    $newStatus = $_POST['newStatus'];

    // Validate input
    if (empty($ticketNum) || empty($newStatus)) {
        die("Ticket Number and New Status are required.");
    }

    // Ensure the new status is valid
    $validStatuses = ['Open', 'In Progress', 'Closed'];
    if (!in_array($newStatus, $validStatuses)) {
        die("Invalid status: $newStatus");
    }

    // Update the ticket status
    $stmt = $conn->prepare("UPDATE Ticket SET Status = ? WHERE TicketNum = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("si", $newStatus, $ticketNum);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Ticket #$ticketNum updated successfully to status '$newStatus'.";
        } else {
            echo "No ticket found with Ticket Number #$ticketNum.";
        }
    } else {
        echo "Error updating ticket: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
