<?php

	include 'includes.php';
	
	$sector = $_GET['sector'];

	
	$conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);

	$sql = "select distinct industry from Stocks where sector = '".$sector."' order by industry asc;";
	
    $results = $conn->query($sql);
	
	while ($row = $results->fetch_assoc()){
      $response[] = array('industry' => $row['industry']);
    }
	
	echo json_encode($response);
?>