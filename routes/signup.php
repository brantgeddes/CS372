<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
  
  $DBServerName = "localhost";
  $DBUserName = $DB_USERNAME;
  $DBPassword = $DB_PASSWORD;
  $DBName = $DB_NAME;
  
  $conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);
  
  $str_json = file_get_contents('php://input');
  $data = json_decode($str_json);
  
  $data->email = validate($data->email);
  $data->password = validate($data->password);
  $data->username = validate($data->username);
  
  $sql = "SELECT COUNT(*) AS count FROM Users WHERE email='" . $data->email . "';";
  $row = $conn->query($sql)->fetch_assoc();
  if ($row["count"] != 1) {
    $hash_pass = hash($HASH_TYPE, $data->password);
    $sql = "INSERT INTO Users (email, password, username, type, balance) VALUES ('" . $data->email . "', '" . $hash_pass . "', '" . $data->username . "', 'trader', '" . $STARTING_BALANCE . "');";
    if ($conn->query($sql)){
      echo json_encode(array('valid' => 'true', 'email' => $data->email, 'username' => $data->username, 'type' => 'trader', 'balance' => $STARTING_BALANCE));
    } else {
      echo json_encode(array('valid' => 'false'));
    }
  } else {
    echo json_encode(array('valid' => 'false'));
  }
  
  mysqli_close($conn);
  
} else {
  header('location: index.html');
}

?>