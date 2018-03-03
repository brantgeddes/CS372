 <?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
  
  $conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);
  
  $str_json = file_get_contents('php://input');
  $data = json_decode($str_json);
  
  $data->email = validate($data->email);
  $data->password = validate($data->password);
  
  $sql = "SELECT username, password FROM Users WHERE email='" . $data->email . "';";
   
  if ($row = $conn->query($sql)->fetch_assoc()) {
    if ($row["password"] == hash($HASH_TYPE, $data->password . $row["username"])) {
      echo "success";
    } else {
      echo "fail";
    }
  } else {
    echo "fail";
  }
  
  mysqli_close($conn);
  
} else {
  header('location: index.html');
}

?>