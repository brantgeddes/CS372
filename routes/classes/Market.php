<?php

class Market {

  private $stock;
  private $transaction;
  private $user;
  
  public function __construct(User $user = null) {
    if ($user) $this->user = $user; else $user = new User();      
  }
  
  public function return_stocks(User $user = null) {
    
    if ($user) $this->user = $user;
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);

    $sql = "SELECT Stocks.symbol AS symbol, 
            SUM((quantity) * (CASE WHEN type = 'BUY' THEN 1 WHEN type = 'SELL' THEN -1 END)) AS quantity  
            FROM Transactions 
            INNER JOIN Stocks ON Transactions.stock_id = Stocks.id 
            WHERE user_id = " . $this->user->get_id() . " 
            GROUP BY symbol;";
    
    $result = $conn->query($sql);
    $response = array();
    $i = 0;
    while(($i < 99) && $row = $result->fetch_assoc()){
      if ($row['quantity'] > 0) $response[] = array('symbol' => $row["symbol"], 'quantity' => $row["quantity"]);
      $i++;
    }

    mysqli_close($conn);
    return $response;


    
  }
  
  public function find_stocks($symbol, $sector, $industry) {
    validate($symbol);
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
	if($symbol != 'undefined' and $sector == 'undefined' and $industry == 'undefined')
	{
	   $sql = "SELECT * FROM Stocks WHERE name LIKE '" . $symbol . "%';";
	}
	elseif($symbol != 'undefined' and $sector == 'null' and $industry == 'undefined')
	{
		$sql = "SELECT * FROM Stocks WHERE name LIKE '" . $symbol . "%';";
	}
	elseif($symbol != 'undefined' and $sector == 'null' and $industry == 'null')
	{
		$sql = "SELECT * FROM Stocks WHERE name LIKE '" . $symbol . "%';";
	}
	elseif($symbol != 'undefined' and $sector != 'undefined' and $industry == 'undefined')
	{
	   $sql = "SELECT * FROM Stocks WHERE name LIKE '" . $symbol . "%' and sector LIKE '" . $sector . "%';";
	}
	elseif($symbol != 'undefined' and $sector != 'undefined' and $industry != 'undefined')
	{
	   $sql = "SELECT * FROM Stocks WHERE name LIKE '" . $symbol . "%' and sector LIKE '" . $sector . "%' and industry LIKE '" . $industry . "%';";
	}
	elseif($symbol == 'undefined' and $sector != 'undefined' and $industry == 'undefined')
	{
	   $sql = "SELECT * FROM Stocks WHERE sector LIKE '" . $sector . "%';";
	}
	elseif($symbol == 'undefined' and $sector != 'undefined' and $industry != 'undefined')
	{
	   $sql = "SELECT * FROM Stocks WHERE sector LIKE '" . $sector . "%' and industry LIKE '" . $industry . "%';";
	}

    $result = $conn->query($sql);
    $response = array();
    $i = 0;
    while(($i < 99) && $row = $result->fetch_assoc()){
      switch($row["enable"])
      {
        case 1:
          $response[] = (object)array('symbol' => $row["symbol"], 'name' => $row["name"], 'enable' => 'true');
          break;
        case 0:
          if ($this->user->get_type() == 'admin')
          {
            $response[] = (object)array('symbol' => $row["symbol"], 'name' => $row["name"], 'enable' => 'false');
          }
          break;
      }
      $i++;
    }
	
    mysqli_close($conn);
    return $response;

  }
  
  public function net_worth(User $user = null) {
    
    if ($user) $this->user = $user;
    
    $stock_list = $this->return_stocks();
    $balance = $this->user->get_balance();
    
    $i = 0;
    $get_string = "";
    while ($i < count($stock_list)) {
      $get_string .= $stock_list[$i]['symbol'] . ',';
      $i++;
    }
    
    $curl = curl_init();
    $api_endpoint = 'https://api.iextrading.com/1.0/stock/market/batch?symbols=' . $get_string . '&types=quote';
    curl_setopt($curl, CURLOPT_URL, $api_endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    $result = json_decode($result);
    
    $i = 0;
    $net = 0;
    while ($i < count($stock_list)) {
      $net += $stock_list[$i]['quantity'] * $result->{$stock_list[$i]['symbol']}->{'quote'}->{'latestPrice'};
      $i++;
    }
    
    $net += $balance;
    
    return $net;
    
  }
 
  public function insert_transaction($symbol, $quantity, $type) {
    
    $this->stock = new Stock($symbol);
    $this->stock->load();
    $this->transaction = new Transaction($this->stock, $quantity, $type);
    if ($type == 'Buy') {
        return $this->transaction->buy();
      } elseif ($type == 'Sell') {
        return $this->transaction->sell();
      } else {
        return array('error' => "true", 'type' => 'transaction', 'message' => 'transaction type out of bounds');
      }
  }
  
  public function toggle($symbol) {
    $this->stock = new Stock();
    return $this->stock->toggle_stock($symbol);
  }
  
  public function return_transactions(User $user = null) {
    if ($user) $this->user = $user; else $this->user->load();
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);

    $sql = "select username,name,quantity,value,Transactions.type as type 
    from Transactions 
    inner join Users on Transactions.user_id=Users.id 
    inner join Stocks on Transactions.stock_id=Stocks.id 
    where Users.id = " . $this->user->get_id() . " 
    ORDER BY Transactions.id DESC;";
    
    $results = $conn->query($sql);
    while ($row = $results->fetch_assoc())
    {
      $response[] = array('stock' => $row['name'], 'quantity' => $row['quantity'], 'value' => $row['value'], 'type' => $row['type']);
    }
    
    $conn->close();
    return $response;
  }
  
}

?>