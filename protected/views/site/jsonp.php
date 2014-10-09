altheaChart({
	chart: {
            type: '<?php echo $type;?>'
    },
    
	"title" : {
		text: '<?php echo $title;?>',//标题
		x : -20 //center
	},
	subtitle: {
		 text:  '<?php echo $subtitle;?>', //副标题
		 x: -20
	},
	xAxis: {
		categories:  <?php echo json_encode($categories);?>,
	},
	yAxis: {
		title: {
			text:  '<?php echo $yAxisTitle;?>'  
		},
		plotLines: [{
			value: 0,
			width: 1,
			color:  '#808080',
		}]
	},
	tooltip: {
		valueSuffix: ''
	},
 	legend: {
 		layout: 'vertical',
 		align: 'right',
 		verticalAlign: 'middle',
 		borderWidth: 0
 	},
 	exporting: {                                  
		enabled: false                                                      
	},    
 	series: <?php echo json_encode($series);?>
});