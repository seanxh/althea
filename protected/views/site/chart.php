<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Highcharts Example</title>
		
		<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/assets/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/assets/althea.js"></script>
		<script src="<?php echo Yii::app()->baseUrl?>/assets/highcharts/highcharts.js"></script>

		
		<script type="text/javascript">
			$(function () {
			        Althea.charts({
				        'container' : $('#container'),
				        'chart' : 2 ,
				        'start_time': "2014-03-30 11:55:00",
					    'end_time': "2014-03-30 11:58:00",
			        });
			});
		</script>
	</head>
	<body>
	<div id="container" style="width:800px; height: 500px; margin: 0 auto"></div>
	</body>
</html>
