<?php
include 'db_config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketNum = $_POST['ticketNum'];

    if (is_numeric($ticketNum)) {
        $stmt = $conn->prepare("SELECT * FROM Tickets WHERE TicketNum = ?");
        $stmt->bind_param("i", $ticketNum);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h2>Ticket Search Result</h2>";

        if ($result->num_rows > 0) {
            echo "<table border='1' cellpadding='8'>";
            echo "<tr>
                    <th>Ticket Number</th>
                    <th>Employee ID</th>
                    <th>Device Type</th>
                    <th>Serial Number</th>
                    <th>Client ID</th>
                    <th>Status</th>
                  </tr>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['TicketNum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['EmployeeID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['DeviceType']) . "</td>";
                echo "<td>" . htmlspecialchars($row['SerialNum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ClientID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Status']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No ticket found with that Ticket Number.";
        }

        $stmt->close();
    } else {
        echo "Invalid Ticket Number.";
    }
}

$conn->close();
?>
