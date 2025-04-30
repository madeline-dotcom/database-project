<?php
include 'template.php';
session_start();

// Get form input
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validate input
if (empty($username) || empty($password)) {
    echo "<h2>Username and password are required</h2>";
    echo "<a href='../html/login.html'>Try again</a>";
    exit();
}
//Perform a select query on users to see if a user with the username and password exists
$stmt = $conn->prepare("SELECT * FROM Users WHERE Username = ? AND Password = ?");
if ($stmt) {
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // User exists
        $row = $result->fetch_assoc();
        $userType = $row['UserType'];
        // Redirect based on UserType
        switch (strtolower($userType)) {
            case 'admin':
                header("Location: http://localhost:8000/html/adminPage.html");
                break;
            case 'client':
                header("Location: http://localhost:8000/html/client.html");
                break;
            case 'employee':
                header("Location: http://localhost:8000/html/employee.html");
                break;
            default:
                echo "<h2>Unknown user type: $userType</h2>";
                exit();
        }
    } else {
        echo "<h2>Invalid username or password</h2>";
        echo "<a href='../html/login.html'>Try again</a>";
    }
    $stmt->close();
} else {
    echo "Database prepare failed: " . $conn->error;
}
$conn->close();
?>
