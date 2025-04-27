<?php
$ticketNum = $_POST['ticketNum'];
$employeeID = $_POST['employeeID'];
$deviceType = $_POST['deviceType'];
$serialNum = $_POST['serialNum'];
$clientID = $_POST['clientID'];


//connection
$conn = new mysqli('localhost','root','pswd','DB name');
if ($conn->connect_error){
	die('Connection Failed : '.$conn->connect_error);
}
else{
	$stmt = $conn->prepare("insert into Ticket(ticketNum, employeeID, deviceType, serialNum, clientID)
		values(?????)")
	$stmt-> bind_param("iissi",$ticketNum,$employeeID,$deviceType,$serialNum,$clientID);
	$stmt->execute();
	echo "success";
	$stmt->close();
	$conn->close();
}

?>