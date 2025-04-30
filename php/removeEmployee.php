<?php
include 'template.php'; 

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted employee ID from the form
    $employeeID = $_POST['employeeID'];

    // Ensure the input is a valid numeric value before proceeding
    if (is_numeric($employeeID)) {
        //check if the employee is assigned to any tickets
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM Ticket WHERE EmployeeID = ?");
        // Bind the employee ID as an integer parameter
        $checkStmt->bind_param("i", $employeeID);
        // Execute the prepared statement
        if ($checkStmt->execute()) {
            // Fetch the result
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            // Close the statement
            $checkStmt->close();
            // If the employee is assigned to tickets, do not allow deletion
            if ($count > 0) {
                echo "Cannot remove employee. They are assigned to tickets.";
                exit;
            }
        } else {
            // Output error if execution fails
            echo "Error: " . $checkStmt->error;
            exit;
        }
        
        // Prepare a SQL DELETE statement to safely remove the employee
        $stmt = $conn->prepare("DELETE FROM Employee WHERE EmployeeID = ?");
        // Bind the employee ID as an integer parameter
        $stmt->bind_param("i", $employeeID);

        // Execute the prepared statement
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Employee removed successfully.";
            } else {
                echo "No employee found with that ID.";
            }
        } else {
            // Output error if execution fails
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement to free resources
        $stmt->close();
    } else {
        // Handle invalid input
        echo "Invalid Employee ID.";
    }
}

$conn->close();
?>
