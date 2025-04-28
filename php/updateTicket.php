<?php
$conn = mysqli_connect("localhost", "root", "", "ANT_IT");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$ticketNum = $_POST['ticketNum'];
$newStatus = $_POST['newStatus'];

$query = "UPDATE Tickets SET Status='$newStatus' WHERE TicketNum='$ticketNum'";
if (mysqli_query($conn, $query)) {
    echo "Ticket updated successfully.";
} else {
    echo "Error updating ticket: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
