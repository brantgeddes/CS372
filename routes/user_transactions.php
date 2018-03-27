<?php

	include 'includes.php';

	$name = $_GET["name"];
	
	$app = new App();
	echo json_encode($app->user_transactions($name));

?>