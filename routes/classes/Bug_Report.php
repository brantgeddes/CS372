<?php

class Bug_Report {
  
  private $user;
  private $description;
  private $timestamp;
  private $status;
  
  public function __construct($description = null){
    
    $this->user = new User();
    $this->user->load();
    
    if ($description) $this->description = $description;
    
    $this->timestamp = date("Y/m/d H:i:s");
    $this->status = 0;
    
  }
  
  public function submit(){
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    
    $sql = "INSERT INTO ReportBug 
    (description, submitted, status, userID) 
    VALUES (
    '" . $this->description . "',
    '" . $this->timestamp . "',
    " . $this->status . ",
    " . $this->user->get_id() . "
    );";
    
    if ($conn->query($sql)) {
      $conn->close();  
      return array("success" => true, "message" => "Bug submitted");
    } else {
      $conn->close();
      return array("error" => true, "type" => "database", "message" => "database error");
    }
    
    $conn->close();
  }
  
  public function get() {
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    
    $sql = "SELECT Users.username AS username, reportID, description, submitted, status 
    FROM ReportBug
    INNER JOIN Users ON Users.id = ReportBug.userID;";
    
    $results = $conn->query($sql);
    $report = array();
    while ($row = $results->fetch_assoc()){
      $report[] = array('username' => $row['username'], 'id' => $row['reportID'], 'description' => $row['description'], 'submitted' => $row['submitted'], 'status' => $row['status']);
    }
    
    $conn->close();
    return $report;
  }
  
  public function mark_solved($id) {
    
    $conn = mysqli_connect($GLOBALS['DB_SERVER'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    
    $sql = "UPDATE ReportBug SET status=1 WHERE reportID=" . $id . ";";
    
    if ($conn->query($sql)) {
      $conn->close();
      return array("success" => true, "message" => "Marked bug solved");
    } else {
      $conn->close();
      return array("error" => true, "type" => "database", "message" => "database error");
    }
    
    $conn->close();    
  }
  
}

?>