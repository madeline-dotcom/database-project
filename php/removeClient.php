<?php
include 'template.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clientID = $_POST['clientID'];

    if (!is_numeric($clientID)) {
        echo json_encode(["status" => "error", "message" => "Invalid Client ID."]);
        exit;
    }
    // Check if client is assigned to tickets or devices
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM Ticket WHERE ClientID = ? OR SerialNum IN (SELECT SerialNum FROM Device WHERE ClientID = ?)");
    if (!$checkStmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit;
    }
    
    $checkStmt->bind_param("ii", $clientID, $clientID); // bind both clientID parameters
    if (!$checkStmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Query error: " . $checkStmt->error]);
        exit;
    }
    
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();
    
    if ($count > 0) {
        echo json_encode(["status" => "error", "message" => "Cannot remove client. They are still assigned to a ticket or device."]);
        exit;
    }

    // Proceed with deletion
    $stmt = $conn->prepare("DELETE FROM Client WHERE ClientID = ?");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("i", $clientID);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["status" => "success", "message" => "Client removed successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "No client found with that ID."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Delete failed: " . $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
?>