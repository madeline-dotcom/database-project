<?php
session_start();
require_once 'template.php'; // <-- Make sure this is uncommented and points to a working connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userType'])) {

    $sql = "SELECT EmployeeID, Name FROM Employee";
    $stmt = $conn->prepare($sql);
    if ($stmt && $stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h1>Employee List</h1>";
            echo "<table border='1'>";
            echo "<tr><th>Employee ID</th><th>Name</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['EmployeeID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No employees found.";
        }
        $stmt->close();
    } else {
        echo "Error retrieving employees. Please try again.";
    }

    $conn->close();
} else {
    echo "Invalid request. Please provide an admin user type.";
}
?>
