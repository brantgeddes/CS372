<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($_SESSION['login']) {
  if ($method === 'POST') {

    $str_json = file_get_contents('php://input');
    $data = json_decode($str_json);
    $stock = new Stock($data->symbol, null, null, $data->quantity);
    $stock->load();
    
    if ($stock->get_quantity() > 0) {
      if ($data->type == 'Buy') {
        if (($stock->get_quantity() * $stock->get_value()) <= $_SESSION['balance']) {
          $conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);
          
          $sql = "SELECT id FROM Stocks WHERE symbol='" . $stock->get_symbol() . "';";
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
                ( " . $_SESSION['id'] . ", " . $stock_id . ", '" . $stock->get_quantity() . "')";

                if ($conn->query($sql)) {
                  $_SESSION['balance'] = $_SESSION['balance'] - $stock->get_quantity() * $stock->get_value();
                  $sql = "UPDATE Users SET balance=" . $_SESSION['balance'] . " WHERE id=" . $_SESSION['id'] . ";";
                  $conn->query($sql);
                }
                else {
                  echo json_encode(array('error' => "true", 'type' => 'database', 'message' => 'database error'));
                }
              } elseif ($stock_exists == 1) {

                $quantity = $quantity + $stock->get_quantity();

                $sql = "UPDATE Portfolio SET
                quantity=" . $quantity . "
                WHERE
                user_id=" . $_SESSION['id'] . " AND stock_id=" . $stock_id . ";";

                if ($conn->query($sql)) {
                  $_SESSION['balance'] = $_SESSION['balance'] - $stock->get_quantity() * $stock->get_value();
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
        
        $sql = "SELECT id FROM Stocks WHERE symbol='" . $stock->get_symbol() . "';";
        $stock_id = $conn->query($sql)->fetch_assoc();
        $stock_id = $stock_id['id'];
        
        $sql = "SELECT quantity FROM Portfolio WHERE user_id='" . $_SESSION['id'] . "' AND stock_id='" . $stock_id . "';";
        $quantity = $conn->query($sql)->fetch_assoc();
        $quantity = $quantity['quantity'];
        
        if ($quantity == $stock->get_quantity()) {
          
          $sql = "DELETE FROM Portfolio WHERE user_id=" . $_SESSION['id'] . " AND stock_id=" . $stock_id . ";";
          if ($conn->query($sql)) {
            $_SESSION['balance'] = $_SESSION['balance'] + $stock->get_quantity() * $stock->get_value();
            $sql = "UPDATE Users SET balance=" . $_SESSION['balance'] . " WHERE id=" . $_SESSION['id'] . ";";
            $conn->query($sql);
          } else {
            echo json_encode(array('error' => "true", 'type' => 'database', 'message' => 'database error'));
          }
        } elseif ($quantity > $stock->get_quantity()) {
          
          $quantity = $quantity - $stock->get_quantity();
          
          $sql = "UPDATE Portfolio SET
          quantity=" . $quantity . "
          WHERE user_id=" . $_SESSION['id'] . " AND stock_id=" . $stock_id . ";";
          
          if ($conn->query($sql)) {
            $_SESSION['balance'] = $_SESSION['balance'] + $stock->get_quantity() * $stock->get_value();
            $sql = "UPDATE Users SET balance=" . $_SESSION['balance'] . " WHERE id=" . $_SESSION['id'] . ";";
            $conn->query($sql);
          } else {
            echo json_encode(array('error' => "true", 'type' => 'database', 'message' => 'database error'));
          }
          
        } elseif ($quantity < $stock->get_quantity()) {
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
} else {
  echo json_encode(array('error' => 'true', 'type' => 'authentication', 'message' => 'user not logged in'));
}

?>