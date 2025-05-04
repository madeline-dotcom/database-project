<?php
include 'template.php'; 

$message = '';
$isError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clientID = $_POST['clientID'];

    if (is_numeric($clientID)) {
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM Ticket WHERE ClientID = ? OR SerialNum IN (SELECT SerialNum FROM Device WHERE ClientID = ?)");
        $checkStmt->bind_param("ii", $clientID, $clientID);
        if ($checkStmt->execute()) {
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            $checkStmt->close();

            if ($count > 0) {
                $message = "❌ Cannot remove client. They are assigned to tickets or devices.";
                $isError = true;
            } else {
                $stmt = $conn->prepare("DELETE FROM Client WHERE ClientID = ?");
                $stmt->bind_param("i", $clientID);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $message = "✅ Client removed successfully.";
                    } else {
                        $message = "⚠️ No client found with that ID.";
                        $isError = true;
                    }
                } else {
                    $message = "Error: " . $stmt->error;
                    $isError = true;
                }
                $stmt->close();
            }
        } else {
            $message = "Error: " . $checkStmt->error;
            $isError = true;
        }
    } else {
        $message = "Invalid Client ID.";
        $isError = true;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Remove Client</title>
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
            max-width: 600px;
            margin: 80px auto;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        .message {
            font-size: 18px;
            padding: 20px;
            color: #000;
            border-radius: 6px;
            border: 1px solid #000;
            background-color: <?= $isError ? '#ffcccc' : '#ccffcc' ?>;
            margin-bottom: 20px;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #c9abd1;
            color: #000;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #000;
            border-radius: 6px;
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
    <div class="message"><?= htmlspecialchars($message) ?></div>
    <a class="back-button" href="../pages/ClientMng.php">← Back to Client Management</a>
</div>

<script>
  // Reload page if restored from back/forward cache (after logout)
  window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
      window.location.reload();
    }
  });
</script>
</body>
</html>
