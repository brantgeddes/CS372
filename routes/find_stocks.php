<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
  /*
  
  $query_string = array();
  parse_str($_SERVER['QUERY_STRING'], $query_string);
  //$stock = $query_string['ticker'];
  $stock_info = get_stock_data($query_string['ticker']);
  
  $s_ticker = $stock_info{'symbol'};
  $s_name = $stock_info{'companyName'};
  $s_close = (int)$stock_info{'close'};
  $s_open = $stock_info{'open'};
  $s_change = $s_close - $s_open;
  $s_pchange = 100 * ($s_change/$s_open);
 
  $stock = array('ticker' => $s_ticker, 'name' => $s_name, 'value' => $s_close, 'change' => $s_change, 'pchange' => $s_pchange);
  echo json_encode($stock);
  
  */
  
  parse_str($_SERVER['QUERY_STRING'], $query_string);
  $symbol = $query_string['name'];
  validate($symbol);
  $conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);
  
  $sql = "SELECT * FROM Stocks WHERE name LIKE '" . $symbol . "%';";
  
  $result = $conn->query($sql);
  $response = array();
  $i = 0;
  while(($i < 99) && $row = $result->fetch_assoc()){
    $response[] = (object)array('symbol' => $row["symbol"], 'name' => $row["name"]);
    $i++;
  }
  
  echo json_encode($response);
  
  mysqli_close($conn);

  
} else {
    
}

?>