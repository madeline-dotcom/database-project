<?php
$conn = mysqli_connect("localhost", "root", "", "your_database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$ticketNum = $_POST['ticketNum'];
$query = "SELECT * FROM Tickets WHERE TicketNum = '$ticketNum'";
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
