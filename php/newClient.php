<?php
include 'template.php'; 

$message = '';
$isError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clientID = $_POST['clientID'];
    $name = $_POST['name'];
    $location = $_POST['location'];

    if (empty($clientID) || empty($name) || empty($location)) {
        $message = "Error: All fields are required.";
        $isError = true;
    } elseif (!is_numeric($clientID)) {
        $message = "Error: Client ID must be a number.";
        $isError = true;
    } else {
        $checkStmt = $conn->prepare("SELECT ClientID FROM Client WHERE ClientID = ?");
        if ($checkStmt === false) {
            $message = "Prepare failed: " . $conn->error;
            $isError = true;
        } else {
            $checkStmt->bind_param("i", $clientID);
            $checkStmt->execute();
            $checkStmt->store_result();
            if ($checkStmt->num_rows > 0) {
                $message = "Error: A client with Client ID $clientID already exists.";
                $isError = true;
            } else {
                $stmt = $conn->prepare("INSERT INTO Client (ClientID, Name, LocationName) VALUES (?, ?, ?)");
                if ($stmt === false) {
                    $message = "Prepare failed: " . $conn->error;
                    $isError = true;
                } else {
                    $stmt->bind_param("iss", $clientID, $name, $location);
                    if ($stmt->execute()) {
                        $message = "✅ New client added successfully.";
                    } else {
                        $message = "Error: " . $stmt->error;
                        $isError = true;
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
    <title>Add Client</title>
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
    <a href="../html/ClientMng.html" class="back-button">← Back to Client Management</a>
</div>

</body>
</html>
