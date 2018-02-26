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
  
  $sql = "SELECT COUNT(*) AS count FROM Users WHERE email='" . $data->email . "' AND password='" . $data->password . "';";
  $row = $conn->query($sql)->fetch_assoc();
  if ($row["count"] != 1) {
    echo "0 match ";
  } else {
    echo "1 match";
  }
  
  mysqli_close($conn);
  
} else {
  header('location: index.html');
}

?>