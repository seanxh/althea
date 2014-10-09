<?php

/**
 * This is the model class for table "budget_unit".
 *
 * The followings are the available columns in table 'budget_unit':
 * @property integer $id
 * @property string $log_id
 * @property string $monitor_name
 * @property string $select_sql
 * @property string $condition_logic_operator
 * @property int $is_alert_everytime
 * @property int $alert_in_cycles
 * @property int $alert_when_gt_times
 * @property string $alert_title
 * @property string $alert_head
 * @property string $alert_content
 * @property int $alert_deploy_id
 * @property int $wait_time
 * @property int $status
 *
 * @property log_config $log_config
 * @property monitor_condition $condition
 * @property alert_deploy $alert_deploy
 * @property monitor_rule_join $rule_join
 */
class monitor_rule extends CActiveRecord {
    static $DATA_TYPE = array(
        0 => 'MySQL',
        1 => 'http',
    );

    static $STATUS = array(
        0 => '禁用',
        1 => '启用',
    );

    const STATUS_FORBIDDEN = 0;
    const STATUS_NORMAL = 1;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return budget_unit the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'monitor_rule';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('monitor_name,select_sql,alert_title,alert_head,alert_content,alert_deploy_id,cycle,data_type', 'required'),
            array('log_id,monitor_name,select_sql,is_alert_everytime,alert_in_cycles,alert_when_gt_times,alert_title,alert_head,alert_content,alert_deploy_id,wait_time,status,cycle,condition_logic_operator,data_type,data_url,data_url_parameters,database_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('log_id,monitor_name,select_sql,is_alert_everytime,alert_in_cycles,alert_when_gt_times,alert_title,alert_head,alert_content,alert_deploy_id,wait_time,status,cycle,condition_logic_operator,data_type,data_url,data_url_parameters,database_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'log_config' => array(self::BELONGS_TO, 'log_config', 'log_id'),
            'condition' => array(self::HAS_MANY, 'monitor_condition', 'rule_id'),
            'alert_deploy' => array(self::BELONGS_TO, 'alert_deploy', 'alert_deploy_id'),
            'database'	=> array(self::BELONGS_TO, 'database_config', 'database_id'),
// 			'rule_join'=>array(self::HAS_MANY,'monitor_rule_join','rule_id'),
        );
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'log_name' => '日志名称',
            'table_name' => '日志表名称',
            'database_id' => '数据库',
            'time_column' => '时间字段',
            'log_cycle' => '日志周期(秒)',
            'log_id' => '日志ID',
            'monitor_name' => '监控名称',
            'select_sql' => '指定查询SQL',
            'condition_logic_operator' => '表达式逻辑运算',
            'is_alert_everytime' => '是否每次',
            'alert_in_cycles' => '在N个周期内监控',
            'alert_when_gt_times' => '当异常大于N次时报警',
            'alert_title' => '报警标题',
            'alert_head' => '报警内容表头',
            'alert_content' => '报警内容',
            'alert_deploy_id' => '报警接收人配置',
            'wait_time' => '等待时间',
            'status' => '监控状态',
        );
    }

    public static function add($arr) {
        $monitor_rule = new monitor_rule();
        $new_attributes = self::mixRule($arr);

        foreach($new_attributes as $key=>$value){
            $monitor_rule->setAttribute($key,$value);
        }
        $id = $monitor_rule->save();
        if( !$id ){
            if( $monitor_rule->hasErrors()){
                $errors = $monitor_rule->getErrors();
                $key = key($errors);
                throw new Exception( $key.':'.$errors[$key]);
            }else{
                throw new Exception('添加监控策略失败');
            }

        }
        $parameters_arr= self::mixOperator($id,$arr);
        monitor_condition::renew($id,$parameters_arr);

    }

    public static function mixRule($arr){


        $parameters_arr = array();
        foreach($arr['parameter'] as $key=>$value){
            $parameters_arr[$value] = $arr['value'][$key];
        }

        $arr['data_url_parameters'] = json_encode($parameters_arr);


        $new_attributes = array(
            'monitor_name' => $arr['monitor_name'],
            'cycle' => $arr['cycle'],
            'data_type' => $arr['data_type'],
            'database_id' => $arr['database_id'],
            'select_sql' => $arr['select_sql'],
            'data_url' => $arr['data_url'],
            'data_url_parameters' => $arr['data_url_parameters'],
            'alert_deploy_id' => $arr['alert_deploy_id'],
            'alert_content' => $arr['alert_content'],
            'alert_title' => $arr['alert_title'],
            'alert_head' => $arr['alert_head'],
            'status' => $arr['status'],
        );

        return $new_attributes;

    }

    public static function mixOperator($id,$arr){
        $parameters_arr = array();
        foreach($arr['operator'] as $key=>$value){
            $parameters_arr[] = array(
                'rule_id'=>$id,
                'comparison_operator'=>$arr['operator'][$key],
                'left_expression'=>$arr['left_expression'][$key],
                'right_expression'=>$arr['right_expression'][$key],
                'serial_num'=>$key,
            );
        }

        return $parameters_arr;
    }


    public static function renew($id, $arr) {
        $monitor_rule = self::model()->findByPk($id);
        if (empty($monitor_rule)) {
            throw new Exception('未知的监控ID，请不要手动修改表单隐藏字段！');
        }


        $new_attributes = self::mixRule($arr);

        $parameters_arr= self::mixOperator($id,$arr);

        monitor_condition::renew($id,$parameters_arr);

        foreach($new_attributes as $key=>$value){
            $monitor_rule->setAttribute($key,$value);
        }
        $monitor_rule->save();


    }

}// end class