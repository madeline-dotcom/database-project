<?php
session_start();
require_once 'template.php'; // Includes the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clientID'])) {
    $clientID = $_POST['clientID'];

    // Query to fetch tickets for the given client ID
    $sql = "SELECT TicketNum, DeviceType, Status FROM Ticket WHERE ClientID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $clientID);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Check if tickets exist
        if ($result->num_rows > 0) {
            echo "<h1>Tickets for Client ID: $clientID</h1>";
            echo "<table border='1'>";
            echo "<tr><th>Ticket Number</th><th>Device Type</th><th>Status</th></tr>";

            // Fetch and display tickets
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['TicketNum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['DeviceType']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Status']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No tickets found for Client ID: $clientID.";
        }
    } else {
        echo "Error retrieving tickets. Please try again.";
    }

    $stmt->close();
} else {
    echo "Invalid request. Please provide an client ID.";
}

$conn->close();
?>