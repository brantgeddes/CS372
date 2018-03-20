<?php

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
  echo json_encode(array("enable" => "false"));
} else {
  
  $str_json = file_get_contents('php://input');
  $data = json_decode($str_json);
  
  echo json_encode(array("report" => $data->report));
}
?>