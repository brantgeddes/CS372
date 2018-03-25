<?php

include "routes/includes.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
  $str_json = file_get_contents('php://input');
  $data = json_decode($str_json);
  
  echo json_encode(array("report" => $data->report));
} else {
  
  $app = new App();
  echo json_encode($app->mark_report(1));  
 
}
?>