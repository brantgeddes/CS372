<?php
	include 'includes.php';

	$symbol = $_GET['symbol'];
	
	$conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);
	
	$sql = "SELECT enable FROM Stocks WHERE symbol = '" . $symbol . "';";
	
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	
	switch($row["enable"])
	{
		case 1:
			$sql = "update Stocks set enable = 0 where symbol = '" . $symbol . "';";
			$result = $conn->query($sql);
			$enable = 'false';
			echo json_encode(array('enable' => $enable));
			break;
		case 0:
			$sql = "update Stocks set enable = 1 where symbol = '" . $symbol . "';";
			$result = $conn->query($sql);
			$enable = 'true';
			echo json_encode(array('enable' => $enable));
			break;
	}
	
	mysqli_close($conn);
?>