<?php
include 'template.php';

session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve input
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT UserID, Username, Password, UserType FROM Users WHERE Username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();

        // Fetch result
        $result = $stmt->get_result();
        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Verify password
            if ($password === $row['Password']) {
                $_SESSION['username'] = $row['Username'];
                $_SESSION['usertype'] = $row['UserType'];

                // Redirect based on user type
                switch ($row['UserType']) {
                    case 'Admin':
                        header("Location: ../html/adminPage.html");
                        break;
                    case 'Employee':
                        header("Location: ../html/employee.html");
                        break;
                    default:
                        header("Location: ../html/client.html");
                        break;
                }
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No such user found.";
        }
        $stmt->close();
    } else {
        echo "Database query failed.";
    }
    $conn->close();
}
?>
