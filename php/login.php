<?php
// Path to User.dat
$userFile = "../data/Users.dat"; // Make sure this path is correct

// Get form input
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

// Read file lines
$lines = file($userFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$authenticated = false;

foreach ($lines as $line) {
    // Split and trim each field
    list($userID, $fileUsername, $filePassword, $userType) = array_map('trim', explode(',', $line));

    if ($username === $fileUsername && $password === $filePassword) {
        $authenticated = true;

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

        exit(); // Stop after redirect
    }
}

// If login fails
if (!$authenticated) {
    echo "<h2>Invalid username or password</h2>";
    echo "<a href='../html/login.html'>Try again</a>";
}
?>
