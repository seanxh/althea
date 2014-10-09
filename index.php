<?php
// change the following paths if necessary
error_reporting(E_ALL);
define('ENV', 'dev'); // 开发环境w
date_default_timezone_set('Asia/Shanghai');
$yii=dirname(__FILE__).'/Yii/framework/yii.php';

$config=dirname(__FILE__).'/protected/config/main.php';
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

$GLOBALS['CURRENT_TIME'] = time();

require_once($yii);
Yii::createWebApplication($config)->run();