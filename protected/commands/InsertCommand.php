<?php
class InsertCommand extends CConsoleCommand{
	
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
	public function actionIndex() {
		$logs = array(
				array(
				'queue'=>'test1',
				'ip'=>'1.1.1.1',
				'port'=>'80',
				'cwrite'=>'20000',
				'cread'=>'900000',
				'add_time'=>date('Y-m-d H:i:01'),
				),
		);
		
		$aInsertKeyMap = array();
		$aInsertKey = array('queue','ip','port','cwrite','cread','add_time');
		$sInsertSqlKey = '`'.str_replace(',', '`,`', implode(',',$aInsertKey)).'`';
		
		$sInsertSql = "INSERT INTO mc_status  ({$sInsertSqlKey}) VALUES ";
		
		$aValues = array();
		foreach ($logs as $log){
			$aInsert = array();
			foreach ($aInsertKey as $key){
				isset($aInsertKeyMap[$key]) && $key = $aInsertKeyMap[$key];
				$aInsert[] = is_string($log[$key]) ? "'{$log[$key]}'" : $log[$key];
			}
			$sInsert = '('. implode(',', $aInsert) .')';
			$aValues[]  = $sInsert;
		}
		
		$sInsertSql .= implode(',', $aValues);
		Yii::app()->db->createCommand($sInsertSql)->execute();
	}
	
} 