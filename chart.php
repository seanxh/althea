<?php

$array = array(
    'status'=>0,
    'data'=>array(
        0=>array(
            'test'=>array('id'=>1,'name'=>'test','create_time'=>'2014-10-01 23:36:25','connection'=>1,),
            'test2'=>array('id'=>2,'name'=>'test2','create_time'=>'2014-10-01 23:51:25','connection'=>10,),
        )
    ),
);

echo json_encode($array);
exit;