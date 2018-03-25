<?php

include "includes.php";

if ($_SESSION['login'] and ($_SESSION['type'] == "admin")) {
	
	$app = new App();
	echo $app->import_stocks();
	
} else {
	echo "Not logged in";
}
?>