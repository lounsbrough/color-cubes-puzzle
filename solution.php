<!DOCTYPE html>

<html>

<head>

<style>

#cubes_wrapper_div {
	text-align: center;
}
.cube_table {
	 display: inline-block;
}
.cube_table td {
	font-weight: bold;
	font-size: 1vw;
	height: 6vw;
	width: 6vw;
}

</style>

</head>

<body>

	<div id="cubes_wrapper_div">

	<button><a href="setup.php" style="text-decoration:none">Back to Cube Setup</a></button>
	<br><br>

	<h3>Cube Configuration:</h3>

<?php

$solution_type = $_GET["solution_type"];

$cube_config_json = file_get_contents("cube_config.json");
$cube_config = json_decode($cube_config_json, true);
$cube_config_original = $cube_config;
$solution_count = 0;

draw_cube_config();

for ($i=1; $i<=24; $i++) {

	next_cube_config(1,$i);

	for ($j=1; $j<=24; $j++) {

		next_cube_config(2,$j);

		for ($k=1; $k<=24; $k++) {

			next_cube_config(3,$k);

			for ($l=1; $l<=24; $l++) {

				next_cube_config(4,$l);

				if (check_solution()) {
					
					$solution_count++;
					echo '<h3>Solution #'.$solution_count.':</h3>';

					draw_cube_config();

				}

			}
		}
	}
}

if ($solution_count == 0) {
	echo '<h3>No Solutions Exist</h3>';
}

?>

	</div>

</body>

</html>

<?php

function next_cube_config($cube_number, $iteration) {

	global $cube_config;
	global $cube_config_original;

	if ($iteration == 5) {
		$cube_config[$cube_number] = $cube_config_original[$cube_number];
		rotate_cube($cube_number, "up");
	} else if ($iteration == 9) {
		$cube_config[$cube_number] = $cube_config_original[$cube_number];
		rotate_cube($cube_number, "up");
		rotate_cube($cube_number, "up");
	} else if ($iteration == 13) {
		$cube_config[$cube_number] = $cube_config_original[$cube_number];
		rotate_cube($cube_number, "down");
	} else if ($iteration == 17) {
		$cube_config[$cube_number] = $cube_config_original[$cube_number];
		rotate_cube($cube_number, "right");
		rotate_cube($cube_number, "up");
	} else if ($iteration == 21) {
		$cube_config[$cube_number] = $cube_config_original[$cube_number];
		rotate_cube($cube_number, "left");
		rotate_cube($cube_number, "up");
	}

	rotate_cube($cube_number,"right");

}

function rotate_cube($cube_number, $rotation_direction) {

	global $cube_config;
	$single_cube_config = $cube_config[$cube_number];

	$new_cube_config = $single_cube_config;

	switch ($rotation_direction) {

		case 'left':
			$new_cube_config["front"] = $single_cube_config["right"];
			$new_cube_config["left"] = $single_cube_config["front"];
			$new_cube_config["back"] = $single_cube_config["left"];
			$new_cube_config["right"] = $single_cube_config["back"];
			$cube_config[$cube_number] = $new_cube_config;
			break;

		case 'right':
			$new_cube_config["front"] = $single_cube_config["left"];
			$new_cube_config["right"] = $single_cube_config["front"];
			$new_cube_config["back"] = $single_cube_config["right"];
			$new_cube_config["left"] = $single_cube_config["back"];
			$cube_config[$cube_number] = $new_cube_config;
			break;

		case 'up':
			$new_cube_config["front"] = $single_cube_config["bottom"];
			$new_cube_config["top"] = $single_cube_config["front"];
			$new_cube_config["back"] = $single_cube_config["top"];
			$new_cube_config["bottom"] = $single_cube_config["back"];
			$cube_config[$cube_number] = $new_cube_config;
			break;

		case 'down':
			$new_cube_config["front"] = $single_cube_config["top"];
			$new_cube_config["bottom"] = $single_cube_config["front"];
			$new_cube_config["back"] = $single_cube_config["bottom"];
			$new_cube_config["top"] = $single_cube_config["back"];
			$cube_config[$cube_number] = $new_cube_config;
			break;

	}

}

function check_solution() {

	global $cube_config;
	global $solution_type;

	if ($solution_type == "different") {

		if ($cube_config[1]["front"] != $cube_config[2]["front"] &&
			$cube_config[1]["front"] != $cube_config[3]["front"] && 
			$cube_config[1]["front"] != $cube_config[4]["front"] &&
			$cube_config[2]["front"] != $cube_config[3]["front"] &&
			$cube_config[2]["front"] != $cube_config[4]["front"] &&
			$cube_config[3]["front"] != $cube_config[4]["front"] &&
			$cube_config[1]["top"] != $cube_config[2]["top"] &&
			$cube_config[1]["top"] != $cube_config[3]["top"] && 
			$cube_config[1]["top"] != $cube_config[4]["top"] &&
			$cube_config[2]["top"] != $cube_config[3]["top"] &&
			$cube_config[2]["top"] != $cube_config[4]["top"] &&
			$cube_config[3]["top"] != $cube_config[4]["top"] &&
			$cube_config[1]["back"] != $cube_config[2]["back"] &&
			$cube_config[1]["back"] != $cube_config[3]["back"] && 
			$cube_config[1]["back"] != $cube_config[4]["back"] &&
			$cube_config[2]["back"] != $cube_config[3]["back"] &&
			$cube_config[2]["back"] != $cube_config[4]["back"] &&
			$cube_config[3]["back"] != $cube_config[4]["back"] &&
			$cube_config[1]["bottom"] != $cube_config[2]["bottom"] &&
			$cube_config[1]["bottom"] != $cube_config[3]["bottom"] && 
			$cube_config[1]["bottom"] != $cube_config[4]["bottom"] &&
			$cube_config[2]["bottom"] != $cube_config[3]["bottom"] &&
			$cube_config[2]["bottom"] != $cube_config[4]["bottom"] &&
			$cube_config[3]["bottom"] != $cube_config[4]["bottom"]) {
			return true;		
		}

	} else if ($solution_type == "same") {

		if ($cube_config[1]["front"] == $cube_config[2]["front"] && 
			$cube_config[1]["front"] == $cube_config[3]["front"] && 
			$cube_config[1]["front"] == $cube_config[4]["front"] &&
			$cube_config[1]["top"] == $cube_config[2]["top"] && 
			$cube_config[1]["top"] == $cube_config[3]["top"] && 
			$cube_config[1]["top"] == $cube_config[4]["top"] &&
			$cube_config[1]["back"] == $cube_config[2]["back"] && 
			$cube_config[1]["back"] == $cube_config[3]["back"] && 
			$cube_config[1]["back"] == $cube_config[4]["back"] &&
			$cube_config[1]["bottom"] == $cube_config[2]["bottom"] && 
			$cube_config[1]["bottom"] == $cube_config[3]["bottom"] && 
			$cube_config[1]["bottom"] == $cube_config[4]["bottom"]) {
			return true;
		}

	}

	return false;

}

function draw_cube_config() {

	global $cube_config;

	foreach ($cube_config as $cube_number => $cube_colors) {
		
		echo '

		<table id="cube_table_'.$cube_number.'" class="cube_table">

		<tbody>
		<tr>
			<td></td>
			<td class="cube_side_td cube_side_td_back" style="background-color:'.$cube_colors["back"].'">BACK</td>
			<td></td>
		</tr>
		<tr>
			<td class="cube_side_td cube_side_td_left" style="background-color:'.$cube_colors["left"].'">LEFT</td>
			<td class="cube_side_td cube_side_td_top" style="background-color:'.$cube_colors["top"].'">TOP</td>
			<td class="cube_side_td cube_side_td_right" style="background-color:'.$cube_colors["right"].'">RIGHT</td>
		</tr>
		<tr>
			<td></td>
			<td class="cube_side_td cube_side_td_front" style="background-color:'.$cube_colors["front"].'">FRONT</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td class="cube_side_td cube_side_td_bottom" style="background-color:'.$cube_colors["bottom"].'">BOTTOM</td>
			<td></td>
		</tr>
		</tbody>

		</table>

		';

	}

	echo '<br><br>';

}

?>