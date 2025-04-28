<?php
include 'db_config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serialNum = $_POST['serialNum'];
    $clientID = $_POST['clientID'];
    $lastWorkedOn = !empty($_POST['lastWorkedOn']) ? $_POST['lastWorkedOn'] : NULL;
    $purchasedDate = !empty($_POST['purchasedDate']) ? $_POST['purchasedDate'] : NULL;
    $ticketNum = !empty($_POST['ticketNum']) ? $_POST['ticketNum'] : NULL;

    if (!empty($serialNum) && is_numeric($clientID)) {
        $stmt = $conn->prepare("INSERT INTO Device (SerialNum, ClientID, LastWorkedOn, PurchasedDate, TicketNum) 
                                VALUES (?, ?, ?, ?, ?)");

        $stmt->bind_param("sissi", $serialNum, $clientID, $lastWorkedOn, $purchasedDate, $ticketNum);

        if ($stmt->execute()) {
            echo "New device added successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Please fill in Serial Number and a valid Client ID.";
    }
}

$conn->close();
?>
