<?php
include 'db_config.php';

<<<<<<< HEAD
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];

    if (!empty($username) && !empty($password) && !empty($usertype)) {
        $stmt = $conn->prepare("INSERT INTO Users (Username, Password, UserType) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $username, $password, $usertype);
            if ($stmt->execute()) {
                // Redirect to login page after successful registration
                header("Location: ../html/Login.html");
                exit();
            } else {
                echo "Error inserting user: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Database prepare failed: " . $conn->error;
        }
    } else {
        echo "All fields are required.";
    }
}
$conn->close();
?>
=======
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    // Later: $password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO Users (Username, Password, UserType) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $user_type);

    if ($stmt->execute()) {
        echo "New user registered successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
>>>>>>> c89dadf571df91c8d34b43f13a0e3c2910ea6903
