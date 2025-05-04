<?php
include 'template.php';

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeID = $_POST['employeeID'];

    if (is_numeric($employeeID)) {
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM Ticket WHERE EmployeeID = ?");
        $checkStmt->bind_param("i", $employeeID);
        if ($checkStmt->execute()) {
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            $checkStmt->close();
            if ($count > 0) {
                $error = "Cannot remove employee. They are assigned to tickets.";
            } else {
                $stmt = $conn->prepare("DELETE FROM Employee WHERE EmployeeID = ?");
                $stmt->bind_param("i", $employeeID);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $message = "Employee removed successfully.";
                    } else {
                        $error = "No employee found with that ID.";
                    }
                } else {
                    $error = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            $error = "Error: " . $checkStmt->error;
        }
    } else {
        $error = "Invalid Employee ID.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Remove Employee Result</title>
    <style>
        body {
            margin: 0;
            background-color: #dde4ff;
            font-family: Arial, sans-serif;
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

        .content {
            margin: 80px auto;
            width: 50%;
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .message {
            font-size: 20px;
            color: green;
            font-weight: bold;
        }

        .error {
            font-size: 20px;
            color: red;
            font-weight: bold;
        }

        .back-button {
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #a4d3f4;
            color: #000;
            font-weight: bold;
            border: 1px solid #000;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .back-button:hover {
            background-color: #90c0e0;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <img src="../images/ant.png" alt="Logo" class="logo">
    <div class="company-name">ANT IT Company</div>
</div>

<div class="content">
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php elseif ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <a class="back-button" href="../pages/EmployeeMng.php">← Back to Employee Management</a>
</div>

<script>
  // Reload page if restored from back/forward cache (after logout)
  window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
      window.location.reload();
    }
  });
</script>
</body>
</html>
