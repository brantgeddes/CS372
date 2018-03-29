<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($_SESSION['login']) {
  if ($method === 'GET') {
    
    $app = new App();
    echo json_encode($app->leaderboard());
    
  } else {

  }
}

?>