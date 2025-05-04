<?php
include 'template.php';
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: 0");

// Get form input
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validate input
if (empty($username) || empty($password)) {
    echo "<h2>Username and password are required</h2>";
    echo "<a href='../pages/login.html'>Try again</a>";
    exit();
}

// Query for user
$stmt = $conn->prepare("SELECT * FROM Users WHERE Username = ? AND Password = ?");
if ($stmt) {
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userType = $row['UserType'];

        // Set session variables
        $_SESSION['usertype'] = $userType;
        $_SESSION['username'] = $username;


        // Redirect by user type
        switch (strtolower($userType)) {
            case 'admin':
                header("Location: ../pages/adminPage.php");
                break;
            case 'client':
                header("Location: ../pages/client.php");
                break;
            case 'employee':
                header("Location: ../pages/employee.php");
                break;
            default:
                echo "<h2>Unknown user type: $userType</h2>";
                exit();
        }
    } else {
        echo "<h2>Invalid username or password</h2>";
        echo "<a href='../pages/login.php'>Try again</a>";
    }
    $stmt->close();
} else {
    echo "Database prepare failed: " . $conn->error;
}

$conn->close();
?>
