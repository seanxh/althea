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
            var charts = this;                
	         setInterval(function() {
	        	 	$.ajax({
				             type: "get",
				             async: false,
				             url: '<?php echo $host.'/'.Yii::app()->baseUrl?>/?r=site/realtime&chart=<?php echo $chart_id;?>',
				             dataType: "jsonp",
				             jsonpCallback:"altheaChart",//自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名，也可以写"?"，jQuery会自动为你处理数据
				             success: function(realData){
                             
                                var categories = charts.xAxis[0].categories;
                                
                                var existed_series_name = {}
                                for(i=0;i < series.length;i++){
                                   existed_series_name[ series[i].name ] = 1; 
                                    if( series[i].data.length > length ){
                                            length= ( series[i].data.length > length ) ? series[i].data.length : length;
                                        }
                                }
                                
                                var new_series_name = {}
                                
                                var new_data = new Array();
                                for(var j=0;j<categories.length;j++)
                                    new_data.push(0);
                                        
                                
                                for(var currentKey in realData.data ){
                                    if( !existed_series_name.hasOwnProperty(currentKey) ){
                                        new_series_name[ currentKey ] = 1;
                                        charts.addSeries({
                                            name : currentKey,
                                            data : new_data},true); 
                                    }
                                }
                                
                                categories.push( realData.date );
                                charts.xAxis[0].setCategories(categories);
                                
                                var shift_point = false;
                                for(i=0;i < series.length;i++){
								        var y = realData.data.hasOwnProperty( series[i].name ) ? realData.data[series[i].name] : 0;
                                        shift_point = series[i].data.length >= <?php echo $maxPoints;?>;
                                        series[i].addPoint(y,false);
                                }
                                
                                
                                if( shift_point ){
                                    var cat_name = categories[0];
                                    var cat;
                                    var data_series = [];
                                    $.each(charts.series, function(sKey, sVal){
                                        var j = sVal.data.length - 1;
                                        var isRemoved = false;
                                        var dt;
                                        while(!isRemoved && j >= 0) {
                                            var dVal = sVal.data[j];
                                            if (dVal.category === cat_name) {
                                                dVal.remove();
                                                 sVal.xIncrement --;
                                                isRemoved = true;
                                            }
                                            j--;
                                        }
                                        
                                        $.each(sVal.data, function(k,v){
                                            v.update({
                                                x: k
                                            });
                                       });
                                        
                                    });
                            
                                    var categories = charts.xAxis[0].categories;
                                    categories.splice( $.inArray(cat_name, categories), 1 );
                                    charts.xAxis[0].setCategories(categories);
                                    
                                }
                                
                                charts.redraw();

				 			},
			             error: function(){
			                 console.log('fail');
			             }
					  });
					   	
		        }, <?php echo $realtimeCycle*1000;?>);
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
