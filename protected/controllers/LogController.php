<?php
class LogController extends CController{
	
	public function init(){
		date_default_timezone_set('Asia/Shanghai');
		header('Content-type: text/html; charset=utf-8');
	}
	
	public function actionAdd(){
		$this->render('add');
	}
}