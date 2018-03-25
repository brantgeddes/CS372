<?php

include 'includes.php';

$data = json_decode(file_get_contents("php://input"));

$app = new App();
echo json_encode($app->report($data->report));

?>