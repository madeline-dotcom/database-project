<?php
session_start();
include 'template.php';// Includes the database connection

echo "<!DOCTYPE html>
<html>
<head>
    <title>My Tickets</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color:rgb(133, 184, 235); /* Blue background */
            color: white;
            text-align: center;
        }
        h1 {
            margin-top: 40px;
        }
        table {
            margin: 40px auto;
            background-color: white;
            color: black;
            border-collapse: collapse;
            width: 60%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        th, td {
            padding: 12px 20px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #003366;
            color: white;
        }
    </style>
</head>
<body>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employeeID'])) {
    $employeeID = $_POST['employeeID'];

    // Query to fetch tickets for the given employee ID
    $sql = "SELECT TicketNum, DeviceType, Status FROM Ticket WHERE EmployeeID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $employeeID);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Check if tickets exist
        if ($result->num_rows > 0) {
            echo "<h1>Tickets for Employee ID: $employeeID</h1>";
            echo "<table border='1'>";
            echo "<tr><th>Ticket Number</th><th>Device Type</th><th>Status</th></tr>";

            // Fetch and display tickets
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['TicketNum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['DeviceType']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Status']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No tickets found for Employee ID: $employeeID.";
        }
    } else {
        echo "Error retrieving tickets. Please try again.";
    }

    $stmt->close();
} else {
    echo "Invalid request. Please provide an employee ID.";
}

$conn->close();
?>