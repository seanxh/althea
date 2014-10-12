<?php

class MonitorController extends BaseController {

    public function init() {
    }

    public function actionIndex(){
        $this->render('index');
    }

    /**
     * 监控列表
     */
    public function actionList(){

        $start = Yii::app()->request->getParam('start',1);
        $length = Yii::app()->request->getParam('length',10);

        $db_command = Yii::app()->db->createCommand()->from('monitor_rule')->limit($length,$start);

        $mointor_arr = $db_command->queryAll();

        $count = Yii::app()->db->createCommand()->from('monitor_rule')->select('count(*)')->queryScalar();

        $response_arr = array('status'=>1,'data'=>array());
        $response_arr['data'] =  $mointor_arr;
        $response_arr['recordsTotal'] = $count;
        $response_arr['recordsFiltered'] = $count;

        $this->response($response_arr);
    }

    /**
     * 添加界面
     */
    public function actionAdd() {

        $monitor_id = Yii::app()->request->getParam('id', 0);

        $monitor_data = array();
        if ($monitor_id != 0) {
            $monitor = monitor_rule::model()->findByPk($monitor_id);
            if (!empty($monitor)) {
                $monitor_data = $monitor->attributes;
                $left_condition_arr = array();
                $operator_arr = array();
                $right_condition_arr = array();
                $conditions = $monitor->condition;
                foreach ($conditions as $value) {
                    $left_condition_arr[$value['serial_num']] = $value['left_expression'];
                    $operator_arr[$value['serial_num']] = $value['comparison_operator'];
                    $right_condition_arr[$value['serial_num']] = $value['right_expression'];
                }
                $monitor_data['left_expression'] = $left_condition_arr;
                $monitor_data['operator'] = $operator_arr;
                $monitor_data['right_expression'] = $right_condition_arr;

                $post_param = json_decode($monitor_data['data_url_parameters'], true);
                $post_key = array();
                $post_value = array();
                if (!empty($post_param)) {
                    foreach ($post_param as $key => $value) {
                        $post_key[] = $key;
                        $post_value[] = $value;
                    }
                    $monitor_data['parameter'] = $post_key;
                    $monitor_data['value'] = $post_value;
                }
                unset($monitor_data['data_url_parameters']);
            }
        }

        $this->encodeData($monitor_data);

        //数据库
        $criteria = new CDbCriteria();
        $criteria->select = 'id,dbname';
        $db_arr = database_config::model()->findAll($criteria);
        $db_arr = MatPHP::model($db_arr)->revert('id', 'dbname');

        //报警策略
        $alert_deploy = alert_deploy::model()->findAll();
        $alert_deploy = MatPHP::model($alert_deploy)->revert('id', 'alert_name');

        $this->render('add', array(
            'db' => $db_arr,
            'alert' => $alert_deploy,
            'data' => $monitor_data,
        ));
    }

    public function actionSubmit(){
        try{
            $id = Yii::app()->request->getParam('id',0);
            $monitor_name = Yii::app()->request->getParam('monitor_name','');
            $cycle = Yii::app()->request->getParam('cycle',3600);

            $data_type = Yii::app()->request->getParam('data_type',0);
            $log_type = Yii::app()->request->getParam('log_type','');
            $log_table_name = Yii::app()->request->getParam('log_table_name','');
            $log_time_column = Yii::app()->request->getParam('log_time_column',0);
            $log_cycle = Yii::app()->request->getParam('log_cycle',0);
            $database_id = Yii::app()->request->getParam('database_id',0);
            $select_sql = Yii::app()->request->getParam('select_sql','');
            $data_url = Yii::app()->request->getParam('data_url','');
            $status = Yii::app()->request->getParam('status',1);
            $value = Yii::app()->request->getParam('value',array());
            $parameter = Yii::app()->request->getParam('parameter',array());

            $left_expression = Yii::app()->request->getParam('left_expression',array());
            $operator = Yii::app()->request->getParam('operator',array());
            $right_expression = Yii::app()->request->getParam('right_expression',array());
            $condition_logic_operator = Yii::app()->request->getParam('condition_logic_operator','');

            $alert_deploy_id = Yii::app()->request->getParam('alert_deploy_id','');

            $alert_content = Yii::app()->request->getParam('alert_content','');
            $alert_title = Yii::app()->request->getParam('alert_title','');
            $alert_head = Yii::app()->request->getParam('alert_head','');

            $submit_data = array(
                'monitor_name'=> $monitor_name,
                'cycle'=> $cycle,
                'data_type'=> $data_type,
                'database_id'=> $database_id,
                'log_type'=>$log_type,
                'log_table_name'=>$log_table_name,
                'log_time_column'=>$log_time_column,
                'log_cycle'=>$log_cycle,
                'select_sql'=> $select_sql,
                'data_url'=> $data_url,
                'value'=> $value,
                'parameter'=> $parameter,
                'left_expression'=> $left_expression,
                'operator'=> $operator,
                'right_expression'=> $right_expression,
                'condition_logic_operator'=> $condition_logic_operator,
                'alert_deploy_id'=> $alert_deploy_id,
                'alert_content'=> $alert_content,
                'alert_title'=> $alert_title,
                'alert_head'=> $alert_head,
                'status'=>$status,
            );
            $validator = new RequestValidator($submit_data);
            $validator->length('monitor_name',1,'','名称不能为空！');
            $validator->int('cycle',1,null,'周期必须为数字！');
            $validator->in('data_type',array(0,1),'数据类型只能为mysql或http！');
            if( $data_type == 1){
                $validator->length('data_url',1,'','url不能为空！');
            }else{
                $validator->length('select_sql',1,'','SQL不能为空！');
                $validator->int('database_id',1,null,'数据库需为数字！');
            }
            if( $log_type == 1){
                $validator->length('log_table_name',1,'','表名称不能为空！');
                $validator->length('log_time_column',1,'','时间字段不能为空！');
                $validator->int('log_cycle',1,null,'表周期必须为数字！');
            }
            $validator->int('alert_deploy_id',1,null,'报警不可为空！');
            $validator->length('alert_title',1,'','报警标题不能为空！');
            $validator->length('alert_head',1,'','报警头不能为空！');
            $validator->length('alert_content',1,'','报警内容不能为空！');

            if( empty($id) ){
                monitor_rule::add($submit_data);

                $message = '添加成功';

            }else{
                monitor_rule::renew($id,$submit_data);
                $message = '修改成功';
            }

            $this->response(array(
                'status'=>1,
                'message'=>$message,
            ));

        }catch (RequestValidatorException $e){
            $this->response(array(
               'status'=>0,
                'field'=>$e->field,
                'message'=>$e->getMessage(),
            ));
        }catch (Exception $e){
            $this->response(array(
                'status'=>0,
                'message'=>$e->getMessage(),
            ));
        }
    }

    public function encodeData(&$source){
        if(is_array($source)){
            foreach($source as $key=>&$value){
                if(is_array($value)){
                    $this->encodeData($value);
                }else {
                    $source[$key] = htmlspecialchars($value);
                }
            }
        }else {
            $source = htmlspecialchars(str_replace('"',"'",$source));
        }
    }

}