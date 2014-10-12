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
        $chart_id = Yii::app()->request->getParam('chart',0);

        $host = Yii::app()->request->hostInfo;

        if(empty($chart_id)) throw new Exception('NULL chart id');

        $chart  = chart_config::model()->findByPk($chart_id);

        $chart_cycle = $chart->cycle;
        //日志表配置

        $default_etime = time();
        $start_time = $end_time = $default_etime;


        if( $chart->log_type = chart_config::LOG_TYPE_WITH_CYCLE) {
            $default_etime = intval( time()/$chart_cycle) * $chart_cycle;
            //默认起始时间为3个周期，结束时间为当前周期
            $start_time = strtotime( Yii::app()->request->getParam('stime',date('Y-m-d H:i:s',$default_etime-$chart_cycle*3)) );
            $end_time = strtotime( Yii::app()->request->getParam('etime',date('Y-m-d H:i:s',$default_etime)) );
        }


        $current = time();

        //日志的数据库配置


        $arr = array();

        for ($s=$start_time; $s<=$end_time;$s+=$chart_cycle){
            $date = date('Y-m-d H:i:s',$s);
            $arr[$date] = array();

// 			$cycle_time = ceil( ($current - $s)/$chart_cycle);

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

            $expression = new ChildExpression($chart->expression);
            $expression->preloadData($rule_data);

            foreach($rule_data[0]  as $key=>$value){
                $arr[$date][$key] = $expression->calc($rule_data,$key);
            }

        }

        $categories = array();
        $series = array();
        foreach ($arr as $date=>$data ){

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
		$date = date('Y-m-d H:i:s',$time);

		//监控策略数据源

		$expression = new ChildExpression($chart->expression);
		$expression->preloadData($rule_data);
		foreach($rule_data[0]  as $key=>$value){
			if( !empty($key) ){
				$arr[$key] = floatval($expression->calc($rule_data,$key));
			}
		}
		
		$this->render('realtime',array(
				'date'=> $date,
				'data'=>$arr,
		));
	}
}
