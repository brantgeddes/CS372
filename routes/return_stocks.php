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
    
    $app = new App();
    echo json_encode($app->return_stocks());
    
  } else {

  }
}

?>