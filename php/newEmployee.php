<?php
include 'template.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and trim input values
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $employeeID = isset($_POST['employeeID']) ? trim($_POST['employeeID']) : null;

    // Validate input
    if (empty($name) || empty($employeeID)) {
        die("Error: All fields are required.");
    }

    if (!is_numeric($employeeID)) {
        die("Error: Employee ID must be a valid number.");
    }

    // Check for duplicate EmployeeID
    $checkStmt = $conn->prepare("SELECT EmployeeID FROM Employee WHERE EmployeeID = ?");
    if ($checkStmt === false) {
        die("Error: Failed to prepare the SQL statement. " . $conn->error);
    }
    $checkStmt->bind_param("i", $employeeID);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        die("Error: An employee with Employee ID $employeeID already exists.");
    }
    $checkStmt->close();

    // Insert the new employee
    $stmt = $conn->prepare("INSERT INTO Employee (EmployeeID, Name) VALUES (?, ?)");
    if ($stmt === false) {
        die("Error: Failed to prepare the SQL statement. " . $conn->error);
    }
    $stmt->bind_param("is", $employeeID, $name);

    if ($stmt->execute()) {
        echo "New employee added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>