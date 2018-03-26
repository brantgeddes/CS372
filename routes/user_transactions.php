<?php
	include 'includes.php';
	$name = $_GET["name"];
	#$name = 'uppal2665';
	$conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    $sql = "select username,name,quantity,value,Transactions.type as type from Transactions inner join Users on Transactions.user_id=Users.id inner join Stocks on Transactions.stock_id=Stocks.id where username = '" . $name . "';";
	$results=$conn->query($sql);
	while ($row = $results->fetch_assoc())
	{
		$response[] = array('stock' => $row['name'], 'quantity' => $row['quantity'], 'value' => $row['value'], 'type' => $row['type']);
	}
	echo json_encode($response);
?>