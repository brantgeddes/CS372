<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
 
  $str_json = file_get_contents('php://input');
  $data = json_decode($str_json);
  
  $user = new User($data->email, $data->password, $data->username);
  
  echo json_encode($user->signup());
  
}

?>