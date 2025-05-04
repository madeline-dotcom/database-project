<?php
include 'template.php'; // Include the database connection

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve input values
    $ticketNum = $_POST['ticketNum'] ?? null;
    $employeeID = $_POST['employeeID'] ?? null;

    // Validate input
    if (empty($ticketNum) || empty($employeeID)) {
        $error = "Ticket Number and Employee ID are required.";
    } else {
        // Check if the EmployeeID exists
        $checkStmt = $conn->prepare("SELECT EmployeeID FROM Employee WHERE EmployeeID = ?");
        if (!$checkStmt) {
            $error = "Database error: " . $conn->error;
        } else {
            $checkStmt->bind_param("i", $employeeID);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows === 0) {
                $error = "Error: Employee ID #$employeeID does not exist.";
            }
            $checkStmt->close();
        }

        // Proceed only if no error
        if (empty($error)) {
            // Check if the ticket exists and is open
            $ticketCheckStmt = $conn->prepare("SELECT Status FROM Ticket WHERE TicketNum = ?");
            $ticketCheckStmt->bind_param("i", $ticketNum);
            $ticketCheckStmt->execute();
            $ticketCheckStmt->bind_result($status);

            if ($ticketCheckStmt->fetch()) {
                if ($status !== 'Open') {
                    $error = "Error: Ticket #$ticketNum is not open and cannot be claimed.";
                }
            } else {
                $error = "Error: Ticket #$ticketNum does not exist.";
            }
            $ticketCheckStmt->close();
        }

        // If everything is valid, update the ticket
        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE Ticket SET EmployeeID = ?, Status = 'In Progress' WHERE TicketNum = ?");
            if (!$stmt) {
                $error = "Prepare failed: " . $conn->error;
            } else {
                $stmt->bind_param("ii", $employeeID, $ticketNum);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $message = "Ticket #$ticketNum has been successfully claimed by Employee #$employeeID.";
                    } else {
                        $error = "No ticket found with Ticket Number #$ticketNum.";
                    }
                } else {
                    $error = "Error claiming ticket: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Claim Ticket</title>
    <style>
        body {
            background-color: #dde4ff;
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            background-color: white;
            margin: auto;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #000;
        }

        h1 {
            text-align: center;
            color: #000;
        }

        .message {
            padding: 15px;
            margin-top: 20px;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
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
            display: block;
            margin: 30px auto 0;
            text-align: center;
            background-color: #c9abd1;
            padding: 10px 20px;
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

<div class="container">
    <h1>Claim Ticket</h1>

    <?php if (!empty($message)): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php elseif (!empty($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <a class="back-button" href="../pages/TicketHistory.php">← Back to Ticket History</a>
</div>

</body>
</html>
