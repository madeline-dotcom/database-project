<?php
include 'template.php'; 

// Retrieve user input
$serialNum = $_POST['serial'] ?? null;
$clientID = $_POST['clientID'] ?? null;
$deviceType = $_POST['type'] ?? null; // Corrected key
$notRecent = $_POST['notRecent'] ?? null;
$purchasedDate = $_POST['purchasedDate'] ?? null; // New input for Purchased Date

// Build the SQL query dynamically
$query = "SELECT * FROM Device WHERE 1=1"; // Start with a base query
$params = [];
$types = "";

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
if (!empty($notRecent)) {
    $query .= " AND (LastWorkedOn IS NULL OR LastWorkedOn < DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
}
if (!empty($purchasedDate)) {
    $query .= " AND PurchasedDate = ?";
    $params[] = $purchasedDate;
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

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . (!empty($row['SerialNum']) ? htmlspecialchars($row['SerialNum']) : "N/A") . "</td>";
        echo "<td>" . (!empty($row['ClientID']) ? htmlspecialchars($row['ClientID']) : "N/A") . "</td>";
        echo "<td>" . (!empty($row['LastWorkedOn']) ? htmlspecialchars($row['LastWorkedOn']) : "N/A") . "</td>";
        echo "<td>" . (!empty($row['PurchasedDate']) ? htmlspecialchars($row['PurchasedDate']) : "N/A") . "</td>";
        echo "<td>" . (!empty($row['TicketNum']) ? htmlspecialchars($row['TicketNum']) : "N/A") . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No devices found matching the search criteria.";
}

$stmt->close();
$conn->close();
?>
