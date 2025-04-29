<?php
include 'template.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketNum = $_POST['ticketNum'];

    if (is_numeric($ticketNum)) {
        // Step 1: Check if the ticket exists and get its current status
        $checkStmt = $conn->prepare("SELECT Status FROM Ticket WHERE TicketNum = ?");
        $checkStmt->bind_param("i", $ticketNum);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentStatus = $row['Status'];

            if ($currentStatus === 'Resolved') {
                echo "The ticket is already resolved.";
            } else {
                // Step 2: Update the ticket status to 'Resolved'
                $stmt = $conn->prepare("UPDATE Ticket SET Status = 'Resolved' WHERE TicketNum = ?");
                $stmt->bind_param("i", $ticketNum);

                if ($stmt->execute()) {
                    echo "Ticket closed successfully!";
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            }
        } else {
            echo "No ticket found with that Ticket Number.";
        }

        $checkStmt->close();
    } else {
        echo "Invalid Ticket Number.";
    }
}

$conn->close();
?>
