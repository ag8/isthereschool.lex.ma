<?php
	$wcvbpage = file_get_contents('http://www.wcvb.com/weather/closings');

	preg_match_all("/<div class=\"weather-closings-data-item\" data-name=\"(.*?)\" data-count=\"(.*?)\">\s*<div class=\"weather-closings-data-name\">(.*?)<\/div>\s*<div class=\"weather-closings-data-location\">\s*(.*?)<br>\s*<\/div>\s*<div class=\"weather-closings-data-status\">\s*(<ul class=\"weather-closings-data-status-list\">\s*(<li class=\"weather-closings-data-status-list-item\">(.*?)<\/li>\s*)*<\/ul>|(.*?))\s*<\/div>\s*<\/div>/", $wcvbpage, $wcvb_trash);

	$wcvb_array = array_pop(array_reverse($wcvb_trash));

	$statuses = array();
	$statuses[0] = "Open";
	$statuses[1] = "Open";
	$statuses[2] = "Open";
	$statuses[3] = "Open";
	$statuses[4] = "Open";
	$statuses[5] = "Open";
	$statuses[6] = "Open";
	$statuses[7] = "Open";
	$statuses[8] = "Open";
	
	
	$lexington = 'green';
	$closed = false;
	
	foreach ($wcvb_array as $value) {

		$name = get_string_between( $value, "<div class=\"weather-closings-data-name\">", "</div>");
			$what = get_string_between( $value, "<div class=\"weather-closings-data-status\">", "</div>");

		if ($name == 'Burlington Public') {
			$statuses[0] = $what;
		}
		if ($name == 'Woburn Public') {
			$statuses[1] = $what;
		}
		if ($name == 'Winchester Public') {
			$statuses[2] = $what;
		}
				if ($name == 'Arlington Public') {
					$statuses[3] = $what;
				}
		if ($name == 'Belmont Public') {
			$statuses[4] = $what;
		}
		if ($name == 'Waltham Public') {
			$statuses[5] = $what;
		}
		if ($name == 'Lincoln Public') {
			$statuses[6] = $what;
		}
		if ($name == 'Concord - Carlisle Regional') {
			$statuses[7] = $what;
		}
		if ($name == 'Bedford Public') {
			$statuses[8] = $what;
		}
		if ($name == 'Lexington Public') {
			$lexington = 'red';
						$closed = true;
		}
	}

	//var_dump( $statuses );
	
	//echo (strpos(strtolower($statuses[0]), 'closed') !== false ? 'green' : 'red');
	
	
	
	// Based on https://stackoverflow.com/a/9826656
	function get_string_between($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}
?>


<html>
 <head>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
		google.charts.load("current", {packages:["corechart"]});
		google.charts.setOnLoadCallback(drawChart);
		function drawChart() {
		var data = google.visualization.arrayToDataTable([
		  ['Town', 'Angle'],
		  ['Burlington', 3],
		  ['Woburn', 2],
		  ['Winchester', 2],
		  ['Arlington', 2],
		  ['Belmont', 2],
		  ['Waltham', 4],
		  ['Lincoln', 4],
		  ['Concord', 2],
		  ['Bedford', 3]
		]);

		var options = {
		  backgroundColor: '<?php echo $lexington; ?>',
		  pieHole: 0.4,
		  legend: 'none',
		  pieSliceText: 'label',
		  tooltip: {textStyle: {color: '#FF0000'}, showColorCode: false, isHTML: true, text: 'value', trigger: 'none'},
		  slices: {
			0: { color: "<?php echo (strpos(strtolower($statuses[0]), 'closed') !== false ? 'red': 'green'); ?>" },
			1: { color: "<?php echo (strpos(strtolower($statuses[1]), 'closed') !== false ? 'red': 'green'); ?>" },
			2: { color: "<?php echo (strpos(strtolower($statuses[2]), 'closed') !== false ? 'red': 'green'); ?>" },
			3: { color: "<?php echo (strpos(strtolower($statuses[3]), 'closed') !== false ? 'red': 'green'); ?>" },
			4: { color: "<?php echo (strpos(strtolower($statuses[4]), 'closed') !== false ? 'red': 'green'); ?>" },
			5: { color: "<?php echo (strpos(strtolower($statuses[5]), 'closed') !== false ? 'red': 'green'); ?>" },
			6: { color: "<?php echo (strpos(strtolower($statuses[6]), 'closed') !== false ? 'red': 'green'); ?>" },
			7: { color: "<?php echo (strpos(strtolower($statuses[7]), 'closed') !== false ? 'red': 'green'); ?>" },
			8: { color: "<?php echo (strpos(strtolower($statuses[8]), 'closed') !== false ? 'red': 'green'); ?>" }
		  },
		  chartArea:{width:'90%',height:'90%'}
		};

		var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
		chart.draw(data, options);
  		}
	</script>
	<?php if($closed){echo('<script type="text/javascript" src="snowstorm.js"></script>');} ?>
	<style>
		html, body {
			margin: 0;
			padding: 0;
		}
		.container {
		position: absolute;
		top: 50%;
		left: 50%;
		-moz-transform: translateX(-50%) translateY(-50%);
		-webkit-transform: translateX(-50%) translateY(-50%);
		transform: translateX(-50%) translateY(-50%);
		z-index: 10000;
		font-size: 36px;
		font-family: Georgia, serif;
	}
	</style>
	<meta http-equiv="refresh" content="300">
	<title>Is there school?</title>
</head>
<body>
	<div class="container">
		<span>Lexington<?php if($closed) { echo('!');} ?></span>
	</div>
	<div id="donutchart" style="width: 100%; height: 100%;"></div>
</body>
</html>
