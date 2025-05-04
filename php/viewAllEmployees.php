<?php
session_start();
require_once 'template.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>View All Employees</title>
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
      <span>Employee List</span>
      <button class="back-button" onclick="document.location='../pages/EmployeeMng.php'">Back</button>
    </div>
</div>

<div class="card">
    <h1>Employee List</h1>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userType'])) {
        $sql = "SELECT EmployeeID, Name FROM Employee";
        $stmt = $conn->prepare($sql);
        if ($stmt && $stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Employee ID</th><th>Name</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['EmployeeID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No employees found.</p>";
            }
            $stmt->close();
        } else {
            echo "<p>Error retrieving employees. Please try again.</p>";
        }
        $conn->close();
    } else {
        echo "<p>Invalid request. Please provide an admin user type.</p>";
    }
    ?>
</div>

</body>
</html>
