<?php
session_start();
require_once 'template.php'; // Includes the database connection

$message = "";
$error = "";
$rows = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clientID'])) {
    $clientID = $_POST['clientID'];

    $sql = "SELECT TicketNum, DeviceType, Status FROM Ticket WHERE ClientID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $clientID);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        } else {
            $error = "No tickets found for Client ID: $clientID.";
        }
    } else {
        $error = "Error retrieving tickets. Please try again.";
    }

    $stmt->close();
} else {
    $error = "Invalid request. Please provide a client ID.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Client Tickets</title>
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
            max-width: 800px;
            margin: 50px auto;
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #000;
        }

        h1 {
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
            color: #000;
        }

        th {
            background-color: #a4d3f4;
        }

        .message {
            text-align: center;
            padding: 15px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 20px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .success {
            background-color: #e6ffe6;
            color: #155724;
            border: 1px solid #c3e6cb;
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
    <?php if (!empty($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php elseif (!empty($rows)): ?>
        <h1>Tickets for Client ID: <?= htmlspecialchars($clientID) ?></h1>
        <table>
            <tr>
                <th>Ticket Number</th>
                <th>Device Type</th>
                <th>Status</th>
            </tr>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['TicketNum']) ?></td>
                    <td><?= htmlspecialchars($row['DeviceType']) ?></td>
                    <td><?= htmlspecialchars($row['Status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <a class="back-button" href="../pages/myTicketsClient.php">← Back to My Tickets</a>
</div>

</body>
</html>
