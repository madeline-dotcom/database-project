<?php
include 'template.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serialNum = $_POST['serial'];

    // Validate input
    if (empty($serialNum)) {
        die("Error: Serial Number is required.");
    }

    // Prepare the SQL query to delete the device
    $stmt = $conn->prepare("DELETE FROM Device WHERE SerialNum = ?");
    if ($stmt === false) {
        die("Error: Failed to prepare the SQL statement. " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("i", $serialNum);

    // Execute the query
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Device with Serial Number $serialNum has been successfully removed.";
        } else {
            echo "No device found with Serial Number $serialNum.";
        }
    } else {
        echo "Error: Failed to execute the SQL statement. " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
