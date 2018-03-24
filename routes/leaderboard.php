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
            Stocks.symbol AS symbol,
            SUM((quantity) * (CASE WHEN Transactions.type = 'BUY' THEN 1 WHEN Transactions.type = 'SELL' THEN -1 END)) AS net 
            FROM Transactions 
            INNER JOIN Stocks ON Transactions.stock_id = Stocks.id 
            INNER JOIN Users ON Transactions.user_id = Users.id 
            GROUP BY username, symbol 
            ORDER BY net DESC;";
    
    $result = $conn->query($sql);
    $leaderboard_list = array();
    while($row = $result->fetch_assoc()){
      $leaderboard_list[] = array('username' => $row["username"], 'symbol' => $row['symbol'], 'net' => $row["net"]);
    }
    
    $i = 0;
    $stock_list = array();
    $users = array();
    while ($i < count($leaderboard_list)) {
      if (array_search($leaderboard_list[$i]['symbol'], $stock_list) === false) $stock_list[] = $leaderboard_list[$i]['symbol'];
      if (array_search($leaderboard_list[$i]['username'], $users) === false) $users[] = $leaderboard_list[$i]['username'];
      $i++;
    }
    
    $i = 0;
    $get_string = "";
    while ($i < count($stock_list)) {
      $get_string .= $stock_list[$i] . ',';
      $i++;
    }
    
    $curl = curl_init();
    $api_endpoint = 'https://api.iextrading.com/1.0/stock/market/batch?symbols=' . $get_string . '&types=quote';
    curl_setopt($curl, CURLOPT_URL, $api_endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    $result = json_decode($result);
    $i = 0;
    while ($i < count($leaderboard_list)) {
      $leaderboard_list[$i]['net'] *= $result->{$leaderboard_list[$i]['symbol']}->{'quote'}->{'latestPrice'};
      $i++;
    }
    
    $i = 0;
    $j = 0;
    $response = array();
    while ($i < count($users)) {
      $net = 0;
      while ($j < count($leaderboard_list)) {
        if ($users[$i] == $leaderboard_list[$j]["username"]) {
          $net += $leaderboard_list[$j]['net'];
        }
        $j++;
      }
      $response[] = array('username' => $users[$i], 'net' => $net);
      $i++;
      $j = 0;
    }
   
    echo json_encode($response);

    mysqli_close($conn);


  } else {

  }
}

?>