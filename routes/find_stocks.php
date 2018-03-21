<?php

include 'includes.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($_SESSION['login']) {
  if ($method === 'GET') {

    parse_str($_SERVER['QUERY_STRING'], $query_string);
    $symbol = $query_string['name'];
    validate($symbol);
    $conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);

    $sql = "SELECT * FROM Stocks WHERE name LIKE '" . $symbol . "%';";

    $result = $conn->query($sql);
    $response = array();
    $i = 0;
    while(($i < 99) && $row = $result->fetch_assoc()){
	switch($row["enable"])
	{
		case 1:
			$response[] = (object)array('symbol' => $row["symbol"], 'name' => $row["name"], 'enable' => 'true');
			break;
		case 0:
			break;
	}
      #$response[] = (object)array('symbol' => $row["symbol"], 'name' => $row["name"], 'enable' => $row["enable"]);
      $i++;
    }

    echo json_encode($response);

    mysqli_close($conn);


  } else {

  }
}

?>