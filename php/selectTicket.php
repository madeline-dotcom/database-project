<?php
$conn = mysqli_connect("localhost", "root", "", "ANT_IT");

// check if the connection failed and stop execution with error message
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$ticketNum = $_POST['ticketNum'];
$query = "SELECT * FROM Tickets WHERE TicketNum = '$ticketNum'";
// execute query on database
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "Ticket Number: " . $row["TicketNum"] . " - Status: " . $row["Status"] . "<br>";
    }
} else {
    echo "No results found.";
}

mysqli_close($conn);
?>
