 <?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {

  $str_json = file_get_contents('php://input');
  $data = json_decode($str_json);
  
  $data->email = validate($data->email);
  $data->password = validate($data->password);
  
  $user = new User($data->email, $data->password);
  
  echo json_encode($user->authenticate());
  
  
}

/*
$method = $_SERVER['REQUEST_METHOD'];
if ($_SESSION['login']) {
  echo json_encode(array('valid' => 'true', 'email' => $_SESSION['email'], 'username' => $_SESSION["username"], 'type' => $_SESSION["type"], 'balance' => $_SESSION["balance"]));
} else {

  if ($method === 'POST') {
    
    $conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);

    $str_json = file_get_contents('php://input');
    $data = json_decode($str_json);

    $data->email = validate($data->email);
    $data->password = validate($data->password);

    $sql = "SELECT id, email, username, password, type, balance FROM Users WHERE email='" . $data->email . "';";

    if ($row = $conn->query($sql)->fetch_assoc()) {
      if ($row["password"] == hash($HASH_TYPE, $data->password)) {
        echo json_encode(array('valid' => 'true', 'email' => $data->email, 'username' => $row["username"], 'type' => $row["type"], 'balance' => $row["balance"]));
        login($row['id'], $data->email, $row['username'], $row['type'], $row['balance']);
      } else {
        echo json_encode(array('valid' => 'false'));
      }
    } else {
      echo json_encode(array('valid' => 'false'));
    }

    mysqli_close($conn);

  } 
 
}

*/
?>