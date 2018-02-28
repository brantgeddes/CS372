<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
  
  $str_json = file_get_contents('php://input');
  $data = json_decode($str_json);
  
  echo "Buy stock: " . json_encode($data);
  
} else {
  
}

?>