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
  
  $sql = "SELECT COUNT(*) AS count FROM Users WHERE email='" . $data->email . "';";
  $row = $conn->query($sql)->fetch_assoc();
  if ($row["count"] != 1) {
    $hash_pass = hash($hash_type, $data->password . $data->username);
    $sql = "INSERT INTO Users (email, password, username) VALUES ('" . $data->email . "', '" . $hash_pass . "', '" . $data->username . "');";
    if ($conn->query($sql)){
      log_event("Add Successful", json_encode($data));
    } else {
      log_event($conn->error, $sql);
    }
  } else {
    log_event("Account Exists", json_encode($data));
  }
  
  mysqli_close($conn);
  
} else {
  header('location: index.html');
}

?>