<!-- При перезагрузке страницы форма отправляется заново
Поэтому если перезагрузить страницы после нажатия кнопки "Show DataBase", тогда таблица исчезнет, при следующем клике - появится. -->


<?php
session_start();

require_once "config/connect.php";
?>

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
	function change_show_flag(){
		if ($_SESSION['to_show_info'])
			$_SESSION['to_show_info'] = false;
		else
			$_SESSION['to_show_info'] = true;
	}
	?>

	<?php
	if(isset($_POST['show_info'])){

		$api_result = $_SESSION['api_result'];
		change_show_flag();
		$to_show_info = $_SESSION['to_show_info'];

	} elseif (isset($_POST['save_info'])){

		$api_result = $_SESSION['api_result'];
		if ($api_result){
			require_once "config/dbwork.php";
		}
		$to_show_info = $_SESSION['to_show_info'];

	} else {
		$_SESSION['to_show_info'] = false;
	}

	$is_selected = false;
	$header_text = "Weather in ";

	if (isset($_POST['location'])) {

		$header_text .= "{$_POST['location']}";
		$location = "{$_POST['location']}";
		$is_selected = true;
		$_SESSION['location'] = $_POST['location'];

	}	elseif ($_SESSION['location']) {
		$header_text .= "{$_SESSION['location']}";
	} else {
		$header_text = "Select a City!";
		$is_selected = false;
	}

	if ($is_selected) {
		$queryString = http_build_query([
			'access_key' => 'bcd91eba085cc8e6fc57cd0c6b1adcf7',
			'query' => $location,
		]);

		$ch = curl_init(sprintf('%s?%s', 'http://api.weatherstack.com/current', $queryString));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$json = curl_exec($ch);
		curl_close($ch);

		$api_result = json_decode($json, true);

		$_SESSION['api_result'] = $api_result;
	}
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

		<form method="POST">
			<div class="btns">
				<button type="submit" name="location" value="New York">New York</button>
				<button type="submit" name="location" value="Washington">Washington</button>
				<button type="submit" name="location" value="Los Angeles">Los Angeles</button>
				<button type="submit" name="location" value="Miami">Miami</button>
				<button type="submit" name="location" value="Seattle">Seattle</button>
			</div>
			<div class="btns btns-columns">
				<button class="save_btn" type="submit" name="save_info">Save</button>
				<button class="info_btn" type="submit" name="show_info">Show DataBase</button>
			</div>
		</form>

		<?php
		if ($to_show_info) {
			require_once "config/show_db.php";
		}
		?>


	</div>

</body>
</html>




