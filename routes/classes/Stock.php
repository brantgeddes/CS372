<?php

class Stock {
  
  private $symbol;
  private $name;
  private $value;
  private $quantity;
  
  public function __construct($symbol, $quantity = null) {
    
    if ($quantity) $this->quantity = $quantity;
    
    $curl = curl_init();
    $api_endpoint = 'https://api.iextrading.com/1.0/stock/' . $symbol . '/quote';
    curl_setopt($curl, CURLOPT_URL, $api_endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    $result = json_decode($result);
    
    $this->symbol = $result->{'symbol'};
    $this->name = $result->{'companyName'};
    $this->value = $result->{'close'};
    
  }
  
}

?>
