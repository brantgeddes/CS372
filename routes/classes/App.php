<?php

class App {
  private $user;
  private $bug;
  
  public function __construct() {
    $this->user = new User();
    $this->bug = new Bug_Report();
  }
  
  public function get_user() {
    return $this->user->get();
  }
}

?>