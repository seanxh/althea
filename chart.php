<?php
date_default_timezone_set('Asia/Shanghai');
$array = array(
    'status'=>0,
    'data'=>array(
        0=>array(
            'test'=>array('id'=>1,'name'=>'test','create_time'=>date('Y-m-d H:i:s'),'connection'=>1,),
            'test2'=>array('id'=>2,'name'=>'test2','create_time'=>date('Y-m-d H:i:s'),'connection'=>10,),
        ),
        1=>array(
            'test'=>array('id'=>1,'name'=>'test','create_time'=>date('Y-m-d H:i:s',time()-10),'connection'=>1,),
            'test2'=>array('id'=>2,'name'=>'test2','create_time'=>date('Y-m-d H:i:s',time()-10),'connection'=>10,),
        ),
        2=>array(
            'test'=>array('id'=>1,'name'=>'test','create_time'=>date('Y-m-d H:i:s',time()-20),'connection'=>3,),
            'test2'=>array('id'=>2,'name'=>'test2','create_time'=>date('Y-m-d H:i:s',time()-20),'connection'=>20,),
        )
    ),
);

echo json_encode($array);
exit;