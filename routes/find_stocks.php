<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
  
  /*
  $test_stocks = array( //Used to test http response, delete when real stocks are being pulled
    (object)array('ticker' => 'AAPL', 'name' => 'Apple Inc.', 'value' => 178.39, 'change' => -0.58, 'pchange' => -0.32), 
    (object)array('ticker' => 'AMZN', 'name' => 'Amazon.com Inc.', 'value' => 1511.98, 'change' => -9.97, 'pchange' => -0.66), 
    (object)array('ticker' => 'MSFT', 'name' => 'Microsoft Corp.', 'value' => 94.20, 'change' => -1.22, 'pchange' => -1.28));
  */
  
  $query_string = array();
  parse_str($_SERVER['QUERY_STRING'], $query_string);
  //$stock = $query_string['ticker'];
  $stock_info = get_stock_data($query_string['ticker']);
  
  $s_ticker = $stock_info['quote']['symbol'];
  $s_name = $stock_info['quote']['companyName'];
  $s_close = (int)$stock_info['quote']['close'];
  $s_open = $stock_info['quote']['open'];
  $s_change = $s_close - $s_open;
  $s_pchange = 100 * ($s_change/$s_open);
  
  $stock = array('ticker' => $s_ticker, 'name' => $s_name, 'value' => $s_close, 'change' => $s_change, 'pchange' => $s_pchange);
  echo json_encode($stock);
  
  
} else {
    
}

?>