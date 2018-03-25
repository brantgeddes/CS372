<?php

class Stock {
  
  private $id;
  private $symbol;
  private $name;
  private $value;
  private $quantity;
  
  public function __construct($symbol = null, $name = null, $value = null, $quantity = null) {
    
    if ($symbol) $this->symbol = $symbol;
    
    if ($name) $this->name = $name;
    if ($value) $this->value = $value;
    if ($quantity) $this->quantity = $quantity;    
    
  }
  
  public function get() {
    return array('symbol' => $this->symbol, 'name' => $this->name, 'value' => $this->value, 'quantity' => $this->quantity);
  }
  
  public function get_id() {
    return $this->id;
  }
  
  public function get_symbol() {
    return $this->symbol;
  }
  
  public function get_name() {
    return $this->name;
  }
  
  public function get_value() {
    return $this->value;
  }
  
  public function set_symbol($symbol) {
    $this->symbol = $symbol;
  }
  
  public function set_name($name) {
    $this->name = $name;
  }
  
  public function set_value($value) {
    $this->value = $value;
  }
  
  public function disable_stock($symbol = null) {
    
    if ($symbol) $this->symbol = $symbol;
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    $sql = "UPDATE Stocks SET enable = 0 WHERE symbol = '" . $this->symbol . "';";
    
    if ($conn->query($sql)) {
      $conn->close();
      return array('enable' => 'false'); 
    } else {
      $conn->close();
      return false;
    }
    
    
  }
  
  public function enable_stock($symbol = null) {
    
    if ($symbol) $this->symbol = $symbol;
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    $sql = "UPDATE Stocks SET enable = 1 WHERE symbol = '" . $this->symbol . "';";
    
    if ($conn->query($sql)) { 
      $conn->close();
      return array('enable' => 'true'); 
    } else {
      $conn->close();
      return false;
    }
    
    
  }
  
  public function toggle_stock($symbol = null) {

    if ($symbol) $this->symbol = $symbol;
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    $sql = "SELECT enable FROM Stocks WHERE symbol = '" . $this->symbol . "';";
    
    $results = $conn->query($sql)->fetch_assoc();
    
    switch($results["enable"]) {
        
      case 1:
        $conn->close();
        return $this->disable_stock();
        break;
      case 0:
        $conn->close();
        return $this->enable_stock();
        break;
        
    }
    
    
  }
  
  public function load() {
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    
    $sql = "SELECT id FROM Stocks WHERE symbol = '" . $this->symbol . "';";
    
    if ($row = $conn->query($sql)->fetch_assoc()) {
         
      $curl = curl_init();
      $api_endpoint = 'https://api.iextrading.com/1.0/stock/' . $this->symbol . '/quote';
      curl_setopt($curl, CURLOPT_URL, $api_endpoint);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

      $result = curl_exec($curl);
      $result = json_decode($result);
      
      $this->id = $row['id'];
      $this->symbol = $result->{'symbol'};
      $this->name = $result->{'companyName'};
      $this->value = $result->{'latestPrice'};
      $conn->close();
      return array('success' => "true");
      
    } else {
      $conn->close();
      return array('error' => "true", 'type' => 'database', 'message' => 'database error');
    }
    
  }
  
}

?>
