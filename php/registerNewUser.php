<?php
include 'db_config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // sanitize and retrieve input values
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];

    // validate all required fields are provided
    if (!empty($username) && !empty($password) && !empty($usertype)) {
        // use prepared statement to insert user into database
        $stmt = $conn->prepare("INSERT INTO Users (Username, Password, UserType) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $username, $password, $usertype);
            // execute the prepared statement and check 
            if ($stmt->execute()) {
                // Redirect to login page upon successful registration
                header("Location: ../html/Login.html");
                exit();
            } else {
                echo "Error inserting user: " . $stmt->error;
            }
            // close prepared statement 
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
