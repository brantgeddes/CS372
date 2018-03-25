 <?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {

  $str_json = file_get_contents('php://input');
  $data = json_decode($str_json);
  
  $app = new App();
  echo json_encode($app->authentication($data->email, $data->password));
  
}

?>