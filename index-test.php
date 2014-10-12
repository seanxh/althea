<?php
/**
 * This is the bootstrap file for test application.
 * This file should be removed when the application is deployed for production.
 */


$array = array(
    'status'=>0,
    'data'=>array(
        0=>array(
            0=>array('id'=>1,'user'=>1,'create_time'=>'2014-10-01 23:36:25','status'=>1,),
            1=>array('id'=>2,'user'=>2,'create_time'=>'2014-10-01 23:51:25','status'=>1,),
        ),
    ),
);

echo json_encode($array);
exit;