<?php if(!empty($theme) && is_file( Yii::app()->basePath.'/../assets/highcharts/themes/'.$theme.'.js') ) :?>
<?php include Yii::app()->basePath.'/../assets/highcharts/themes/'.$theme.'.js';?>
<?php endif;?>


altheaChart({
chart: {
	type: '<?php echo $type;?>'<?php if($realtime == 1):?>,                                                 
    animation: Highcharts.svg, // don't animate in old IE               
	events: {
		load: function() {                                              
			// set up the updating of the chart each second             
	       var series = this.series;                                
	         setInterval(function() {
	        	 	$.ajax({
				             type: "get",
				             async: false,
				             url: '<?php echo Yii::app()->baseUrl?>/?r=site/realtime&chart=<?php echo $chart_id;?>',
				             dataType: "jsonp",
				             jsonpCallback:"altheaChart",//自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名，也可以写"?"，jQuery会自动为你处理数据
				             success: function(realData){
				             		time = realData.date
				             		for(i=0;i < series.length;i++){
								        var x = time;         
								        var y = realData.data.hasOwnProperty( series[i].name ) ? realData.data[series[i].name] : 0;                    
								        series[i].addPoint([x, y], true, true);
									}
				 			},
			             error: function(){
			                 console.log('fail');
			             }
					  });
					   	
		        }, 1000); 
        }
	}              
	<?php endif;?>                                                     
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
	credits:{
		enabled:false
	},
 	series: <?php echo json_encode($series);?>
})