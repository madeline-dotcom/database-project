<?php
$name = $_POST['name'];

//connection
$conn = new mysqli('localhost','root','pswd','DB name');

if ($conn->connect_error){
	die('Connection Failed : '.$conn->connect_error);
}
$stmt = $conn->prepare("INSERT INTO name (name) VALUES (?)");

// Check if preparation was successful
if ($stmt) {
    // Bind the parameter to the statement
    $stmt->bind_param("s", $name);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Execution failed: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Preparation failed: " . $conn->error;
}

// Close the database connection
$conn->close();

?>