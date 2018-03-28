<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($_SESSION['login']) {
  if ($method === 'GET') {

    parse_str($_SERVER['QUERY_STRING'], $query_string);
    $symbol = $query_string['name'];
	$sector = $query_string['sector'];
	$industry = $query_string['industry'];
		
		$app = new App();
		echo json_encode($app->find_stocks($symbol, $sector, $industry));		

  } else {

  }
}

?>