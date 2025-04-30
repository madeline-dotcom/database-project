<?php
include 'template.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve input values
    $ticketNum = $_POST['ticketNum'] ?? null;
    $employeeID = $_POST['employeeID'] ?? null;
    $deviceType = $_POST['deviceType'] ?? null;
    $serialNum = $_POST['serialNum'] ?? null;
    $clientID = $_POST['clientID'] ?? null;
    $status = $_POST['status'] ?? null;

    // Build the SQL query dynamically
    $query = "SELECT * FROM Ticket WHERE 1=1"; // Start with a base query
    $params = [];
    $types = "";

    if (!empty($ticketNum)) {
        $query .= " AND TicketNum = ?";
        $params[] = $ticketNum;
        $types .= "i";
    }
    if (!empty($employeeID)) {
        $query .= " AND EmployeeID = ?";
        $params[] = $employeeID;
        $types .= "i";
    }
    if (!empty($deviceType)) {
        $query .= " AND LOWER(DeviceType) LIKE ?";
        $params[] = "%" . strtolower($deviceType) . "%";
        $types .= "s";
    }
    if (!empty($serialNum)) {
        $query .= " AND SerialNum = ?";
        $params[] = $serialNum;
        $types .= "i";
    }
    if (!empty($clientID)) {
        $query .= " AND ClientID = ?";
        $params[] = $clientID;
        $types .= "i";
    }
    if (!empty($status)) {
        $query .= " AND Status = ?";
        $params[] = $status;
        $types .= "s";
    }

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();


    // Display the results
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

        while ($row = $result->fetch_assoc()) {
            // Check if employeeID is null and set it to N/A
            if (is_null($row['EmployeeID'])) {
                $row['EmployeeID'] = "N/A";
            }
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
        echo "No tickets found matching the search criteria.";
    }

    $stmt->close();
}

$conn->close();
?>
