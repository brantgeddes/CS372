<?php

class User {
  
  private $id;
  private $email;
  private $password;
  private $username;
  private $type;
  private $balance;
  
  public function __construct($email = null, $password = null, $username = null) {
    if ($email) $this->set_email(validate($email));
    if ($password) $this->set_password(validate($password));
    if ($username) $this->set_username(validate($username));
  }
  
  public function get_id() {
    return $this->id;
  }
  
  public function get_email() {
    return $this->email;
  }
  
  public function get_username() {
     return $this->username;
  }
  
  public function get_balance() {
     return $this->balance;
  }
  
  public function get_type() {
     return $this->type;
  }
  
  public function get() {
    if ($_SESSION['login']) {
      return array('valid' => 'true', 'email' => $_SESSION['email'], 'username' => $_SESSION["username"], 'type' => $_SESSION["type"], 'balance' => $_SESSION["balance"]);
    } else {
      return array('valid' => 'false');
    }
  }
  
  public function set_id($id) {
    $this->id = $id;
  }
  
  public function set_email($email) {
    $this->email = $email;
  }
  
  public function set_password($password) {
    $this->password = $this->hash_password($password);
  }
  
  public function set_username($username) {
    $this->username = $username;
  }
  
  public function set_balance($balance) {
    $this->balance = $balance;
  }
  
  public function set_type($type) {
    $this->type = $type;
  }
  
  public function hash_password($password) {
    return hash('sha512', $password);
  }
  
  public function login(){
    $_SESSION['login'] = true;
    $_SESSION['id'] = $this->id;
    $_SESSION['email'] = $this->email;
    $_SESSION['username'] = $this->username;
    $_SESSION['type'] = $this->type;
    $_SESSION['balance'] = $this->balance;	
  }
  
  public function logout() {
    session_unset();
    session_destroy();
  }
  
  public function load($id = null) {
    
    if ($id) $this->id = $id; else $this->id = $_SESSION['id'];
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    $sql = "SELECT email, username, type, balance FROM Users WHERE id = " . $this->id . ";";
    
    if($row = $conn->query($sql)->fetch_assoc()) {
    
      $this->email = $row['email'];
      $this->username = $row['username'];
      $this->type = $row['type'];
      $this->balance = $row['balance'];
      $conn->close();
      
      return array('success' => "true");
      
    } else {
      $conn->close();
      return array('error' => "true", 'type' => 'database', 'message' => 'database error');
    }
    
  }
  
  public function reset(){
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);

    $sql = "UPDATE Users 
    SET balance = " . $GLOBALS['STARTING_BALANCE'] . " 
    WHERE id = " . $this->id . ";
    DELETE FROM Transactions WHERE user_id = " . $this->id . ";";
    
    if ($conn->multi_query($sql)) {
      $conn->close();
      return array('success' => "true"); 
    } else {
      $conn->close();
      return array('error' => "true", 'type' => 'database', 'message' => 'database error');
    }
    
  }
  
  public function authenticate() {
    
    if ($_SESSION['login']) {
      $this->load();
      return array('valid' => 'true', 'email' => $this->email, 'username' => $this->username, 'type' => $this->type, 'balance' => $this->balance);
    } else {
      $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);

      $sql = "SELECT id, email, username, password, type, balance FROM Users WHERE email='" . $this->email . "';";
      $row = $conn->query($sql);
      if ($row->num_rows) {
        $row = $row->fetch_assoc();
        if ($row["password"] == $this->password) {
          $this->set_id($row['id']);
          $this->set_type($row['type']);
          $this->set_username($row['username']);
          $this->set_balance($row['balance']);
          $this->login();
          $conn->close();
          return array('valid' => 'true', 'email' => $this->email, 'username' => $row["username"], 'type' => $row["type"], 'balance' => $row["balance"]);
        } else {
          $conn->close();
          return array('valid' => 'false');
        }
      } else {
        $conn->close();
        return array('valid' => 'false');
      }

      $conn->close();
    }
  }
 
  public function signup() {
    
    if ($_SESSION['login']) {
      return array('valid' => 'true', 'email' => $_SESSION['email'], 'username' => $_SESSION["username"], 'type' => $_SESSION["type"], 'balance' => $_SESSION["balance"]);
    } else {

        $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
        
        $str_json = file_get_contents('php://input');
        $data = json_decode($str_json);

        $sql = "SELECT email, username, COUNT(*) AS count FROM Users WHERE email='" . $this->email . "' OR username='" . $this->username . "';";
        $row = $conn->query($sql)->fetch_assoc();
        if ($row["count"] == 0) {
          $sql = "INSERT INTO Users (email, password, username, type, balance) VALUES ('" . $this->email . "', '" . $this->password . "', '" . $this->username . "', 'trader', '" . $GLOBALS['STARTING_BALANCE'] . "');";
          if ($conn->query($sql)){
            $sql = "SELECT id FROM Users WHERE email='" . $this->email . "';";
            $row = $conn->query($sql)->fetch_assoc();
            $this->set_id($row['id']);
            $this->set_type('trader');
            $this->set_balance($GLOBALS['STARTING_BALANCE']);
            $this->login();
            $conn->close();
            return array('valid' => 'true', 'email' => $this->email, 'username' => $this->username, 'type' => 'trader', 'balance' => $GLOBALS['STARTING_BALANCE']);
          } else {
            $conn->close();
            return array('valid' => 'false');
          }
        } else {
          $conn->close();
          return array('valid' => 'false', 'error' => (($this->email == $row['email']) ? "email" : "username"));
        }

        mysqli_close($conn);
      
    }
  }
}


?>