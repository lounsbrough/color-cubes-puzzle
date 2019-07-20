<!DOCTYPE html>

<html>

<head>

<script type="text/javascript" src="assets/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="assets/touch.js"></script>
<link rel='stylesheet' href='assets/huebee/huebee.min.css' />
<script src='assets/huebee/huebee.pkgd.min.js'></script>

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
#color_selector {
	height: 5vw;
	width: 5vw;
}
.cube_side_selected {
	box-shadow: inset 0px 0px 10px 3px rgba(0,0,0,0.9);
}

</style>

<script>

var hueb;

$(function() {

	$(".cube_side_td").click(function() {
		$(".cube_side_td").removeClass("cube_side_selected");
		$(this).addClass("cube_side_selected");
	});

	$("#color_selector").each( function( i, elem ) {
		hueb = new Huebee( elem, {
			setText: false,
			shades: 1,
			hues: 7,
			saturations: 1
		});
	});

	hueb.on("change", function(color, hue, sat, lum) {
		$(".cube_side_selected").css("background-color",color);
		updateCubeConfig();
	});	

});

function updateCubeConfig() {

	var cubeConfigJson = '{';
	for (var i=1; i<=4; i++) {

		if (i > 1) {
			cubeConfigJson += ',';
		}

		cubeConfigJson += '"'+i+
		'":{"back":"'+$("#cube_table_"+i).find(".cube_side_td_back").css("background-color")+
		'","top":"'+$("#cube_table_"+i).find(".cube_side_td_top").css("background-color")+
		'","front":"'+$("#cube_table_"+i).find(".cube_side_td_front").css("background-color")+
		'","bottom":"'+$("#cube_table_"+i).find(".cube_side_td_bottom").css("background-color")+
		'","left":"'+$("#cube_table_"+i).find(".cube_side_td_left").css("background-color")+
		'","right":"'+$("#cube_table_"+i).find(".cube_side_td_right").css("background-color")+'"}';
		
	}
	cubeConfigJson += '}';

	$.get("ajax/update_cube_config.php?json=" + cubeConfigJson);

}

</script>

</head>

<body>

	<div id="cubes_wrapper_div">

<?php

$cube_config_json = file_get_contents("cube_config.json");
$cube_config = json_decode($cube_config_json, true);

foreach ($cube_config as $cube_number => $cube_colors) {
	
?>

<table id="cube_table_<?php echo $cube_number; ?>" class="cube_table">

<tbody>
<tr>
	<td></td>
	<td class="cube_side_td cube_side_td_back" style="background-color:<?php echo $cube_colors["back"]; ?>">BACK</td>
	<td></td>
</tr>
<tr>
	<td class="cube_side_td cube_side_td_left" style="background-color:<?php echo $cube_colors["left"]; ?>">LEFT</td>
	<td class="cube_side_td cube_side_td_top" style="background-color:<?php echo $cube_colors["top"]; ?>">TOP</td>
	<td class="cube_side_td cube_side_td_right" style="background-color:<?php echo $cube_colors["right"]; ?>">RIGHT</td>
</tr>
<tr>
	<td></td>
	<td class="cube_side_td cube_side_td_front" style="background-color:<?php echo $cube_colors["front"]; ?>">FRONT</td>
	<td></td>
</tr>
<tr>
	<td></td>
	<td class="cube_side_td cube_side_td_bottom" style="background-color:<?php echo $cube_colors["bottom"]; ?>">BOTTOM</td>
	<td></td>
</tr>
</tbody>

</table>

<?php
}
?>

		<div>
			<input id="color_selector" style="background-color:black" />
		</div>

		<br>

		<div>
			<form method="get" action="solution.php">
				<select name="solution_type">
					<option value="different">Different Colors</option>
					<option value="same">Same Colors</option>
				</select>
				<br><br>
				<button type="submit">Solve</button>
			</form>
		</div>

	</div>

</body>

</html>