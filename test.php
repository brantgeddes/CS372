<?php

include "routes/includes.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
  $str_json = file_get_contents('php://input');
  $data = json_decode($str_json);
  
  echo json_encode(array("report" => $data->report));
} else {
  
  $user = new User();
  echo json_encode($user->load(19));
  $user->login();
  $stock = new Stock("AAPL");
  echo json_encode($stock->load());
  $transaction = new Transaction($stock, $user, 10);
  
  echo json_encode($transaction->buy());
  //echo json_encode($transaction->sell());
  
}
?>