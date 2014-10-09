<?php

// 包含环境相关的配置项
$config = require dirname(__FILE__) . "/console.php";


// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array_merge($config,array(
    'defaultController' => 'index',
    )
);