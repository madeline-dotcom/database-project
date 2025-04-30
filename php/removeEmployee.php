<?php
include 'template.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeID = $_POST['employeeID'];

    if (is_numeric($employeeID)) {
        $stmt = $conn->prepare("DELETE FROM Employee WHERE EmployeeID = ?");
        $stmt->bind_param("i", $employeeID);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Employee removed successfully.";
            } else {
                echo "No employee found with that ID.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid Employee ID.";
    }
}

$conn->close();
?>
