<?php
include 'template.php'; 

$serialNum = $_POST['serial'] ?? null;
$clientID = $_POST['clientID'] ?? null;
$deviceType = $_POST['type'] ?? null;
$notRecent = $_POST['notRecent'] ?? null;
$purchasedDate = $_POST['purchasedDate'] ?? null;

$query = "SELECT * FROM Device WHERE 1=1";
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

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Device Management</title>
    <style>
        body {
            margin: 0;
            background-color: #dde4ff;
            font-family: Arial, sans-serif;
        }

        .top-bar {
            background-color: #fdf1dc;
            padding: 20px 40px;
            display: flex;
            align-items: center;
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
            max-width: 900px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #003366;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            border: 1px solid #999;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #a4d3f4;
            color: #000;
        }

        td {
            color: #333;
        }

        .message {
            text-align: center;
            font-size: 18px;
            color: #000;
            background-color: #f8d7da;
            padding: 12px;
            margin-top: 30px;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
        }

        .back-button {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #c9abd1;
            color: #000;
            font-weight: bold;
            border: 1px solid #000;
            border-radius: 6px;
            text-decoration: none;
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
    <h2>Device Management</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Serial Number</th>
                <th>Client ID</th>
                <th>Last Worked On</th>
                <th>Purchased Date</th>
                <th>Ticket Number</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['SerialNum'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['ClientID'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['LastWorkedOn'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['PurchasedDate'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['TicketNum'] ?? 'N/A') ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <div class="message">No devices found matching the search criteria.</div>
    <?php endif; ?>

    <div style="text-align: center;">
        <a class="back-button" href="../html/DeviceMng.html">← Back to Device Management</a>
    </div>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
