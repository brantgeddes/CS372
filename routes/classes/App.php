<?php

class App {
  private $user;
  private $bug;
  private $market;
  
  public function authentication($email, $password) {
    $this->user = new User($email, $password);
    return $this->user->authenticate();
  } 
  
  public function find_stocks($symbol) {
    $this->user = new User();
		$this->user->load();
		$this->market = new Market($this->user);
		return $this->market->find_stocks($symbol);
  }
  
  public function import_stocks() {
    
    $start = microtime(true);
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);

    $curl = curl_init();
    $api_endpoint = 'https://api.iextrading.com/1.0/ref-data/symbols';
    curl_setopt($curl, CURLOPT_URL, $api_endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    $result = json_decode($result);

    $stock_array = array();

    foreach ($result as $stock) 
    {
      if (is_object($stock))
      {
        if ($stock->{"isEnabled"} == true and $stock->{"name"}!=""and $stock->{"symbol"}!="") 
        {
          #$api_endpoint = 'https://api.iextrading.com/1.0/stock/market/batch?symbols='.$stock->{"symbol"}.'&types=company';     
          $api_endpoint = 'https://api.iextrading.com/1.0/stock/'.$stock->{"symbol"}.'/batch?types=company';
          curl_setopt($curl, CURLOPT_URL, $api_endpoint);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

          $result2 = curl_exec($curl);
          $result2 = json_decode($result2);

          foreach ($result2 as $stock2) 
          {
            if (is_object($stock2))
            {
              if ($stock2->{"sector"}!="" and $stock2->{"industry"}!=""and $stock2->{"symbol"}!=""and $stock2->{"companyName"}!="")
              {
                $stock_array[] = (object)array("symbol" => $stock2->{"symbol"}, "name" => $stock2->{"companyName"}, "sector" => $stock2->{"sector"}, "industry" => $stock2->{"industry"});
              }
            }
          }	
        }
      }
    }

    $sql = "DELETE FROM Stocks;";
    $conn->query($sql);

    foreach ($stock_array as $stock) {
      $sql = "INSERT INTO Stocks (symbol, name, sector, industry, enable) VALUES ('" . $stock->{"symbol"} . "', '" . $stock->{"name"} . "', '" . $stock->{"sector"} . "', '" . $stock->{"industry"} . "',1);";
      if ($conn->query($sql)); 
    }

    mysqli_close($conn);
    curl_close($curl);
    $end = microtime(true);
    $execute = ($end - $start)/60;
    return $execute . "mins\n";
  }
  
  public function leaderboard() {
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    $sql = "SELECT id FROM Users WHERE type='trader';";
    
    $results = $conn->query($sql);
    $user_list = array();
    
    while ($row = $results->fetch_assoc()){
      $user_list[] = $row['id'];
    }
    
    $this->user = new User();
    $this->market = new Market();
    
    $i = 0;
    $response = array();
    while ($i < count($user_list)) {
      $this->user->load($user_list[$i]);
      $response[] = array('username' => $this->user->get_username(), 'net' => $this->market->net_worth($this->user));
      $i++;
    }
    
    usort($response, function ($item1, $item2) {
      return ($item2['net'] == $item1['net']) ? 0 : (($item2['net'] > $item1['net']) ? 1 : -1);
    });
    
    $conn->close();
    return $response;
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
  
  public function report($description) {
    
		$this->bug = new Bug_Report(validate($description));
 		return $this->bug->submit();
		
  }
	
	public function get_reports() {
		
		$this->user = new User();
		$this->user->load();
		
		if ($this->user->get_type() == 'admin') {
			$this->bug = new Bug_Report();
			return $this->bug->get();
		} else {
			return array("error" => "true", "type" => "Permission", "message" => "Must be an admin to view active bug reports");
		}
	}
	
	public function mark_report($id) {
		
		$this->user = new User();
		$this->user->load();
		if ($this->user->get_type() == 'admin') {
			$this->bug = new Bug_Report();
			return $this->bug->mark_solved($id);
		} else {
			return array("error" => "true", "type" => "Permission", "message" => "Must be an admin to alter bug reports");
		}
	}
	
}

?>