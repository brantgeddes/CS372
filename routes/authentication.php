 <?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
  
  $conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);
  
  $str_json = file_get_contents('php://input');
  $data = json_decode($str_json);
  
  $data->email = validate($data->email);
  $data->password = validate($data->password);
  
  $sql = "SELECT email, username, password, type, balance FROM Users WHERE email='" . $data->email . "';";
  
  if ($row = $conn->query($sql)->fetch_assoc()) {
    if ($row["password"] == hash($HASH_TYPE, $data->password)) {
      echo json_encode(array('valid' => 'true', 'email' => $data->email, 'username' => $row["username"], 'type' => $row["type"], 'balance' => $row["balance"]));
    } else {
      echo json_encode(array('valid' => 'false'));
    }
  } else {
    echo json_encode(array('valid' => 'false'));
  }
  
  mysqli_close($conn);
  
} else {
 
}

?>