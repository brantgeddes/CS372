<?php

  //This contains password information for db
  include 'tokens.php';

  ////////////////////////////////////////////////////////////
  ///////////////DEV MODE////////////////////////////////////
  $dev_mode = true;  //Comment for production mode
  //////////////////////////////////////////////////////////
  /////////////////////////////////////////////////////////
  
  $HASH_TYPE = 'sha512';
	$STARTING_BALANCE = 1000;
  $API_STOCK_ENDPOINT = "https://api.iextrading.com/1.0/stock/";

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

function validate($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function login($id, $email, $username, $type, $balance) {
	$_SESSION['login'] = true;
	$_SESSION['id'] = $id;
	$_SESSION['email'] = $email;
	$_SESSION['username'] = $username;
	$_SESSION['type'] = $type;
	$_SESSION['balance'] = $balance;	
}

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
      if ($data) $api_endpoint = $api_endpoint . $data . "/quote";
      break;
  }
  
  curl_setopt($curl, CURLOPT_URL, $api_endpoint);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

  $result = curl_exec($curl);

  curl_close($curl);
  return $result;
  
}

//Returns stock quote
function get_stock_data($symbol) {
  global $API_STOCK_ENDPOINT; 
  $stock_json = call_stock_API('GET', $API_STOCK_ENDPOINT, $symbol);
  $stock = json_decode($stock_json, true);
  
  return $stock;
}

?>