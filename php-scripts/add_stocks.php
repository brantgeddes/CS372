<?php
  
  include "../routes/includes.php";

  $curl = curl_init();
  $api_endpoint = 'https://api.iextrading.com/1.0/ref-data/symbols';     
  
  curl_setopt($curl, CURLOPT_URL, $api_endpoint);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

  $result = curl_exec($curl);

  curl_close($curl);

  $result = json_decode($result);

  $conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);
  foreach ($result as $stock) {
    if ($stock->{"isEnabled"} == true) {
      $sql = "INSERT INTO Stocks (symbol, name) VALUES ('" . $stock->{"symbol"} . "', '" . $stock->{"name"} . "');";
      if ($conn->query($sql)){} else {
        echo "Insert Failed\n";
      }
    }
  }

  mysqli_close($conn);

?>