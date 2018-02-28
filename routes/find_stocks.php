<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
  
  $test_stocks = array( //Used to test http response, delete when real stocks are being pulled
    (object)array('ticker' => 'AAPL', 'name' => 'Apple Inc.', 'value' => 178.39, 'change' => -0.58, 'pchange' => -0.32), 
    (object)array('ticker' => 'AMZN', 'name' => 'Amazon.com Inc.', 'value' => 1511.98, 'change' => -9.97, 'pchange' => -0.66), 
    (object)array('ticker' => 'MSFT', 'name' => 'Microsoft Corp.', 'value' => 94.20, 'change' => -1.22, 'pchange' => -1.28));
  
  echo json_encode($test_stocks);
  
} else {
    
}

?>