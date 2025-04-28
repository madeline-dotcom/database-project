<?php
include 'db_config.php'; 

// Query all devices
$sql = "SELECT * FROM Device";
$result = $conn->query($sql);

echo "<h2>Device Management</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='8'>";
    echo "<tr>
            <th>Serial Number</th>
            <th>Client ID</th>
            <th>Last Worked On</th>
            <th>Purchased Date</th>
            <th>Ticket Number</th>
          </tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['SerialNum']) . "</td>";
        echo "<td>" . htmlspecialchars($row['ClientID']) . "</td>";
        echo "<td>" . (!empty($row['LastWorkedOn']) ? htmlspecialchars($row['LastWorkedOn']) : "N/A") . "</td>";
        echo "<td>" . (!empty($row['PurchasedDate']) ? htmlspecialchars($row['PurchasedDate']) : "N/A") . "</td>";
        echo "<td>" . (!empty($row['TicketNum']) ? htmlspecialchars($row['TicketNum']) : "N/A") . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No devices found.";
}

$conn->close();
?>
