<?php

class ChartController extends BaseController {

    public function init() {
    }


    public function actionIndex(){
        $this->render('index');
    }

    /**
     * 添加界面
     */
    public function actionAdd() {

        $chart_id = Yii::app()->request->getParam('id', 0);

        $chart_data = array();
        if ($chart_id != 0) {
            $chart = chart_config::model()->findByPk($chart_id);
            if (!empty($chart)) {
                $chart_data = $chart->attributes;

                $post_param = json_decode($chart_data['data_url_parameters'], true);
                $post_key = array();
                $post_value = array();
                if (!empty($post_param)) {
                    foreach ($post_param as $key => $value) {
                        $post_key[] = $key;
                        $post_value[] = $value;
                    }
                    $chart_data['parameter'] = $post_key;
                    $chart_data['value'] = $post_value;
                }
                unset($chart_data['data_url_parameters']);

            }
        }

        $this->encodeData($chart_data);

        //数据库
        $criteria = new CDbCriteria();
        $criteria->select = 'id,dbname';
        $db_arr = database_config::model()->findAll($criteria);
        $db_arr = MatPHP::model($db_arr)->revert('id', 'dbname');

        $this->render('add', array(
            'db' => $db_arr,
            'data' => $chart_data,
        ));
    }

    /**
     * 图表列表
     */
    public function actionList() {

        $start = Yii::app()->request->getParam('start', 1);
        $length = Yii::app()->request->getParam('length', 10);

        $db_command = Yii::app()->db->createCommand()->from('chart_config')->limit($length, $start);

        $chart_arr = $db_command->queryAll();

        $count = Yii::app()->db->createCommand()->from('chart_config')->select('count(*)')->queryScalar();

        $response_arr = array('status' => 1, 'data' => array());
        $response_arr['data'] = $chart_arr;
        $response_arr['recordsTotal'] = $count;
        $response_arr['recordsFiltered'] = $count;

        $this->response($response_arr);
    }

    public function actionSubmit(){
        try{
            $id = Yii::app()->request->getParam('id',0);
            $monitor_name = Yii::app()->request->getParam('name','');
            $expression = Yii::app()->request->getParam('expression','');
            $theme = Yii::app()->request->getParam('theme',0);
            $type = Yii::app()->request->getParam('type',0);
            $realtime = Yii::app()->request->getParam('realtime',0);
            $cycle = intval(Yii::app()->request->getParam('cycle',3600));
            $max_points = Yii::app()->request->getParam('max_points',0);


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


            $title = Yii::app()->request->getParam('title','');
            $sub_title = Yii::app()->request->getParam('sub_title','');
            $y_title = Yii::app()->request->getParam('y_title','');

            $submit_data = array(
                'name'=> $monitor_name,
                'data_type'=> $data_type,
                'log_type'=>$log_type,
                'log_table_name'=>$log_table_name,
                'log_time_column'=>$log_time_column,
                'log_cycle'=>$log_cycle,
                'database_id'=> $database_id,
                'select_sql'=> $select_sql,
                'data_url'=> $data_url,
                'value'=> $value,
                'parameter'=> $parameter,
                'title'=> $title,
                'status'=>$status,
                'expression'=>$expression,
                'sub_title'=> $sub_title,
                'y_title'=>$y_title,
                'theme'=>$theme,
                'type'=>$type,
                'realtime'=>$realtime,
                'cycle'=> $cycle,
                'max_points'=>$max_points,
            );

            $validator = new RequestValidator($submit_data);
            $validator->length('name',1,'','名称不能为空！');
            if( !empty($cycle) ){
                $validator->int('cycle',1,null,'周期必须为数字！');
            }
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
            $validator->length('title',1,'','标题不能为空！');
            if( empty($id) ){
                chart_config::add($submit_data);

                $message = '添加成功';

            }else{
                chart_config::renew($id,$submit_data);
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