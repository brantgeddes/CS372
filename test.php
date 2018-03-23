<?php

include "routes/includes.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
  $str_json = file_get_contents('php://input');
  $data = json_decode($str_json);
  
  echo json_encode(array("report" => $data->report));
} else {
  
  $test = new Stock('AAPL',0,0,1);
  $test->load();
  var_dump($test);
  
}
?>