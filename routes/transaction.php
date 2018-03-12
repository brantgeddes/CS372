<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($_SESSION['login']) {
  if ($method === 'POST') {

    $str_json = file_get_contents('php://input');
    $data = json_decode($str_json);
    $stock = get_stock_data($data->symbol);
    if ($data->quantity > 0) {
      if ($data->type == 'Buy') {
        if (($data->quantity * $stock['latestPrice']) <= $_SESSION['balance']) {
          $conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);
          
          $sql = "SELECT id FROM Stocks WHERE symbol='" . $data->symbol . "';";
          if ($stock_id = $conn->query($sql)->fetch_assoc()) {
            $stock_id = $stock_id['id'];

            $sql = "SELECT quantity, COUNT(*) AS count FROM Portfolio WHERE user_id=" . $_SESSION['id'] . " AND stock_id=" . $stock_id . ";";
            if ($stock_exists = $conn->query($sql)->fetch_assoc()) {
              $quantity = $stock_exists['quantity'];
              $stock_exists = $stock_exists['count'];

              if ($stock_exists == 0) {
                $sql = "INSERT INTO Portfolio 
                (user_id, stock_id, quantity) 
                VALUES 
                ( " . $_SESSION['id'] . ", " . $stock_id . ", '" . $data->quantity . "')";

                if ($conn->query($sql)) {
                  $_SESSION['balance'] = $_SESSION['balance'] - $data->quantity * $stock['latestPrice'];
                  $sql = "UPDATE Users SET balance=" . $_SESSION['balance'] . " WHERE id=" . $_SESSION['id'] . ";";
                  $conn->query($sql);
                }
                else {
                  echo json_encode(array('error' => "true", 'type' => 'database', 'message' => 'database error'));
                }
              } elseif ($stock_exists == 1) {

                $quantity = $quantity + $data->quantity;

                $sql = "UPDATE Portfolio SET
                quantity=" . $quantity . "
                WHERE
                user_id=" . $_SESSION['id'] . " AND stock_id=" . $stock_id . ";";

                if ($conn->query($sql)) {
                  $_SESSION['balance'] = $_SESSION['balance'] - $data->quantity * $stock['latestPrice'];
                  $sql = "UPDATE Users SET balance=" . $_SESSION['balance'] . " WHERE id=" . $_SESSION['id'] . ";";
                  $conn->query($sql);
                }
                else {
                  echo json_encode(array('error' => "true", 'type' => 'database', 'message' => 'database error'));
                }

              } else {
                echo json_encode(array('error' => "true", 'type' => 'internal', 'message' => 'internal logic error'));
              }

              mysqli_close($conn);
            } else {
              echo json_encode(array('error' => "true", 'type' => 'database', 'message' => 'database error'));
            }
          } else {
            echo json_encode(array('error' => "true", 'type' => 'database', 'message' => 'database error'));
          }
        } else {
          echo json_encode(array('error' => "true", 'type' => 'transaction', 'message' => 'insufficient funds'));
        }
      } elseif ($data->type == "Sell") {
        
        $conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);
        
        $sql = "SELECT id FROM Stocks WHERE symbol='" . $data->symbol . "';";
        $stock_id = $conn->query($sql)->fetch_assoc();
        $stock_id = $stock_id['id'];
        
        $sql = "SELECT quantity FROM Portfolio WHERE user_id='" . $_SESSION['id'] . "' AND stock_id='" . $stock_id . "';";
        $quantity = $conn->query($sql)->fetch_assoc();
        $quantity = $quantity['quantity'];
        
        if ($quantity == $data->quantity) {
          
          $sql = "DELETE FROM Portfolio WHERE user_id=" . $_SESSION['id'] . " AND stock_id=" . $stock_id . ";";
          if ($conn->query($sql)) {
            $_SESSION['balance'] = $_SESSION['balance'] + $data->quantity * $stock['latestPrice'];
            $sql = "UPDATE Users SET balance=" . $_SESSION['balance'] . " WHERE id=" . $_SESSION['id'] . ";";
            $conn->query($sql);
          } else {
            echo json_encode(array('error' => "true", 'type' => 'database', 'message' => 'database error'));
          }
        } elseif ($quantity > $data->quantity) {
          
          $quantity = $quantity - $data->quantity;
          
          $sql = "UPDATE Portfolio SET
          quantity=" . $quantity . "
          WHERE user_id=" . $_SESSION['id'] . " AND stock_id=" . $stock_id . ";";
          
          if ($conn->query($sql)) {
            $_SESSION['balance'] = $_SESSION['balance'] + $data->quantity * $stock['latestPrice'];
            $sql = "UPDATE Users SET balance=" . $_SESSION['balance'] . " WHERE id=" . $_SESSION['id'] . ";";
            $conn->query($sql);
          } else {
            echo json_encode(array('error' => "true", 'type' => 'database', 'message' => 'database error'));
          }
          
        } elseif ($quantity < $data->quantity) {
          echo json_encode(array('error' => "true", 'type' => 'transaction', 'message' => 'Insufficent stock to sell'));
        } else {
          echo json_encode(array('error' => "true", 'type' => 'transaction', 'message' => 'Bad Quantity'));
        }
        
        mysqli_close($conn);
        
      } else {
        echo json_encode(array('error' => "true", 'type' => 'transaction', 'message' => 'transaction type out of bounds'));
      }
    } else {
      echo json_encode(array('error' => "true", 'type' => 'data', 'message' => 'stock data out of range'));
    }
   
  } 
}

?>