<?php
include 'template.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeID = $_POST['employeeID'];

    if (!is_numeric($employeeID)) {
        echo json_encode(["status" => "error", "message" => "Invalid Employee ID."]);
        exit;
    }

    // Check if employee is assigned to tickets
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM Ticket WHERE EmployeeID = ?");
    if (!$checkStmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit;
    }

    $checkStmt->bind_param("i", $employeeID);
    if (!$checkStmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Query error: " . $checkStmt->error]);
        exit;
    }

    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        echo json_encode(["status" => "error", "message" => "Cannot remove employee. They are still assigned to tickets."]);
        exit;
    }

    // Proceed with deletion
    $stmt = $conn->prepare("DELETE FROM Employee WHERE EmployeeID = ?");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $employeeID);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["status" => "success", "message" => "Employee removed successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "No employee found with that ID."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Delete failed: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
