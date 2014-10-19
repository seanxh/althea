<?php
class SiteController extends CController{

    /**
     * 默认的渲染框架
     *
     * @var string
     * @access public
     */
    public $layout = 'null';
	
	public function init(){
	}
	
	public function actionChart(){
        $chart_id = Yii::app()->request->getParam('id', 1);

		$this->render('chart',array(
            'id'=>$chart_id,
        ));
	}
	
	public function actionIndex(){
        try{
            //图表ID
            $chart_id = Yii::app()->request->getParam('chart',0);
            $host = Yii::app()->request->hostInfo;
            if(empty($chart_id)) throw new Exception('图表ID为空');
            if(!is_numeric($chart_id)) throw new Exception('图表ID必须为数字');
            $chart  = chart_config::model()->findByPk($chart_id);
            if(empty($chart)){
                throw new Exception('未知ID，请确认你的ID输入正确.');
            }
            if( $chart->status == chart_config::STATUS_FORBIDDEN){
                throw new Exception('"'.$chart->name.'"图表已经被禁用，请联系管理员.');
            }

            $chart_cycle = $chart->cycle;
            $default_etime = time();
            $start_time = $end_time = $default_etime;


            if( $chart->log_type == chart_config::LOG_TYPE_WITH_CYCLE) {
                $default_etime = intval( time()/$chart_cycle) * $chart_cycle;
                //默认起始时间为3个周期，结束时间为当前周期
                $start_time = strtotime( Yii::app()->request->getParam('stime',date('Y-m-d H:i:s',$default_etime-$chart_cycle*3)) );
                $end_time = strtotime( Yii::app()->request->getParam('etime',date('Y-m-d H:i:s',$default_etime)) );
            }

            $chart_data_arr = array();

            //监控策略数据源
            if( $chart->data_type == chart_config::DATA_TYPE_MYSQL){
                $database = $chart->database;
                $log_dsn = $database->type.':host='.$database->host.';port='.$database->port.';dbname='.$database->dbname;
                $rule_data = new RuleData($log_dsn, $database->user, $database->passwd, $chart);
                Yii::app()->params['CURRENT_TIME'] = $end_time;
                if( $chart->log_type == chart_config::LOG_TYPE_WITH_CYCLE) {
                    $i = 0;
                    for ($s=$end_time; $s<=$start_time;$s-=$chart_cycle){
                        $i++;
                        $rule_data[$i];
                    }
                }else{
                    $rule_data[0];
                }

            }else{
                $params = $chart->data_url_parameters;
                if( empty($params)){
                    $params = '[]';
                }
                $rule_data = new HttpRuleData($chart->data_url, json_decode($params,true), $chart);
            }

            $expression = new ChildExpression($chart->y_expression);
            $expression->preloadData($rule_data);

            $x_expression = new ChildExpression($chart->x_expression);
            $x_expression->preloadData($rule_data);


            $chart_data = $rule_data->getData();
            while(count($chart_data) > 0){
                foreach($chart_data[0]  as $key=>$value){
                    $x_data = $x_expression->calc($chart_data,$key);
                    if(!isset($chart_data_arr[$x_data])){
                        $chart_data_arr[$x_data] = array();
                    }
                    $chart_data_arr[$x_data][$key] = $expression->calc($chart_data,$key);
                }
                array_shift($chart_data);
            }

            $categories = array();
            $series = array();
            foreach ($chart_data_arr as $date=>$data ){
                $categories[] = $date;
                foreach ($data as $key=>$value){
                    if( !isset( $series[ $key ]) )	$series[ $key ]= array();
                    $series[ $key ][] = floatval($value);
                }
            }

            $series_data = array();
            foreach ($series as $key=>$data){
                $series_data[] = array(
                    'name'=>$key,
                    'data'=>$data,
                );
            }

            $this->render('spline',array(
                'chart_id'=>$chart_id,
                'series'=> $series_data,
                'categories'=>$categories,
                'title'=>$chart->title,
                'type'=> chart_config::$CHART[$chart->type],
                'subtitle'=>$chart->subtitle,
                'yAxisTitle'=>$chart->y_title,
                'realtime'=>$chart->realtime,
                'realtimeCycle'=>$chart->cycle,
                'theme'=>chart_config::$THEME[$chart->theme],
                'host'=>$host,
                'maxPoints'=>($chart->max_points>0) ? $chart->max_points : 5,
            ));
        }catch (Exception $e){
            $this->render('error',array(
                'code'=>$e->getCode(),
                'message'=> $e->getMessage(),
            ));

        }

	}
	
	public function actionRealtime(){
		
		$chart_id = Yii::app()->request->getParam('chart',0);
		
		if(empty($chart_id)) throw new Exception('NULL chart id');
		
		$chart  = chart_config::model()->findByPk($chart_id);
		
		$chart_cycle = $chart->cycle;

		//默认起始时间为1个周期，结束时间为当前周期
		$time = intval( time()/$chart_cycle) * $chart_cycle;
		
        //监控策略数据源
        if( $chart->data_type == chart_config::DATA_TYPE_MYSQL){
		    $database = $chart->database;
            $log_dsn = $database->type.':host='.$database->host.';port='.$database->port.';dbname='.$database->dbname;
            $rule_data = new RuleData($log_dsn, $database->user, $database->passwd, $chart);
        }else{
            $params = $chart->data_url_parameters;
            if( empty($params)){
                $params = '[]';
            }
            $rule_data = new HttpRuleData($chart->data_url, json_decode($params,true), $chart);
        }


		$arr = array();
//		$date = date('Y-m-d H:i:s',$time);

        $x_expression = new ChildExpression($chart->x_expression);
        $x_expression->preloadData($rule_data);

        $x_data = 'unkown';
        //监控策略数据源
		$expression = new ChildExpression($chart->y_expression);
		$expression->preloadData($rule_data);
		foreach($rule_data[0]  as $key=>$value){
			if( !empty($key) ){
                $x_data = $x_expression->calc($rule_data,$key);
				$arr[$key] = floatval($expression->calc($rule_data,$key));
			}
		}
		
		$this->render('realtime',array(
				'key'=> $x_data,
				'data'=>$arr,
		));
	}
}
