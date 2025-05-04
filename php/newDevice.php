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

    // Check if the client exists
    $clientCheck = $conn->prepare("SELECT ClientID FROM Client WHERE ClientID = ?");
    if (!$clientCheck) {
        die("Prepare failed (client check): " . $conn->error);
    }
    $clientCheck->bind_param("i", $clientID);
    $clientCheck->execute();
    $clientCheck->store_result();
    if ($clientCheck->num_rows === 0) {
        $clientCheck->close();
        die("Error: Client ID $clientID does not exist. Please use a valid Client ID.");
    }
    $clientCheck->close();

    // Check if the device already exists
    $checkStmt = $conn->prepare("SELECT SerialNum FROM Device WHERE SerialNum = ?");
    if ($checkStmt === false) {
        die("Prepare failed (device check): " . $conn->error);
    }
    $checkStmt->bind_param("i", $serialNum);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $checkStmt->close();
        die("Error: A device with Serial Number $serialNum already exists.");
    }
    $checkStmt->close();

    // Insert the new device
    $stmt = $conn->prepare("INSERT INTO Device (SerialNum, DeviceType, ClientID, PurchasedDate) 
                            VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed (insert): " . $conn->error);
    }

    $stmt->bind_param("ssis", $serialNum, $deviceType, $clientID, $purchasedDate);

    if ($stmt->execute()) {
        echo "New device added successfully.";
    } else {
        echo "Error inserting device: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
