<?php
include 'template.php'; 

$message = "";
$rows = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve input values
    $ticketNum = $_POST['ticketNum'] ?? null;
    $employeeID = $_POST['employeeID'] ?? null;
    $deviceType = $_POST['deviceType'] ?? null;
    $serialNum = $_POST['serialNum'] ?? null;
    $clientID = $_POST['clientID'] ?? null;
    $status = $_POST['status'] ?? null;

    // Build the SQL query dynamically
    $query = "SELECT * FROM Ticket WHERE 1=1"; 
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
        $message = "Prepare failed: " . $conn->error;
    } else {
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (is_null($row['EmployeeID'])) {
                    $row['EmployeeID'] = "N/A";
                }
                $rows[] = $row;
            }
        } else {
            $message = "No tickets found matching the search criteria.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ticket Search Result</title>
    <style>
        body {
            background-color: #dde4ff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            background-color: #fdf1dc;
            display: flex;
            align-items: center;
            padding: 20px 40px;
        }

        .logo {
            width: 40px;
            margin-right: 20px;
        }

        .company-name {
            font-size: 26px;
            font-weight: bold;
            color: #000;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #000;
        }

        h2 {
            text-align: center;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #a4d3f4;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            color: #B22222;
            font-weight: bold;
        }

        .back-button {
            margin-top: 30px;
            display: block;
            text-align: center;
            padding: 10px 20px;
            background-color: #c9abd1;
            color: #000;
            border: 1px solid #000;
            font-weight: bold;
            border-radius: 6px;
            text-decoration: none;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }

        .back-button:hover {
            background-color: #b89bc3;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <img src="../images/ant.png" alt="Logo" class="logo">
    <div class="company-name">ANT IT Company</div>
</div>

<div class="container">
    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php elseif (!empty($rows)): ?>
        <h2>Ticket Search Result</h2>
        <table>
            <tr>
                <th>Ticket Number</th>
                <th>Employee ID</th>
                <th>Device Type</th>
                <th>Serial Number</th>
                <th>Client ID</th>
                <th>Status</th>
            </tr>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['TicketNum']) ?></td>
                    <td><?= htmlspecialchars($row['EmployeeID']) ?></td>
                    <td><?= htmlspecialchars($row['DeviceType']) ?></td>
                    <td><?= htmlspecialchars($row['SerialNum']) ?></td>
                    <td><?= htmlspecialchars($row['ClientID']) ?></td>
                    <td><?= htmlspecialchars($row['Status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <a class="back-button" href="../html/TicketHistory.html">← Back to Ticket History</a>
</div>

</body>
</html>
