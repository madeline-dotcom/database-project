<?php
include 'db_config.php';

session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT UserID, Username, Password, UserType FROM Users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();x

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        if ($password === $row['Password']) {
            $_SESSION['username'] = $row['Username'];
            $_SESSION['usertype'] = $row['UserType'];

            echo "Login successful!<br>";

            if ($row['UserType'] == 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($row['UserType'] == 'employee') {
                header("Location: employee_dashboard.php");
            } else {
                header("Location: client_dashboard.php");
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No such user found.";
    }

    $stmt->close();
}

$conn->close();
?>
