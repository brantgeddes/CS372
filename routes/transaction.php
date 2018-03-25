<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($_SESSION['login']) {
  if ($method === 'POST') {
    
    $str_json = file_get_contents('php://input');
    $data = json_decode($str_json);
    
    $app = new App();
    echo json_encode($app->transaction($data->symbol, $data->quantity, $data->type));
    
  }
}
 
?>