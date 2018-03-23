<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($_SESSION['login']) {
  if ($method === 'POST') {
    
    $str_json = file_get_contents('php://input');
    $data = json_decode($str_json);
    
    if ($data->quantity == 0){
      echo json_encode(array('error' => "true", 'type' => 'transaction', 'message' => 'Bad Quantity'));
    } else {
    
      $str_json = file_get_contents('php://input');
      $data = json_decode($str_json);

      $stock = new Stock($data->symbol);
      $stock->load();

      $user = new User();
      $user->load();

      $transaction = new Transaction($stock, $user, $data->quantity);
      
      if ($data->type == 'Buy') {
        echo json_encode($transaction->buy());
      } elseif ($data->type == 'Sell') {
        echo json_encode($transaction->sell());
      } else {
        echo json_encode(array('error' => "true", 'type' => 'transaction', 'message' => 'transaction type out of bounds'));
      }
      
    }
  }
}
 
?>