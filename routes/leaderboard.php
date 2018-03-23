<?php

/*

SELECT Users.username AS username, 
SUM((quantity) * (value) * (CASE WHEN Transactions.type = 'BUY' THEN 1 WHEN Transactions.type = 'SELL' THEN -1 END)) AS net 
FROM Transactions 
INNER JOIN Stocks ON Transactions.stock_id = Stocks.id 
INNER JOIN Users ON Transactions.user_id = Users.id 
GROUP BY username 
ORDER BY net DESC;

*/
include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($_SESSION['login']) {
  if ($method === 'GET') {
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);

    $sql = "SELECT Users.username AS username, 
            SUM((quantity) * (value) * (CASE WHEN Transactions.type = 'BUY' THEN 1 WHEN Transactions.type = 'SELL' THEN -1 END)) AS net 
            FROM Transactions 
            INNER JOIN Stocks ON Transactions.stock_id = Stocks.id 
            INNER JOIN Users ON Transactions.user_id = Users.id 
            GROUP BY username 
            ORDER BY net DESC;";
    
    $result = $conn->query($sql);
    $response = array();
    $i = 0;
    while(($i < 99) && $row = $result->fetch_assoc()){
      $response[] = array('username' => $row["username"], 'net' => $row["net"]);
      $i++;
    }

    echo json_encode($response);

    mysqli_close($conn);


  } else {

  }
}

?>