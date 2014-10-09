<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>创建日志</title>
	<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/assets/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/assets/althea.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/assets/jquery-ui.custom.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl?>/css/althea.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl?>/css/add.css" />
	
</head>

<body class="althea addContent">
	<div class="bodyWrapper">
		<ul class="section">
			<li class="sectionTitle">
				<h2>创建日志</h2>
			</li>
			<li>
				<span class="name">日志名称</span>
				<div class="fillin">
					<input type="text" value="" name="address">
				</div>
			</li>
			<li>
				<span class="name">表名称</span>
				<div class="fillin">
					<input type="text" value="" name="address">
				</div>
			</li>
			<li>
				<span class="name">数据库</span>
				<div class="fillin">
					<select>
					<option>RMS</option>
					</select>
				</div>
			</li>
			<li>
				<span class="name">是否是周期型日志</span>
				<div class="fillin">
					<select>
					<option>是</option>
					<option>否</option>
					</select>
				</div>
			</li>
			<li>
				<span class="name">日志周期</span>
				<div class="fillin">
					<input type="text" value="" name="address">
				</div>
			</li>
			<li class="sectionConfirm">
				<a class="confirmBtn green disabled" href="op_widgetcenter.html">创建</a>
				<a class="confirmBtn white" href="op_widgetcenter.html">取消</a>
			</li>
		</ul>
		
	</div>
	
</body>

</html>