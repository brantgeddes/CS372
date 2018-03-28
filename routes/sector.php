<?php

	include 'includes.php';
	
	$conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);

	$sql = "select distinct sector from Stocks order by sector asc;";
	
    $results = $conn->query($sql);
	
	while ($row = $results->fetch_assoc()){
      $response[] = array('sector' => $row['sector']);
    }
	
	echo json_encode($response);
?>