<?php
include 'template.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clientID = $_POST['clientID'];
    $name = $_POST['name'];
    $location = $_POST['location'];

    // Validate input
    if (empty($clientID) || empty($name) || empty($location)) {
        die("Error: All fields are required.");
    }

    // Check if the client already exists
    $checkStmt = $conn->prepare("SELECT ClientID FROM Client WHERE ClientID = ?");
    if ($checkStmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $checkStmt->bind_param("i", $clientID);
    $checkStmt->execute();
    $checkStmt->store_result();
    if ($checkStmt->num_rows > 0) {
        die("Error: A client with Client ID $clientID already exists.");
    }
    $checkStmt->close();
    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO Client (ClientID, Name, Location) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    // Bind parameters
    $stmt->bind_param("iss", $clientID, $name, $location);
    // Execute the query
    if ($stmt->execute()) {
        echo "New client added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();

}

$conn->close();
?>
