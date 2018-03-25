<?php

class App {
  private $user;
  private $bug;
  private $market;
  
  public function __construct() {}
  
  public function get_user() {
    return $this->user->get();
  }
  
  public function set_user(User $user) {
    $this->user = $user;  
  }
  
  public function authentication($email, $password) {
    $this->user = new User($email, $password);
    return $this->user->authenticate();
  } 
  
  public function find_stocks($symbol) {
    $this->user = new User();
		$this->user->load();
		$this->market = new Market($user);
		return $this->market->find_stocks($symbol);
  }
  
  public function import_stocks() {
    
  }
  
  public function leaderboard() {
    
  }
  
  public function logout() {
    User::logout();
  }
  
  public function return_stocks() {
    $this->user = new User();
    $this->user->load();
    $this->market = new Market($this->user);
    return $this->market->return_stocks();
  }
  
  public function signup($email, $password, $username) {
    $this->user = new User($email, $password, $username);
    return $this->user->signup();
  }
  
  public function toggle($symbol) {
    $this->market = new Market();
	  return $this->market->toggle(validate($symbol));
  }
  
  public function transaction($symbol, $quantity, $type) {
    
    if ($quantity <= 0){
      return array('error' => "true", 'type' => 'transaction', 'message' => 'Bad Quantity');
    } else {

      $this->market = new Market();
      return $this->market->insert_transaction(validate($symbol), validate($quantity), validate($type));
      
    }
  }
  
  public function report() {
    
  }
}

?>