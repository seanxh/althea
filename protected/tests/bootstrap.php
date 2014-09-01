<?php

error_reporting(E_ALL);
define('ENV', 'dev'); // 开发环境

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../../Yii/framework/yii.php';
$config=dirname(__FILE__).'/../config/console.php';

require_once($yiic);

Yii::import('system.test.CTestCase');
Yii::import('system.test.CDbTestCase');
Yii::import('system.test.CWebTestCase');

function arraySimilar($a, $b) {
	// if the indexes don't match, return immediately
	if (count(array_diff_assoc($a, $b))) {
		return false;
	}
	// we know that the indexes, but maybe not values, match.
	// compare the values between the two arrays
	foreach($a as $k => $v) {
		if ($v !== $b[$k]) {
			return false;
		}
	}
	// we have identical indexes, and no unequal values
	return true;
}

$app=Yii::createConsoleApplication($config);
