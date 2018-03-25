<?php
	include 'includes.php';

	$symbol = $_GET['symbol'];
	
	$conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
	
	$app = new App();
	echo json_encode($app->toggle($symbol));
	
	mysqli_close($conn);
?>