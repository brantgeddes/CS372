<?php

  //This contains password information for db
  include 'tokens.php';

  ////////////////////////////////////////////////////////////
  ///////////////DEV MODE////////////////////////////////////
  $dev_mode = true;  //Comment for production mode
  //////////////////////////////////////////////////////////
  /////////////////////////////////////////////////////////
  
  $hash_type = 'sha512';

  session_start();  

  function log_event($message, $data) {
    global $dev_mode;
    if (isset($dev_mode) && $dev_mode) {
      echo $message . ' ';
      echo $data;
    }
  }
  
?>