<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./css/styles.css">
	<title>Task 1</title>
</head>
<body>

	<?php 
	$header_text = "Weather in ";

	if (isset($_POST['ny_btn']))   {
		$header_text .= "{$_POST['ny_btn']}";
		$location = "{$_POST['ny_btn']}";
	}
	elseif  (isset($_POST['washington_btn']))  {
		$header_text .= "{$_POST['washington_btn']}";
		$location = "{$_POST['washington_btn']}";
	}
	elseif  (isset($_POST['la_btn']))  {
		$header_text .= "{$_POST['la_btn']}";
		$location = "{$_POST['la_btn']}";
	}
	elseif  (isset($_POST['miami_btn']))  {
		$header_text .= "{$_POST['miami_btn']}";
		$location = "{$_POST['miami_btn']}";
	}
	elseif  (isset($_POST['seattle_btn']))  {
		$header_text .= "{$_POST['seattle_btn']}";
		$location = "{$_POST['seattle_btn']}";
	}
	else    {
		$header_text = "Select a City!";
	}


	// $location = 'Moscow';

	$queryString = http_build_query([
		'access_key' => 'bcd91eba085cc8e6fc57cd0c6b1adcf7',
		'query' => $location,
	]);

	$ch = curl_init(sprintf('%s?%s', 'http://api.weatherstack.com/current', $queryString));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$json = curl_exec($ch);
	curl_close($ch);

	$api_result = json_decode($json, true);

	?>


	<div class="container">
		<p><?php echo $header_text ?></p>
		<table class="info-tbl">
			<tr>
				<th>Country</th>
				<th>City</th>
				<th>Local Time</th>
				<th>Temperature</th>
				<th>Wind speed</th>
				<th>Pressure</th>
			</tr> <!--ряд с ячейками заголовков-->
			<tr>
				<td><?php echo $api_result['location']['country'] ?></td>
				<td><?php echo $api_result['location']['name']?></td>
				<td><?php echo $api_result['location']['localtime']?></td>
				<td><?php echo $api_result['current']['temperature']?></td>
				<td><?php echo $api_result['current']['wind_speed']?></td>
				<td><?php echo $api_result['current']['pressure']?></td>
			</tr> <!--ряд с ячейками тела таблицы-->
		</table>

		<p>All Cities</p>

		<form  method="POST">
			<div class="btns">
				<button type="submit" name="ny_btn" value="New York">New York</button>
				<button type="submit" name="washington_btn" value="Washington">Washington</button>
				<button type="submit" name="la_btn" value="Los Angeles">Los Angeles</button>
				<button type="submit" name="miami_btn" value="Miami">Miami</button>
				<button type="submit" name="seattle_btn" value="Seattle">Seattle</button>
			</div>
		</form>

	</div>
</body>
</html>




