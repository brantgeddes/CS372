<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($_SESSION['login']) {
  if ($method === 'GET') {

    parse_str($_SERVER['QUERY_STRING'], $query_string);
    $symbol = $query_string['name'];
		
		$app = new App();
		echo json_encode($app->find_stocks($symbol));		

  } else {

  }
}

?>