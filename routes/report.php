<?php

date_default_timezone_set('America/Regina');

include 'includes.php';

$data = json_decode(file_get_contents("php://input"));

$conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);

$sql = "Select id from Users where username ='".$_SESSION['username']."'";
$result = $conn->query($sql);
$row=$result->fetch_assoc();

$sql = "Insert into ReportBug (description,submitted,status,userID) values ('".$data->report."','".date("Y/m/d H:i:s")."',1,'".$row['id']."')";
$result = $conn->query($sql);

echo $result;
?>