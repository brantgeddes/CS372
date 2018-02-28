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
  
  $sql = "SELECT username FROM Users WHERE email='" . $data->email . "';";
  if ($username = $conn->query($sql)->fetch_assoc()) {
    $username = $username["username"];
  } else {
    log_event($conn->error, $sql);
  }
 
  $sql = "SELECT email, password FROM Users WHERE email='" . $data->email . "';";
  if ($row = $conn->query($sql)->fetch_assoc()) {
    if ($row["password"] == hash($hash_type, $data->password . $username)) {
      log_event("Login Successful", json_encode($data));
    } else {
      log_event("Login Failed", json_encode($data));
    }
  } else {
    log_event($conn->error, $sql);
  }
  
  mysqli_close($conn);
  
} else {
  header('location: index.html');
}

?>