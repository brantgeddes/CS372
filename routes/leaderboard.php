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
    /*
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
      $leaderboard_list[] = array('username' => $row["username"], 'symbol' => $row['symbol'], 'net' => $row["net"], 'balance' => $row['balance']);
    }
    
    $i = 0;
    $stock_list = array();
    while ($i < count($leaderboard_list)) {
      if (array_search($leaderboard_list[$i]['symbol'], $stock_list) === false) $stock_list[] = $leaderboard_list[$i]['symbol'];
      $i++;
    }
    
    $users = array();
    $sql = "SELECT username, balance FROM Users;";
    $result = $conn->query($sql);
    
    while ($row = $result->fetch_assoc()) {
      $users[] = array('username' => $row['username'], 'balance' => $row['balance']);  
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
        if ($users[$i]['username'] == $leaderboard_list[$j]["username"]) {
          $net += $leaderboard_list[$j]['net'];
        }
        $j++;
      }
      $net += $users[$i]['balance'];
      $response[] = array('username' => $users[$i]['username'], 'net' => $net);
      $i++;
      $j = 0;
    }
    
    usort($response, function ($item1, $item2) {
      return ($item2['net'] == $item1['net']) ? 0 : (($item2['net'] > $item1['net']) ? 1 : -1);
    });
    
    echo json_encode($response);

    mysqli_close($conn);
  */
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    $sql = "SELECT id FROM Users WHERE type='trader';";
    
    $results = $conn->query($sql);
    $user_list = array();
    
    while ($row = $results->fetch_assoc()){
      $user_list[] = $row['id'];
    }
    
    $user = new User();
    $market = new Market();
    
    $i = 0;
    $response = array();
    while ($i < count($user_list)) {
      $user->load($user_list[$i]);
      $response[] = array('username' => $user->get_username(), 'net' => $market->net_worth($user));
      $i++;
    }
    
    usort($response, function ($item1, $item2) {
      return ($item2['net'] == $item1['net']) ? 0 : (($item2['net'] > $item1['net']) ? 1 : -1);
    });
    
    echo json_encode($response);
    $conn->close();
    
  } else {

  }
}

?>