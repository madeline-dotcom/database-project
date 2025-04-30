<?php
include 'db_config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the client ID from the submitted form data
    $clientID = $_POST['clientID'];

    // Validate that the input is a numeric value
    if (is_numeric($clientID)) {
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
