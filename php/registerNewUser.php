<?php
include 'template.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // sanitize and retrieve input values
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];
    $UserID = $_POST['UserID'];

    // validate all required fields are provided
    if (!empty($username) && !empty($password) && !empty($usertype) && !empty($UserID)) {
        //if userid already exists in users table, return error
        $stmt = $conn->prepare("SELECT UserID FROM Users WHERE UserID = ?");
        if ($stmt) {
            $stmt->bind_param("i", $UserID);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                echo "User ID already exists. Please choose a different User ID.";
                exit();
            }
            $stmt->close();
        } else {
            echo "Database prepare failed: " . $conn->error;
        }
        //if usertype is client, check if userid is equal to clientid
        if ($usertype == 'Client') {
            $stmt = $conn->prepare("SELECT ClientID FROM Client WHERE ClientID = ?");
            if ($stmt) {
                $stmt->bind_param("i", $UserID);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows == 0) {
                    echo "Client ID does not exist. You must add the client first.";
                    exit();
                }
                $stmt->close();
            } else {
                echo "Database prepare failed: " . $conn->error;
            }
        }
        //if usertype is employee, check if userid is equal to employeeid
        if ($usertype == 'Employee') {
            $stmt = $conn->prepare("SELECT EmployeeID FROM Employee WHERE EmployeeID = ?");
            if ($stmt) {
                $stmt->bind_param("i", $UserID);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows == 0) {
                    echo "Employee ID does not exist. You must add the employee first.";
                    exit();
                }
                $stmt->close();
            } else {
                echo "Database prepare failed: " . $conn->error;
            }
        }
        
        // use prepared statement to insert user into database
        $stmt = $conn->prepare("INSERT INTO Users (Username, Password, UserType, UserID) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssi", $username, $password, $usertype, $UserID);
            // execute the prepared statement and check 
            if ($stmt->execute()) {
                // Redirect to login page upon successful registration
                echo "User registered successfully.";
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
