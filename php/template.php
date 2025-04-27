<?php
$name = $_POST['name'];

//connection
$conn = new mysqli('localhost','root','pswd','DB name');
if ($conn->connect_error){
	die('Connection Failed : '.$conn->connect_error);
}
else{
	$stmt = $conn->prepare("insert into name(name)
		values(?)")
	$stmt-> bind_param("s",$name);
	$stmt->execute();
	echo "success";
	$stmt->close();
	$conn->close();
}

?>