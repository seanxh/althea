<?php
/**
 * Class AlertController
 * @author (SeanXh) 14-10-1 下午1:43
 */
class AlertController extends BaseController {

    public function init() {
    }

    public function actionAdd() {
        $alert_id = Yii::app()->request->getParam('id', 0);

        $alert_data = array();
        if ($alert_id != 0) {
            $alert_deploy = alert_deploy::model()->findByPk($alert_id);
            if (!empty($alert_deploy)) {
                $alert_data = $alert_deploy->attributes;
            }
        }

        $this->render('add',array(
            'data'=>$alert_data,
        ));
    }

    public function actionIndex(){
        $this->render('index');
    }

    public function actionSubmit(){
        try {
            $id = Yii::app()->request->getParam('id', 0);
            $alert_name = Yii::app()->request->getParam('alert_name', '');
            $mail_receiver = Yii::app()->request->getParam('mail_receiver', '');
            $message_receiver = Yii::app()->request->getParam('message_receiver', '');
            $rule = Yii::app()->request->getParam('rule', '');
            $url_receiver = Yii::app()->request->getParam('url_receiver','');

            $data_arr = array(
                'alert_name'=>$alert_name,
                'mail_receiver'=>$mail_receiver,
                'message_receiver'=>$message_receiver,
                'rule'=>$rule,
                'url_receiver'=>$url_receiver
            );

            $validator = new RequestValidator($data_arr);
            $validator->length('alert_name',1,'','数据库代号不能为空！');

            if( !empty($id) ){
                $alert_deploy = alert_deploy::model()->findByPk($id);
                if( empty($alert_deploy) ){
                    throw new Exception('未知的监控ID，请不要手动修改表单隐藏字段！');
                }
                $message = '修改成功';
            }else{
                $alert_deploy = new alert_deploy();
                $message = '添加成功';
            }

            foreach($data_arr as $key=>$value){
                $alert_deploy->setAttribute($key,$value);
            }
            $alert_deploy->save();

            if( $alert_deploy->hasErrors()){
                $errors = $alert_deploy->getErrors();
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

    public  function actionList(){
        $start = Yii::app()->request->getParam('start',1);
        $length = Yii::app()->request->getParam('length',10);

        $db_command = Yii::app()->db->createCommand()->from('alert_deploy')->limit($length,$start);

        $monitor_arr = $db_command->queryAll();

//        $receiver_arr = Yii::app()->db->createCommand()->from('alert_receiver')->where('alert_deploy_id in('.implode(',',MatPHP::model($monitor_arr)->column('id')).')')->queryAll();
//        $response_arr_mail = MatPHP::model($receiver_arr)->equal('type','mail')->grouper('alert_deploy_id')->group('receiver');
//        $response_arr_message = MatPHP::model($receiver_arr)->equal('type','message')->grouper('alert_deploy_id')->group('receiver');
//
//        foreach($monitor_arr as $key=>$value){
//            $value['mail'] = isset($response_arr_mail[$value['id']]) ? implode(',',$response_arr_mail[$value['id']]) : '';
//            $value['message'] = isset($response_arr_message[$value['id']]) ? implode(',',$response_arr_message[$value['id']]) : '';
//            $monitor_arr[$key] = $value;
//        }

        $count = Yii::app()->db->createCommand()->from('alert_deploy')->select('count(*)')->queryScalar();

        $response_arr = array('status'=>1,'data'=>array());
        $response_arr['data'] =  $monitor_arr;
        $response_arr['recordsTotal'] = $count;
        $response_arr['recordsFiltered'] = $count;

        $this->response($response_arr);
    }
}