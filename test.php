<?php

$str_json = file_get_contents('php://input');

$obj = json_decode($str_json);

phpinfo();

$json_obj = json_encode($obj);

echo $json_obj; 

?>