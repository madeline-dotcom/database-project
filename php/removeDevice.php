<?php
include 'template.php'; // Include the database connection

$message = "";
$success = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serialNum = $_POST['serial'];

    if (empty($serialNum)) {
        $message = "Error: Serial Number is required.";
        $success = false;
    } else {
        $stmt = $conn->prepare("DELETE FROM Device WHERE SerialNum = ?");
        if ($stmt === false) {
            $message = "Error: Failed to prepare SQL statement. " . $conn->error;
            $success = false;
        } else {
            $stmt->bind_param("i", $serialNum);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $message = "✅ Device with Serial Number <strong>$serialNum</strong> has been successfully removed.";
                } else {
                    $message = "⚠️ No device found with Serial Number <strong>$serialNum</strong>.";
                    $success = false;
                }
            } else {
                $message = "Error: Failed to execute SQL statement. " . $stmt->error;
                $success = false;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Remove Device</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #dde4ff;
        }

        .container {
            max-width: 600px;
            margin: 80px auto;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #000;
        }

        h1 {
            color: #000;
        }

        .message {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            color: <?= $success ? '#006400' : '#B22222' ?>;
        }

        .back-button {
            margin-top: 30px;
            display: inline-block;
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
    <div class="container">
        <h1>Remove Device</h1>
        <div class="message"><?= $message ?></div>
        <a class="back-button" href="../html/DeviceMng.html">← Back to Device Management</a>
    </div>
</body>
</html>
