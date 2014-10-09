<?php

class DbController extends BaseController {

    public function init() {
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionAdd() {
        $db_id = Yii::app()->request->getParam('id', 0);

        $db_data = array();
        if ($db_id != 0) {
            $database = database_config::model()->findByPk($db_id);
            if (!empty($database)) {
                $db_data = $database->attributes;
            }
        }
        $this->render('add', array(
            'data' => $db_data,
        ));
    }

    /**
     * 数据库列表
     */
    public function actionList() {

        $start = Yii::app()->request->getParam('start', 1);
        $length = Yii::app()->request->getParam('length', 10);

        $db_command = Yii::app()->db->createCommand()->from('database_config')->limit($length, $start);

        $db_arr = $db_command->queryAll();

        $count = Yii::app()->db->createCommand()->from('database_config')->select('count(*)')->queryScalar();

        $response_arr = array('status' => 1, 'data' => array());
        $response_arr['data'] = $db_arr;
        $response_arr['recordsTotal'] = $count;
        $response_arr['recordsFiltered'] = $count;

        $this->response($response_arr);

    }

    public function actionSubmit() {
        try {
            $id = Yii::app()->request->getParam('id', 0);
            $name = Yii::app()->request->getParam('name', '');
            $dbname = Yii::app()->request->getParam('dbname', '');
            $host = Yii::app()->request->getParam('host', '');
            $port = Yii::app()->request->getParam('port', '');
            $user = Yii::app()->request->getParam('user', '');
            $passwd = Yii::app()->request->getParam('passwd', '');

            $data_arr = array(
                'name'=>$name,
                'dbname'=>$dbname,
                'host'=>$host,
                'port'=>$port,
                'user'=>$user,
                'passwd'=>$passwd,
            );

            $validator = new RequestValidator($data_arr);
            $validator->length('name',1,'','数据库代号不能为空！');
            $validator->length('dbname',1,'','数据库名称不能为空！');
            $validator->length('host',1,'','host不能为空！');
            $validator->length('port',1,'','端口不能为空！');
            $validator->int('port',1,'','端口必须为数字！');
            $validator->length('user',1,'','用户不能为空！');

            if( !empty($id) ){
                $db = database_config::model()->findByPk($id);
                if( empty($db) ){
                    throw new Exception('未知的监控ID，请不要手动修改表单隐藏字段！');
                }
                $message = '修改成功';
            }else{
                $db = new database_config();
                $message = '添加成功';
            }

             foreach($data_arr as $key=>$value){
                 $db->setAttribute($key,$value);
             }
             $db->save();

            if( $db->hasErrors()){
                $errors = $db->getErrors();
                $key = key($errors);
                throw new Exception( $key.':'.implode(',',$errors[$key]));
            }


            $this->response(array(
                'status'=>1,
                'message'=>$message,
            ));

        } catch (RequestValidatorException $e) {
            $this->response(array(
                'status' => 0,
                'field' => $e->field,
                'message' => $e->getMessage(),
            ));
        } catch (Exception $e) {
            $this->response(array(
                'status' => 0,
                'message' => $e->getMessage(),
            ));
        }
    }
}