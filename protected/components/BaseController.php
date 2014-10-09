<?php
/**
 * Class BaseController
 * @author (SeanXh) 14-9-27 上午10:28
 */
class BaseController extends CController {

    /**
     * 默认的渲染框架
     *
     * @var string
     * @access public
     */
    public $layout = 'main';

    public function init() {
        parent::init();
    }

    /*
     * @access public
     * @author (SeanXh) 14-9-27 上午10:29
     * @param mixed $json
     * @return void
     */
    public function response($json){
        $this->layout=false;
        header('Content-type:application/json; charset=utf-8');
        if (is_string($json)) {
            print($json);
        } else {
            print json_encode($json);
        }
    }
}
