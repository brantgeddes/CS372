<?php

include 'includes.php';

session_unset();
session_destroy();

echo "Logout success";

?>