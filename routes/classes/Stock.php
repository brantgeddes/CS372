<?php

class Stock {
  
  private $symbol;
  private $name;
  private $value;
  private $quantity;
  
  public function __construct($symbol = null, $name = null, $value = null, $quantity = null) {
    
    $this->symbol = $symbol;
    
    if ($name) $this->name = $name;
    if ($value) $this->value = $value;
    if ($quantity) $this->quantity = $quantity;    
    
  }
  
  public function get() {
    return array('symbol' => $this->symbol, 'name' => $this->name, 'value' => $this->value, 'quantity' => $this->quantity);
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
  
  public function get_quantity() {
    return $this->quantity;
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
  
  public function set_quantity($quantity) {
    $this->quantity = $quantity;
  }
  
  public function load() {
    
    $curl = curl_init();
    $api_endpoint = 'https://api.iextrading.com/1.0/stock/' . $this->symbol . '/quote';
    curl_setopt($curl, CURLOPT_URL, $api_endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    $result = json_decode($result);
    
    $this->symbol = $result->{'symbol'};
    $this->name = $result->{'companyName'};
    $this->value = $result->{'latestPrice'};
    
  }
  
}

?>
