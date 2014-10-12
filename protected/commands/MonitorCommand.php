<?php

class MonitorCommand extends CConsoleCommand {

    /**
     * 初始化
     * @see CConsoleCommand::init()
     */
    public function init() {
        parent::init();
    }

    /**
     * @param int $monitor_id 监控策略ID
     * 报警入口
     */
    public function actionIndex($id) {

        if (empty($id) || !is_numeric($id)) {
            echo '格式不正确。 usage: yiic monitor --id=1' . "\n";
            return;
        }

        Yii::app()->params['CURRENT_TIME'] = $GLOBALS['CURRENT_TIME'] = $time = time(); // strtotime('2014-03-09 23:19:00');

        $rule = monitor_rule::model()->findByPk($id);

        if ($rule->status != monitor_rule::STATUS_NORMAL) {
            echo 'id:' . $id . '监控的状态不为正常运行' . "\n";
            return;
        }

//        $log_config = NULL;
//        if ($rule->log_id != 0) {
//            //报警策略的日志配置
//            $log_config = $rule->log_config;
//        }

        //日志的数据库配置
        $database = $rule->database;

        //等待本周期的数据入库
        sleep($rule->wait_time);

        $log_dsn = $database->type . ':host=' . $database->host . ';port=' . $database->port . ';dbname=' . $database->dbname;

        //监控策略数据源

        if( $rule->data_type == monitor_rule::DATA_TYPE_MYSQL){
            $rule_data = new RuleData($log_dsn, $database->user, $database->passwd, $rule);
        }else{
            $params = $rule->data_url_parameters;
            if( empty($params)){
                $params = '[]';
            }
            $rule_data = new HttpRuleData($rule->data_url, json_decode($params,true), $rule);
        }

        //监控策略条件表达式(一个监控策略有可能有多个表达式，多个表达式可能是逻辑“或”，“与”的关系
        $condition = $rule->condition;
        $expressions = array();

        foreach ($condition as $con) {
            $expression = array();
            //表达式比较运算符
            $expression['compare'] = $con->comparison_operator;
            //依次取出左式和右式
            $expression['left'] = $con->left_expression;
            $expression['right'] = $con->right_expression;

            $expressions[$con->serial_num] = new Expression($expression['left'], $expression['right'], $expression['compare']);
        }
        $condition = new Condition($expressions, $rule->condition_logic_operator);
        $condition->preload($rule_data);
        $alert_data = $condition->judgeCondition($rule_data);
// 		echo "------------------------------------------------\n";
        if (count($alert_data) > 0) {
            $alarm = new Alarm($rule);
            $alarm->oneMail($alert_data);
        }


    }

} 