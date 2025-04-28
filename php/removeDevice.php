<?php
include 'db_config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serialNum = $_POST['serialNum'];

    if (!empty($serialNum)) {
        $stmt = $conn->prepare("DELETE FROM Device WHERE SerialNum = ?");
        $stmt->bind_param("s", $serialNum);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Device removed successfully.";
            } else {
                echo "No device found with that Serial Number.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Please provide a valid Serial Number.";
    }
}

$conn->close();
?>
