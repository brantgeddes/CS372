<?php

include "includes.php";
var_dump($_SESSION);

if ($_SESSION['login'] and ($_SESSION['type'] == "admin")) {
	
	$start = microtime(true);
	
	$curl = curl_init();
	$api_endpoint = 'https://api.iextrading.com/1.0/ref-data/symbols';
	curl_setopt($curl, CURLOPT_URL, $api_endpoint);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	
	$result = curl_exec($curl);
	$result = json_decode($result);
	
	$conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);
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
		$sql = "INSERT INTO Stocks (symbol, name, sector, industry, enable) VALUES ('" . $stock->{"symbol"} . "', '" . $stock->{"companyName"} . "', '" . $stock->{"sector"} . "', '" . $stock->{"industry"} . "',1);";
		if ($conn->query($sql)); 
	}
	
	mysqli_close($conn);
	curl_close($curl);
	$end = microtime(true);
	$execute = ($end - $start)/60;
	echo $execute . "mins\n";
	
} else {
	echo "Not logged in";
}
?>