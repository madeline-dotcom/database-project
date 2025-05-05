<?php
include 'template.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketNum = isset($_POST['ticketNum']) ? trim($_POST['ticketNum']) : null;
    $deviceType = isset($_POST['deviceType']) ? trim($_POST['deviceType']) : null;
    $serialNum = isset($_POST['serialNum']) ? trim($_POST['serialNum']) : null;
    $clientID = isset($_POST['clientID']) ? trim($_POST['clientID']) : null;
    $purchaseDate = isset($_POST['purchaseDate']) ? trim($_POST['purchaseDate']) : null;
    $employeeID = null;
    $status = "Open";

    if (empty($ticketNum) || empty($deviceType) || empty($serialNum) || empty($clientID) || empty($purchaseDate)) {
        $message = "Error: All fields are required.";
    } elseif (!is_numeric($ticketNum) || !is_numeric($serialNum) || !is_numeric($clientID)) {
        $message = "Error: All fields must be valid numbers.";
    } else {
        $checkStmt = $conn->prepare("SELECT TicketNum FROM Ticket WHERE TicketNum = ?");
        if (!$checkStmt) {
            $message = "Error: " . $conn->error;
        } else {
            $checkStmt->bind_param("i", $ticketNum);
            $checkStmt->execute();
            $checkStmt->store_result();
            //Insert the ticket if it doesn't exist
            if ($checkStmt->num_rows > 0) {
                $message = "Error: A ticket with Ticket Number $ticketNum already exists.";
            } else {
                $stmt = $conn->prepare("INSERT INTO Ticket (TicketNum, DeviceType, SerialNum, ClientID, EmployeeID, Status) 
                                        VALUES (?, ?, ?, ?, ?, ?)");
                if (!$stmt) {
                    $message = "Error: " . $conn->error;
                } else {
                    $stmt->bind_param("isiiis", $ticketNum, $deviceType, $serialNum, $clientID, $employeeID, $status);
                    if ($stmt->execute()) {
                        $message = "Ticket submitted successfully!";
                    } else {
                        $message = "Error: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
            $checkStmt->close();
            //Insert device into the Device table
            $deviceStmt = $conn->prepare("INSERT INTO Device (SerialNum, DeviceType, PurchasedDate, ClientID, TicketNum) VALUES (?, ?, ?, ?, ?)");
            if (!$deviceStmt) {
                $message = "Error: " . $conn->error;
            } else {
                $deviceStmt->bind_param("ssssi", $serialNum, $deviceType, $purchaseDate, $clientID, $ticketNum);
                if (!$deviceStmt->execute()) {
                    $message = "Error: " . $deviceStmt->error;
                }
                $deviceStmt->close();
            }
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dde4ff;
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
            max-width: 600px;
            margin: 80px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            border: 1px solid #000;
        }

        .message {
            font-size: 22px;
            font-weight: bold;
            color: <?= (str_starts_with($message, "Ticket submitted")) ? "#006400" : "#B22222" ?>;
            background-color: <?= (str_starts_with($message, "Ticket submitted")) ? "#e6ffe6" : "#ffe6e6" ?>;
            padding: 20px;
            border: 1px solid <?= (str_starts_with($message, "Ticket submitted")) ? "#006400" : "#B22222" ?>;
            border-radius: 6px;
        }

        .back-button {
            margin-top: 30px;
            display: inline-block;
            padding: 12px 20px;
            background-color: #c9abd1;
            color: #000;
            border: 1px solid #000;
            border-radius: 6px;
            font-weight: bold;
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
    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <a href="../pages/TicketSubmission.php" class="back-button">← Back to Ticket Form</a>
</div>
</body>
</html>
