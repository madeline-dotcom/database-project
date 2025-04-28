<?php
include 'db_config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clientID = $_POST['clientID'];

    if (is_numeric($clientID)) {
        $stmt = $conn->prepare("DELETE FROM Client WHERE ClientID = ?");
        $stmt->bind_param("i", $clientID);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Client removed successfully.";
            } else {
                echo "No client found with that ID.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid Client ID.";
    }
}

$conn->close();
?>
