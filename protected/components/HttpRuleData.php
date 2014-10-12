<?php
/**
 * 监控策略数据源
 * @author (SeanXh) 14-10-10 下午11:55
 */
class HttpRuleData implements ArrayAccess,Iterator,Countable{

    public $rule;
    public $url;
    public $params;


    protected  $_data;

    /**
     *
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param string $charset
     * @param log_config $log_config
     * @param monitor_rule $rule
     * @param int $cycle_timestamp
     */
    public function __construct($url,$params,$rule){
        $this->url = $url;
        $this->rule = $rule;
        if( empty($params) ){
            $params = array();
        }
        $this->params = $this->analyseParams($params);
        $this->doRequest();
    }

    public function doRequest(){
        if( empty($this->params) ){
            $data = Request::init()->url($this->url)->get();
        }else{
            $data = Request::init()->url($this->url)->post($this->params);
        }

        if( $data['status'] != 0){
            $json_params = json_decode($this->params);
            if( isset($data['msg'])){
                throw new Exception("调用{$this->url}:{$json_params}异常,{$data['msg']}");
            }else{
                throw new Exception("调用{$this->url}:{$json_params}异常,未知错误");
            }
        }


        if( !isset($data['data'])){
            $json_params = json_decode($this->params);
            throw new Exception("调用{$this->url}:{$json_params}异常,未包含'data'值");
        }


        $this->_data = $data['data'];

    }


    public function pp(){
        var_dump($this->_data);
    }

    function analyseParams($params){
        foreach($params as $key=>$value){
                preg_match_all('/\[([^\[\]]+)\]/',$value,$expressions);
                if( !empty($expressions)){
                    $replace_array = array();
                    foreach ($expressions[1] as  $expression){
                        $child_expression = new ChildExpression($expression);
                        $replace_array[]  =  array(
                            $expression,
                            $child_expression->calc(array(),''),
                        );
                    }
                    foreach ($replace_array as $replace){
                        $value = str_replace("[{$replace[0]}]", $replace[1], $value);
                    }

                    $params[$key] = $value;
                }
        }
        return $params;
    }


    // Interface实现
    //countable,iterable,arrayaccess实现

    public function count() {
        return count($this->_data);
    }

    function rewind() {
        reset($this->_data);
    }

    function current() {
        return current($this->_data);
    }

    function key() {
        return key($this->_data);
    }

    function next() {
        next($this->_data);
    }

    function valid() {
        return ( $this->current() !== false );
    }

    /**
     * @param offset
     */
    public function offsetExists ($offset) {
        return isset($this->_data[$offset] );
    }

    /**
     * @param offset
     */
    public function offsetGet ($offset) {
        if(!isset( $this->_data[$offset])){
            throw new Exception('未知的数据,$data['.$offset.']');
        }
        return $this->_data[$offset];
    }

    /**
     * @param offset
     * @param value
     */
    public function offsetSet ($offset, $value) {
        $this->_data[$offset] = $value;
    }

    /**
     * @param offset
     */
    public function offsetUnset ($offset) {
        if(isset($this->_data[$offset]))
            unset($this->_data[$offset]);
    }
}