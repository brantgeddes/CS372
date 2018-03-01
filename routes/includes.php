<?php

  //This contains password information for db
  include 'tokens.php';

  ////////////////////////////////////////////////////////////
  ///////////////DEV MODE////////////////////////////////////
  $dev_mode = true;  //Comment for production mode
  //////////////////////////////////////////////////////////
  /////////////////////////////////////////////////////////
  
  $HASH_TYPE = 'sha512';

  $API_STOCK_ENDPOINT = "https://api.iextrading.com/1.0/stock/market/batch";

  session_start();  

  $DBServerName = "localhost";
  $DBUserName = $DB_USERNAME;
  $DBPassword = $DB_PASSWORD;
  $DBName = $DB_NAME;

  function log_event($message, $data) {
    global $dev_mode;
    if (isset($dev_mode) && $dev_mode) {
      echo $message . ' ';
      echo $data;
    }
  }

//Default values for API:
//$http_method = 'GET'
//$api_endpoint = 'https://www.alphavantage.co/query'
//$data = array('function' => 'TIME_SERIES_DAILY', 'symbol' => 'AAPL', 'outputsize' => 'compact', 'datatype' => 'json', 'apikey' => '37YNWZBM25LC9QHH');
function call_stock_API($http_method, $api_endpoint, $data) {
  
  $curl = curl_init();
  
  switch ($http_method){
    case 'POST':
      return 0;
      break;
    case 'PUT':
      return 0;
      break;
    case 'DELETE':
      return 0;
      break;
    default:
      if ($data) $api_endpoint = sprintf("%s?%s", $api_endpoint, http_build_query($data));
      break;
  }
  
  curl_setopt($curl, CURLOPT_URL, $api_endpoint);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

  $result = curl_exec($curl);

  curl_close($curl);

  return $result;
  
}

//Returns previous month stock data and current quote
function get_stock_data($symbol) {
  global $API_STOCK_ENDPOINT; 
  $data = array('symbols' => $symbol, 'types' => 'quote,chart', 'range' => '1m', 'last' => '5');
  $stock_json = call_stock_API('GET', $API_STOCK_ENDPOINT, $data);
  $stock = json_decode($stock_json, true);
  
  return $stock[strtoupper($symbol)];
}

?>