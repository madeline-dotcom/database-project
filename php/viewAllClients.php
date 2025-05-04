<?php
session_start();
require_once 'template.php'; // Includes the database connection

$clients = [];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userType'])) {
    $sql = "SELECT ClientID, Name FROM Client";
    $stmt = $conn->prepare($sql);
    if ($stmt && $stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $clients[] = $row;
        }
        $stmt->close();
    } else {
        $error = "Error retrieving clients. Please try again.";
    }
} else {
    $error = "Invalid request. Please provide an admin user type.";
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Client List</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #dde4ff;
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
            margin: 60px auto;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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

        .error {
            color: red;
            font-weight: bold;
            text-align: center;
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
    <h1>Client List</h1>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php elseif (count($clients) === 0): ?>
        <div class="error">No clients found.</div>
    <?php else: ?>
        <table>
            <tr><th>Client ID</th><th>Name</th></tr>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= htmlspecialchars($client['ClientID']) ?></td>
                    <td><?= htmlspecialchars($client['Name'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <a class="back-button" href="../html/ClientMng.html">← Back to Client Management</a>
</div>

</body>
</html>
