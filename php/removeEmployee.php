<?php
<<<<<<< HEAD
include 'db_config.php'; 
=======
include '../db_config.php'; 
>>>>>>> c89dadf571df91c8d34b43f13a0e3c2910ea6903

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeID = $_POST['employeeID'];

<<<<<<< HEAD
=======
    // Basic validation
>>>>>>> c89dadf571df91c8d34b43f13a0e3c2910ea6903
    if (is_numeric($employeeID)) {
        $stmt = $conn->prepare("DELETE FROM Employee WHERE EmployeeID = ?");
        $stmt->bind_param("i", $employeeID);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Employee removed successfully.";
            } else {
                echo "No employee found with that ID.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
<<<<<<< HEAD
        echo "Invalid Employee ID.";
=======
        echo "Please enter a valid Employee ID.";
>>>>>>> c89dadf571df91c8d34b43f13a0e3c2910ea6903
    }
}

$conn->close();
<<<<<<< HEAD
?>
=======
?>
>>>>>>> c89dadf571df91c8d34b43f13a0e3c2910ea6903
