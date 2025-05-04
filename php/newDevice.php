<?php
include 'template.php'; 
$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serialNum = $_POST['serial'];
    $clientID = $_POST['clientID'];
    $deviceType = $_POST['type'];
    $purchasedDate = $_POST['date'];

    // Validate required input
    if (empty($serialNum) || empty($clientID) || empty($deviceType) || empty($purchasedDate)) {
        $error = "Error: All fields are required.";
    } elseif (!is_numeric($clientID)) {
        $error = "Error: Client ID must be numeric.";
    } elseif (!is_numeric($serialNum)) {
        $error = "Error: Serial Number must be numeric.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $purchasedDate)) {
        $error = "Error: Purchase Date must be in the format YYYY-MM-DD.";
    } else {
        // Check if the device already exists
        $checkStmt = $conn->prepare("SELECT SerialNum FROM Device WHERE SerialNum = ?");
        if (!$checkStmt) {
            $error = "Prepare failed: " . $conn->error;
        } else {
            $checkStmt->bind_param("i", $serialNum);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                $error = "Error: A device with Serial Number $serialNum already exists.";
            } else {
                // Insert new device
                $stmt = $conn->prepare("INSERT INTO Device (SerialNum, DeviceType, ClientID, PurchasedDate) VALUES (?, ?, ?, ?)");
                if (!$stmt) {
                    $error = "Prepare failed: " . $conn->error;
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
            $checkStmt->close();
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Device Submission</title>
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
    <h1>New Device Submission</h1>

    <?php if (!empty($message)): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php elseif (!empty($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <a class="back-button" href="../html/DeviceMng.html">← Back to Device Management</a>
</div>

</body>
</html>
