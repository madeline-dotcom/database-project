<?php
include 'template.php';

// Handle form submission via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted ticket number from the POST data
    $ticketNum = $_POST['ticketNum'] ?? null;

    // Validate that the ticket number was provided
    if (empty($ticketNum)) {
        // Stop execution and display error if no ticket number was entered
        die("Ticket Number is required.");
    }

    // SQL query to update the status of the ticket
    $updateQuery = "UPDATE Ticket SET Status = 'Closed' WHERE TicketNum = $ticketNum";

    // Execute the update query
    if ($conn->query($updateQuery)) {
        // Check if any rows were affected
        // Check if any ticket was actually updated
        if ($conn->affected_rows > 0) {
            echo "Ticket #$ticketNum has been successfully closed.";
        } 
        else {
            echo "No ticket found with Ticket Number #$ticketNum.";
        }
    } 
    else {
        // handle SQL errors
        echo "Error closing ticket: " . $conn->error;
    }
}

$conn->close();
?>
