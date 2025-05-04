<?php
include 'template.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT UserID, Username, Password, UserType FROM Users WHERE Username = '$username' AND Password = '$password'";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['Username'];
        $_SESSION['usertype'] = $row['UserType'];

        // Redirect based on role
        switch ($row['UserType']) {
            case 'Admin':
                header("Location: ../pages/adminPage.html");
                break;
            case 'Employee':
                header("Location: ../pages/employee.html");
                break;
            default:
                header("Location: ../pages/client.html");
                break;
        }
        exit();
    }
    $conn->close();
}
?>