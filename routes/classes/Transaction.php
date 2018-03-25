<?php

class Transaction {
  
  private $stock;
  private $user;
  private $quantity;
  private $type;
  private $value;
  
  public function __construct(Stock $stock, $quantity, $type) {
    $this->user = new User();
    $this->user->load();
    $this->stock = $stock;
    $this->quantity = $quantity;
    $this->type = $type;
    $this->value = $stock->get_value();
  }
  
  public function get_stock() {
    return $stock;
  }
  
  public function get_user() {
    return $user;
  }
  
  public function get_quantity() {
    return $quantity;
  }
  
  public function get_value() {
    return $value;
  }
  
  public function set_stock($stock) {
    $this->stock = $stock;
  }
  
  public function set_user($user) {
    $this->user = $user;
  }
  
  public function set_quantity($quantity) {
    $this->quantity = $quantity;
  }
  
  public function set_value($value) {
    $this->value = $value;
  }
  
  public function buy() {
    
    if (($this->quantity * $this->stock->get_value()) <= $this->user->get_balance()) {
      return $this->insert("BUY");
    } else {
      return array('error' => "true", 'type' => 'transaction', 'message' => 'Insufficient Funds');
    }
  }
  
  public function sell() {
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    
    $sql = "SELECT symbol, 
    quantity, type, 
    SUM((quantity) * (CASE WHEN type = 'BUY' THEN 1 WHEN type = 'SELL' THEN -1 END)) AS t_quantity 
    FROM Transactions 
    INNER JOIN Stocks ON Transactions.stock_id = Stocks.id 
    WHERE user_id = " . $this->user->get_id() . " AND symbol = '" . $this->stock->get_symbol() . "';";
    
    if ($row = $conn->query($sql)->fetch_assoc()) {
      if ($this->quantity <= $row['t_quantity']) {
        $conn->close();
        return $this->insert("SELL");
      } else {
        $conn->close();
        return array('error' => "true", 'type' => 'transaction', 'message' => 'Insufficient Stock');
      }
    } else {
      $conn->close();
      return array('error' => "true", 'type' => 'database', 'message' => 'database error');
    }
  }
  
  public function insert($type) {
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
          
    $sql = "INSERT INTO Transactions (stock_id, user_id, quantity, value, type) 
    VALUES (" . $this->stock->get_id() . ", " . $this->user->get_id() . ", " . $this->quantity . ", " . $this->value . ", '" . $type . "');";
    
    if ($conn->query($sql)) { 
      if ($type == "BUY") $mul = -1; elseif ($type == "SELL") $mul = 1; else $mul = 0;
      $sql = "UPDATE Users SET balance = " . ($this->user->get_balance() + $mul * $this->stock->get_value() * $this->quantity). " WHERE id = " . $this->user->get_id() . ";";
      $conn->query($sql);
      $conn->close();
      return array('success' => "true", "type" => "transaction", "message" => $type . " Successful");
    } else {
      $conn->close();
      return array('error' => "true", 'type' => 'database', 'message' => 'database error');
    }
    
    $conn->close();
  }
  
}

?>