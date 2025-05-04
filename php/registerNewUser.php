<?php
include 'template.php';
session_start();

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];
    $UserID = $_POST['UserID'];

    if (!empty($username) && !empty($password) && !empty($usertype) && !empty($UserID)) {
        // Check if UserID already exists
        $stmt = $conn->prepare("SELECT UserID FROM Users WHERE UserID = ?");
        if ($stmt) {
            $stmt->bind_param("i", $UserID);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error = "User ID already exists. Please choose a different User ID.";
            }
            $stmt->close();
        } else {
            $error = "Database error: " . $conn->error;
        }

        // Client or Employee existence check
        if (empty($error)) {
            if ($usertype == 'Client') {
                $stmt = $conn->prepare("SELECT ClientID FROM Client WHERE ClientID = ?");
            } elseif ($usertype == 'Employee') {
                $stmt = $conn->prepare("SELECT EmployeeID FROM Employee WHERE EmployeeID = ?");
            }

            if ($stmt) {
                $stmt->bind_param("i", $UserID);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows == 0) {
                    $error = "$usertype ID does not exist. You must add the $usertype first.";
                }
                $stmt->close();
            } else {
                $error = "Database error: " . $conn->error;
            }
        }

        // Insert new user
        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO Users (Username, Password, UserType, UserID) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sssi", $username, $password, $usertype, $UserID);
                if ($stmt->execute()) {
                    $message = "User registered successfully.";
                } else {
                    $error = "Error inserting user: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error = "Database error: " . $conn->error;
            }
        }
    } else {
        $error = "All fields are required.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
        body {
            background-color: #dde4ff;
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            border: 1px solid #000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #000;
        }

        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #c9abd1;
            color: #000;
            border: 1px solid #000;
            font-weight: bold;
            border-radius: 6px;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #b89bc3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Register User</h1>

    <?php if (!empty($message)): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php elseif (!empty($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <a class="back-button" href="../html/registerNewUser.html">← Back to Registration Form</a>
</div>

</body>
</html>
