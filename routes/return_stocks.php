<?php

/*

SELECT Stocks.symbol, 
SUM((quantity) * (CASE WHEN type = "BUY" THEN 1 WHEN type = "SELL" THEN -1 END)) AS quantity,  
FROM Transactions 
INNER JOIN Stocks ON Transactions.stock_id = Stocks.id 
WHERE user_id = 2 
GROUP BY symbol;

*/
include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($_SESSION['login']) {
  if ($method === 'GET') {
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);

    $sql = "SELECT Stocks.symbol AS symbol, 
            SUM((quantity) * (CASE WHEN type = 'BUY' THEN 1 WHEN type = 'SELL' THEN -1 END)) AS quantity  
            FROM Transactions 
            INNER JOIN Stocks ON Transactions.stock_id = Stocks.id 
            WHERE user_id = " . $_SESSION['id'] . " 
            GROUP BY symbol;";
    
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