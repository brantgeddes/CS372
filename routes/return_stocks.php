<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($_SESSION['login']) {
  if ($method === 'GET') {
    
    $conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);

    $sql = "SELECT Stocks.symbol, Portfolio.quantity FROM Portfolio 
    INNER JOIN Stocks ON Stocks.id = Portfolio.stock_id
    WHERE Portfolio.user_id='" . $_SESSION['id'] . "';";
    
    $result = $conn->query($sql);
    $response = array();
    $i = 0;
    while(($i < 99) && $row = $result->fetch_assoc()){
      $response[] = array('symbol' => $row["symbol"], 'quantity' => $row["quantity"]);
      $i++;
    }

    echo json_encode($response);

    mysqli_close($conn);


  } else {

  }
}

?>