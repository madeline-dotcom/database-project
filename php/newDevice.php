<?php
include 'template.php'; 

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serialNum = $_POST['serial'];
    $clientID = $_POST['clientID'];
    $deviceType = $_POST['type'];
    $purchasedDate = !empty($_POST['date']) ? $_POST['date'] : NULL;

    if (empty($serialNum) || empty($clientID) || empty($deviceType)) {
        $error = "Serial Number, Client ID, and Device Type are required.";
    } elseif (!is_numeric($clientID)) {
        $error = "Client ID must be a valid number.";
    } else {
        // Check if client exists
        $clientCheck = $conn->prepare("SELECT ClientID FROM Client WHERE ClientID = ?");
        if (!$clientCheck) {
            $error = "Prepare failed (client check): " . $conn->error;
        } else {
            $clientCheck->bind_param("i", $clientID);
            $clientCheck->execute();
            $clientCheck->store_result();
            if ($clientCheck->num_rows === 0) {
                $error = "Client ID $clientID does not exist. Please use a valid Client ID.";
            }
            $clientCheck->close();
        }

        // Check if device already exists
        if (empty($error)) {
            $checkStmt = $conn->prepare("SELECT SerialNum FROM Device WHERE SerialNum = ?");
            if (!$checkStmt) {
                $error = "Prepare failed (device check): " . $conn->error;
            } else {
                $checkStmt->bind_param("i", $serialNum);
                $checkStmt->execute();
                $checkStmt->store_result();
                if ($checkStmt->num_rows > 0) {
                    $error = "A device with Serial Number $serialNum already exists.";
                }
                $checkStmt->close();
            }
        }

        // Insert the new device
        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO Device (SerialNum, DeviceType, ClientID, PurchasedDate) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                $error = "Prepare failed (insert): " . $conn->error;
            } else {
                $stmt->bind_param("ssis", $serialNum, $deviceType, $clientID, $purchasedDate);
                if ($stmt->execute()) {
                    $message = "New device added successfully.";
                } else {
                    $error = "Error inserting device: " . $stmt->error;
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
    <title>Add Device Result</title>
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
            margin-top: 20px;
            border-radius: 6px;
            font-weight: bold;
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

    <a href="../pages/DeviceMng.php" class="back-button">← Back to Device Management</a>
</div>

</body>
</html>
