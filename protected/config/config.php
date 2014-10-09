<?php
// ============== 常量定义 ==================//

$config_file = dirname(__FILE__).'/config.ini';
$ini_array = parse_ini_file($config_file);



$array_check = array('HOST','PORT','DBNAME','USER','PASSWD');
foreach($array_check as $value){
    if( !isset($ini_array[$value]) ){
        die('Config file('.$config_file.') missed config item "'.$value.'"');
    }
}

$con_string = "mysql:host={$ini_array['HOST']};port={$ini_array['PORT']};dbname={$ini_array['DBNAME']}";
$user_string = $ini_array['USER'];
$pw_string = $ini_array['PASSWD'];

define('DB_CON_STRING', $con_string);
define('DB_USER_STRING', $user_string);
define('DB_PW_STRING', $pw_string);
