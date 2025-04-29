<?php
include 'db_config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketNum = $_POST['ticketNum'];

    if (is_numeric($ticketNum)) {
        $stmt = $conn->prepare("UPDATE Tickets SET Status = 'Resolved' WHERE TicketNum = ?");
        $stmt->bind_param("i", $ticketNum);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Ticket closed successfully!";
            } else {
                echo "No ticket found with that Ticket Number.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid Ticket Number.";
    }
}

$conn->close();
?>
