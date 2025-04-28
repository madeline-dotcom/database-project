<?php
include 'db_config.php'; // Connect to database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clientID = $_POST['clientID'];
    $locationName = $_POST['locationName'];

    // Basic validation
    if (is_numeric($clientID) && !empty($locationName)) {
        $stmt = $conn->prepare("INSERT INTO Client (ClientID, LocationName) VALUES (?, ?)");
        $stmt->bind_param("is", $clientID, $locationName);

        if ($stmt->execute()) {
            echo "New client added successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid input. Please enter valid Client ID and Location Name.";
    }
}

$conn->close();
?>
