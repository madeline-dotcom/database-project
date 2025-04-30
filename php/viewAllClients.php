<?php
session_start();
require_once 'template.php'; // Includes the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userType'])) {

    // Query to fetch all clients from Client table
    $sql = "SELECT ClientID, Name FROM Client";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Check if employees exist
        if ($result->num_rows > 0) {
            echo "<h1>Client List</h1>";
            echo "<table border='1'>";
            echo "<tr><th>Client ID</th><th>Name</th></tr>";

            // Fetch and display employees
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['ClientID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No clients found.";
        }
    } else {
        echo "Error retrieving clients. Please try again.";
    }
    $stmt->close();
} else {
    echo "Invalid request. Please provide an admin user type.";
}
$conn->close();
?>