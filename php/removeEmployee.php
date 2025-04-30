<?php
include 'db_config.php'; 

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted employee ID from the form
    $employeeID = $_POST['employeeID'];

    // Ensure the input is a valid numeric value before proceeding
    if (is_numeric($employeeID)) {
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
