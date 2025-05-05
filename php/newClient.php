<?php
include 'template.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $clientID = isset($_POST['clientID']) ? trim($_POST['clientID']) : null;
    $location = isset($_POST['location']) ? trim($_POST['location']) : null;

    if (empty($name) || empty($clientID) || empty($location)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    if (!is_numeric($clientID)) {
        echo json_encode(["status" => "error", "message" => "Employee ID must be a valid number."]);
        exit;
    }

    // Check for duplicate
    $checkStmt = $conn->prepare("SELECT ClientID FROM Client WHERE ClientID = ?");
    if (!$checkStmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit;
    }
    $checkStmt->bind_param("i", $clientID);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "An client with Client ID $clientID already exists."]);
        $checkStmt->close();
        exit;
    }
    $checkStmt->close();

    // Insert client
    $stmt = $conn->prepare("INSERT INTO Client (ClientID, Name, LocationName) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("iss", $clientID, $name, $location);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Client added successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Insert error: " . $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
?>