<?php
	$cbspage = file_get_contents('http://boston.cbslocal.com/closings/');
	$lines = explode("\n", $cbspage);
	
	$good_lines = preg_grep ( "<div class=\"search-wrapper\">", $lines );
	
	$good_line = array_pop(array_reverse($good_lines));
	
	
	
	preg_match_all("/<div class=\"business\"><p class=\"name\">(.*?)<\/p><p class=\"county\">(.*?)<\/p><p class=\"category\">(.*?)<\/p><\/div><p class=\"status red\">(.*?)<\/p><\/div>/", $good_line, $regex_trash);
	
	$actual_array = array_pop(array_reverse($regex_trash));
	
	//var_dump( $actual_array );
	
	$statuses = array();
	$statuses[0] = "Open";
	$statuses[1] = "Open";
	$statuses[2] = "Open";
	$statuses[3] = "Open";
	$statuses[4] = "Open";
	$statuses[5] = "Open";
	$statuses[6] = "Open";
	$statuses[7] = "Open";
	
	
	$lexington = 'green';
	
	foreach ($actual_array as $value) {
		// echo $value;
		
		$name = get_string_between( $value, "<p class=\"name\">", "</p><p class=\"county\">");
		$what = get_string_between( $value, "<p class=\"status red\">", "</p></div>");
		
		if ($name == 'Arlington') {
			$statuses[0] = $what;
		}
		if ($name == 'Bedford') {
			$statuses[1] = $what;
		}
		if ($name == 'Belmont') {
			$statuses[2] = $what;
		}
		if ($name == 'Burlington') {
			$statuses[3] = $what;
		}
		if ($name == 'Lincoln') {
			$statuses[4] = $what;
		}
		if ($name == 'Waltham') {
			$statuses[5] = $what;
		}
		if ($name == 'Winchester') {
			$statuses[6] = $what;
		}
		if ($name == 'Woburn') {
			$statuses[7] = $what;
		}
		if ($name == 'Lexington') {
			$lexington = 'red';
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
          ['Task', 'Hours per Day'],
          ['Arlington',     1],
          ['Bedford',      1],
          ['Belmont',  1],
          ['Burlington', 1],
          ['Lincoln', 1],
          ['Waltham', 1],
          ['Winchester', 1],
          ['Woburn',    1]
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
            7: { color: "<?php echo (strpos(strtolower($statuses[7]), 'closed') !== false ? 'red': 'green'); ?>" }
          },
          sliceVisibilityThreshold: 0.125,
          chartArea:{width:'90%',height:'90%'}
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>
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
  </head>
  <body>
  <div class="container">
	    <span>Lexington!</span>
	</div>
    <div id="donutchart" style="width: 100%; height: 100%;"></div>
  </body>
</html>