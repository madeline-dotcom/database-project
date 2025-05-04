<?php
session_start();
require_once 'template.php'; // Includes the database connection

?>
<!DOCTYPE html>
<html>
<head>
    <title>View All Clients</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #dde4ff;
        }

        .top-bar {
            background-color: #fdf1dc;
            display: flex;
            align-items: center;
            padding: 20px 40px;
        }

        .logo {
            width: 40px;
            margin-right: 20px;
        }

        .company-name {
            font-size: 26px;
            font-weight: bold;
            color: #000;
        }

        .user-info {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info span {
            font-size: 18px;
            color: #000;
        }

        .card {
            background-color: white;
            border: 1px solid #000;
            width: 600px;
            padding: 40px;
            margin: 80px auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .card h1 {
            text-align: center;
            color: #000;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #000;
            text-align: left;
        }

        th {
            background-color: #c9abd1;
        }

        .back-button {
            padding: 8px 16px;
            background-color: #a4d3f4;
            color: #000;
            border: 1px solid #000;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #90c0e0;
        }

        .logout-button {
            position: fixed;
            right: 40px;
            bottom: 40px;
            padding: 10px 20px;
            background-color: #dde4ff;
            color: #000;
            border: 1px solid #000;
            font-weight: bold;
            cursor: pointer;
        }

        .logout-button:hover {
            background-color: #ccc;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <img src="../images/ant.png" alt="Logo" class="logo">
    <div class="company-name">ANT IT Company</div>
    <div class="user-info">
      <span>Client List</span>
      <button class="back-button" onclick="document.location='../html/ClientMng.html'">Back</button>
    </div>
</div>

<div class="card">
    <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userType'])) {

    // Query to fetch all clients from Client table
    $sql = "SELECT ClientID, Name, Location FROM Client";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Check if employees exist
        if ($result->num_rows > 0) {
            echo "<h1>Client List</h1>";
            echo "<table border='1'>";
            echo "<tr><th>Client ID</th><th>Name</th><th>Location</th></tr>";

            // Fetch and display employees
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['ClientID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Location']) . "</td>";
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
</div>

<button class="logout-button" onclick="location.href='Login.html'">LOGOUT</button>

</body>
</html>