<?php
include 'template.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $employeeID = isset($_POST['employeeID']) ? trim($_POST['employeeID']) : null;

    if (empty($name) || empty($employeeID)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    if (!is_numeric($employeeID)) {
        echo json_encode(["status" => "error", "message" => "Employee ID must be a valid number."]);
        exit;
    }

    // Check for duplicate
    $checkStmt = $conn->prepare("SELECT EmployeeID FROM Employee WHERE EmployeeID = ?");
    if (!$checkStmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit;
    }
    $checkStmt->bind_param("i", $employeeID);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "An employee with Employee ID $employeeID already exists."]);
        $checkStmt->close();
        exit;
    }
    $checkStmt->close();

    // Insert employee
    $stmt = $conn->prepare("INSERT INTO Employee (EmployeeID, Name) VALUES (?, ?)");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Insert failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("is", $employeeID, $name);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Employee added successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Insert error: " . $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
?>