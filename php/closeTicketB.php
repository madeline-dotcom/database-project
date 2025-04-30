<?php
include 'template.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketNum = $_POST['ticketNum'] ?? null;

    if (empty($ticketNum)) {
        die("Ticket Number is required.");
    }

    $updateQuery = "UPDATE Ticket SET Status = 'Closed' WHERE TicketNum = $ticketNum";
    if ($conn->query($updateQuery)) {
        if ($conn->affected_rows > 0) {
            echo "Ticket #$ticketNum has been successfully closed.";
        } else {
            echo "No ticket found with Ticket Number #$ticketNum.";
        }
    } else {
        echo "Error closing ticket: " . $conn->error;
    }
}

$conn->close();
?>
