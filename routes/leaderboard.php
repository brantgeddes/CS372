<?php
	$conn = mysqli_connect('localhost', 'pi', 'raspberry', 'stockmarket');
	
	$sql = "select username from Users";
	$result = mysqli_query($conn, $sql);
	
	$user = array();
	$totalbalance = array();
	$leaderboard = array('user'=>array(),'balance'=>array());
	
	while ($row = $result->fetch_assoc())
	{
		$user[]=$row['username'];
	}
	
	$index = sizeof($user);
	
	$curl = curl_init();
	
	for ($i=0;$i<$index;$i++)
	{
		#echo '<p>'.$user[$i].'</p>';
		$sql = "select username, quantity, symbol, balance from Users left join Portfolio on Portfolio.user_id=Users.id left join Stocks on Portfolio.stock_id=Stocks.id where username = '".$user[$i]."';";
		$result = mysqli_query($conn, $sql);
		if($result)
		{
			$net=0;
			$balance=0;
			
			while ($row = $result->fetch_assoc())
			{
				if($row['quantity']!=null and $row['symbol']!=null)
				{
					$api_endpoint = 'https://api.iextrading.com/1.0/stock/'.$row['symbol'].'/price';
					curl_setopt($curl, CURLOPT_URL, $api_endpoint);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					$apiresult = curl_exec($curl);
					$apiresult = json_decode($apiresult);
					$net = $net + ($row['quantity'] * $apiresult);
					#echo $net;
				}
				$balance = $row['balance'];
			}
			$totalbalance[] = $net + $balance;
			#echo '<p>'.$total.'</p>';
		}
	}
	
	for ($i=0;$i<$index;$i++)
	{
		$leaderboard['user'][] = $user[$i];
		$leaderboard['balance'][] = $totalbalance[$i];
	}
	
	echo json_encode($leaderboard);
?>