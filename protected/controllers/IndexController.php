<?php
class IndexController extends BaseController{


    public function init(){
    }

    public function actionIndex(){
        $this->render('index');
    }

}