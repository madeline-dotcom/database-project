<?php
include 'template.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the client ID from the submitted form data
    $clientID = $_POST['clientID'];

    // Validate that the input is a numeric value
    if (is_numeric($clientID)) {
        //check if the client is assigned to any tickets or devices
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM Ticket WHERE ClientID = ? OR SerialNum IN (SELECT SerialNum FROM Device WHERE ClientID = ?)");
        // Bind the client ID as an integer parameter
        $checkStmt->bind_param("ii", $clientID, $clientID);
        // Execute the prepared statement
        if ($checkStmt->execute()) {
            // Fetch the result
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            // Close the statement
            $checkStmt->close();
            // If the client is assigned to tickets or devices, do not allow deletion
            if ($count > 0) {
                echo "Cannot remove client. They are assigned to tickets or devices.";
                exit;
            }
        } else {
            // Output an error message if the SQL execution fails
            echo "Error: " . $checkStmt->error;
            exit;
        }
        // Prepare a SQL DELETE statement to remove the client with the given ID
        $stmt = $conn->prepare("DELETE FROM Client WHERE ClientID = ?");
        $stmt->bind_param("i", $clientID);

        // Execute the statement and check if it was successful
        if ($stmt->execute()) {
            // check if any rows affected
            if ($stmt->affected_rows > 0) {
                echo "Client removed successfully.";
            } else {
                echo "No client found with that ID.";
            }
        } else {
            // Output an error message if the SQL execution fails
            echo "Error: " . $stmt->error;
        }

        // close the prepared statement
        $stmt->close();
    } else {
        // Inform the user if the input was not a valid number
        echo "Invalid Client ID.";
    }
}

$conn->close();
?>
