<?php
include 'template.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serialNum = $_POST['serial'];
    $clientID = $_POST['clientID'];
    $deviceType = $_POST['type'];
    $purchasedDate = !empty($_POST['date']) ? $_POST['date'] : NULL;

    // Validate input
    if (empty($serialNum) || empty($clientID) || empty($deviceType)) {
        die("Error: Serial Number, Client ID, and Device Type are required.");
    }

    if (!is_numeric($clientID)) {
        die("Error: Client ID must be a valid number.");
    }

    // Check if the device already exists
    $checkStmt = $conn->prepare("SELECT SerialNum FROM Device WHERE SerialNum = ?");
    if ($checkStmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $checkStmt->bind_param("i", $serialNum);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        die("Error: A device with Serial Number $serialNum already exists.");
    }
    $checkStmt->close();

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO Device (SerialNum, DeviceType, ClientID, PurchasedDate) 
                            VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ssis", $serialNum, $deviceType, $clientID, $purchasedDate);

    // Execute the query
    if ($stmt->execute()) {
        echo "New device added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
