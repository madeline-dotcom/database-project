<?php
include 'template.php'; 
$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketNum = $_POST['ticketNum'] ?? null;

    if (empty($ticketNum)) {
        $error = "Ticket Number is required.";
    } else {
        $checkStmt = $conn->prepare("SELECT Status FROM Ticket WHERE TicketNum = ?");
        $checkStmt->bind_param("i", $ticketNum);
        $checkStmt->execute();
        $checkStmt->bind_result($currentStatus);
        
        if ($checkStmt->fetch()) {
            if ($currentStatus === 'Closed') {
                $error = "Error: Ticket #$ticketNum is already closed.";
            }
        } else {
            $error = "Error: Ticket #$ticketNum does not exist.";
        }
        $checkStmt->close();

        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE Ticket SET Status = 'Closed' WHERE TicketNum = ?");
            if ($stmt === false) {
                $error = "Prepare failed: " . $conn->error;
            } else {
                $stmt->bind_param("i", $ticketNum);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $message = "Ticket #$ticketNum has been successfully closed.";
                    } else {
                        $error = "No ticket found with Ticket Number #$ticketNum.";
                    }
                } else {
                    $error = "Error closing ticket: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Close Ticket</title>
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
            max-width: 600px;
            background-color: white;
            margin: 60px auto;
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        .message {
            padding: 15px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 20px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .back-button {
            margin-top: 30px;
            display: inline-block;
            padding: 12px 20px;
            background-color: #c9abd1;
            color: #000;
            border: 1px solid #000;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
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
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php elseif (!empty($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <a href="../pages/MyTickets.php" class="back-button">← Back to My Tickets</a>
</div>
</body>
</html>
