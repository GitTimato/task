<?php
session_start();

require_once "config/connect.php";
// require_once "config/btn-control.php";
// $api_result = $_SESSION['api_result'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
	<link rel="stylesheet" href="./css/styles.css">
	<title>Task 1</title>
</head>
<body>


	<?php
	if(isset($_POST['show_info'])){

		$api_result = $_SESSION['api_result'];
		if(!isset($_SESSION['show']) or $_SESSION['show'])
			$to_show_info = true;

	} elseif (isset($_POST['save_info'])){

		$api_result = $_SESSION['api_result'];
		if ($api_result){
			require_once "config/dbwork.php";
		}
		$to_show_info = $_SESSION['to_show_info'];

	} else {
		$_SESSION['show'] = true;
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
		$api_result = $_SESSION['api_result'];
	} else {
		$header_text = "Select a City!";
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

		<p class="text-center mb-4">
			<?php
			if (isset($_SESSION['location']))
				echo "Weather in ".$_SESSION['location'];
			else
				echo "Select a City!";
			?>
		</p>
		<div class="row">
			<table class="info-tbl table">
				<tr>
					<th>Country</th>
					<th>City</th>
					<th>Local Time</th>
					<th>Temperature</th>
					<th>Wind speed</th>
					<th>Pressure</th>
				</tr>
				<tr>
					<td><?php echo $api_result['location']['country'] ?></td>
					<td><?php echo $api_result['location']['name']?></td>
					<td><?php echo $api_result['location']['localtime']?></td>
					<td><?php echo $api_result['current']['temperature']?></td>
					<td><?php echo $api_result['current']['wind_speed']?></td>
					<td><?php echo $api_result['current']['pressure']?></td>
				</tr>
			</table>
		</div>

		<!-- <div class="row"> -->
			<p class="text-center mb-4">All Cities</p>
			<!-- </div> -->

			<form method="POST" class="city-btns">
				<div class="d-flex justify-content-center my-btn-background">
					<div class="col-2 mr-4 test">
						<button type="submit" class="btn btn-outline-info cust-btn" name="location" value="New York">New York</button>
					</div>

					<div class="col-2 mr-4 test">
						<button type="submit" class="btn btn-outline-info cust-btn" name="location" value="Washington">Washington</button>
					</div>

					<div class="col-2 test">
						<button type="submit" class="btn btn-outline-info cust-btn" name="location" value="Los Angeles">Los Angeles</button>
					</div>

					<div class="col-2 ml-4 test">
						<button type="submit" class="btn btn-outline-info cust-btn" name="location" value="Miami">Miami</button>
					</div>

					<div class="col-2 ml-4 test">
						<button type="submit" class="btn btn-outline-info cust-btn" name="location" value="Seattle">Seattle</button>
					</div>
				</div>
				<div class="btns btns-columns d-flex">
					<button class="btn btn-info btn-lg mb-4" type="submit" name="save_info">Save</button>
					<button class="btn btn-info btn-lg" type="submit" name="show_info">Show DataBase</button>
				</div>
			</form>

			<?php
			if ($to_show_info) {
				require_once "config/show_db.php";
				$_SESSION['show'] = false;
			}
			?>

		</div>

	</body>
	</html>