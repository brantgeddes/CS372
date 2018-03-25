<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
  $app = new App();
  echo json_encode($app->get_reports());
} elseif ($method === 'POST') {
  $data = json_decode(file_get_contents("php://input"));
  $app = new App();
  echo json_encode($app->mark_report($data->id));
} else {
  
}
?>