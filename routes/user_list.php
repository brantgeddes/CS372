<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($_SESSION['login']){
  if ($method === 'GET') {  //Get request to return user info
    $app = new App();
    
    if ($_GET) {  //Query string exists, get certain users {{not implemented on cient}}
      
      $name = $_GET['name'];
      echo json_encode($app->return_users($name));
      
    } else {  //Query string does not exist, get all users
      
      echo json_encode($app->return_users());
      
    }
  } elseif ($method === "POST") { //Post request to reset certain user account
    
    $str_json = file_get_contents('php://input');
    $data = json_decode($str_json);
    
    $app = new App();
    echo json_encode($app->reset_account($data->id));
    
  } else {  //Wrong request type
    
  }
} else {
  echo json_encode(array('error' => 'true', 'type' => 'Permissions', 'message' => 'User not logged in'));
}

?>