<?php

	#include "../routes/includes.php";

	$DBServerName = 'localhost';
	$DBUserName = 'pi';
	$DBPassword = 'raspberry';
	$DBName = 'test';
	
	$curl = curl_init();
	$api_endpoint = 'https://api.iextrading.com/1.0/ref-data/symbols';
	
	curl_setopt($curl, CURLOPT_URL, $api_endpoint);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	
	$result = curl_exec($curl);

	curl_close($curl);

	$result = json_decode($result);
	
	$conn = mysqli_connect($DBServerName, $DBUserName, $DBPassword, $DBName);
	
	foreach ($result as $stock) 
	{
		if ($stock->{"isEnabled"} == true and $stock->{"name"}!="") 
		{
			$curl = curl_init();
			$api_endpoint = 'https://api.iextrading.com/1.0/stock/'.$stock->{"symbol"}.'/company';     
  
			curl_setopt($curl, CURLOPT_URL, $api_endpoint);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

			$result2 = curl_exec($curl);

			curl_close($curl);
			
			$result2 = json_decode($result2);
			
			if ($result2->{"sector"}!="" and $result2->{"industry"}!="")
			{
				$sql = "INSERT INTO Stocks (symbol, name, sector, industry) VALUES ('" . $stock->{"symbol"} . "', '" . $stock->{"name"} . "', '" . $result2->{"sector"} . "', '" . $result2->{"industry"} . "');";
				if ($conn->query($sql))
				{
					
				} 
				else 
				{
					echo "Insert Failed\n";
				}
			}
		}
	}
	mysqli_close($conn);
?>