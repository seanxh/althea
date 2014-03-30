<?php
class MonitorCommand extends CConsoleCommand{
	
	/**
	 * 初始化
	 * @see CConsoleCommand::init()
	 */
	public function init(){
		parent::init();
	}
	
	/**
	 * @param int $monitor_id 监控策略ID
	 * 报警入口 
	 */
	public function actionIndex($monitor_id) {
		
		$GLOBALS['CURRENT_TIME'] = $time =time();// strtotime('2014-03-09 23:19:00');
		
		$rule = monitor_rule::model()->findByPk($monitor_id);
		
		//报警策略的日志配置
		$log_config = $rule->log_config;
		
		//如果是指定周期的日志，则计算周期
		$cycle_time = ($log_config->log_type) ? time() :  intval($time/$log_config->log_cycle)*$log_config->log_cycle;
		
		//日志的数据库配置
		$database = $log_config->database;
		
		//等待本周期的数据入库
 		sleep($rule->wait_time);
		
		$log_dsn = $database->type.':host='.$database->host.';port='.$database->port.';dbname='.$database->dbname;
		
		//监控策略数据源
		$rule_data = new RuleData($log_dsn,$database->user,$database->passwd, 'utf8',$log_config,$rule,$cycle_time);
			
		//监控策略条件表达式(一个监控策略有可能有多个表达式，多个表达式可能是逻辑“或”，“与”的关系
		$condition = $rule->condition;
		
		$expressions = array();
		
		foreach($condition as $con){
			$expression = array();
			//表达式逻辑
			$expression['logic'] = $con->logic_operator;
			//表达式比较运算符
			$expression['compare'] = $con->comparison_operator;
			
			//依次取出左式和右式
			$expression['left'] = $con->left_expression;
			
			$expression['right'] = $con->right_expression;
			
			$expressions[]  = new Expression($expression['left'], $expression['right'], $expression['compare'] , $expression['logic']);
		}
		
		$condition = new Condition($expressions,$rule_data);
		$condition->preload();
		$alert_data = $condition->judgeCondition();
// 		echo "------------------------------------------------\n";

		if( count($alert_data) > 0 ){
			$alarm = new Alarm($rule);
// 			$alarm->multiMail($alert_data);
			$alarm->oneMail($alert_data);
		}
		
		
	}
	
} 