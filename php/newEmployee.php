<?php
include 'template.php';

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $employeeID = isset($_POST['employeeID']) ? trim($_POST['employeeID']) : null;

    if (empty($name) || empty($employeeID)) {
        $error = "Error: All fields are required.";
    } elseif (!is_numeric($employeeID)) {
        $error = "Error: Employee ID must be a valid number.";
    } else {
        $checkStmt = $conn->prepare("SELECT EmployeeID FROM Employee WHERE EmployeeID = ?");
        if ($checkStmt === false) {
            $error = "Error: Failed to prepare the SQL statement. " . $conn->error;
        } else {
            $checkStmt->bind_param("i", $employeeID);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                $error = "Error: An employee with Employee ID $employeeID already exists.";
            } else {
                $stmt = $conn->prepare("INSERT INTO Employee (EmployeeID, Name) VALUES (?, ?)");
                if ($stmt === false) {
                    $error = "Error: Failed to prepare the SQL statement. " . $conn->error;
                } else {
                    $stmt->bind_param("is", $employeeID, $name);
                    if ($stmt->execute()) {
                        $message = "New employee added successfully!";
                    } else {
                        $error = "Error: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
            $checkStmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee Result</title>
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

    <a class="back-button" href="../html/EmployeeMng.html">← Back to Employee Management</a>
</div>

</body>
</html>
