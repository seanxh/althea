<?php
/**
 * Class Request
 * @author (SeanXh) 14-10-2 上午9:46
 */
class Request {

    private $_aConfig = array(
        'timeout' => 0,
        'return_transfer' => 1,
    );


    private $_aRequestData = array();

    private $_sResult = '';

    private $_aRequestInfo = array();

    private $_r;

    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const RESULT_TYPE_JSON = 'json';

    public function __construct(){
        $this->_aConfig['method'] = self::METHOD_GET;
        $this->_aConfig['return_format'] = self::RESULT_TYPE_JSON;
    }

    public function __get($sName) {
        $sGetter = 'get'.$sName;
        if(method_exists($this, $sGetter)) {
            return $this->$sGetter();
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name,$arguments){
        $method = 'set'.ucfirst($name);
        if(method_exists($this, $method)) {
            call_user_func_array(array($this,$method),$arguments);
        }
        return $this;
    }


    public static function init() {
       return new Request();
    }


    public function setUrl($sUrl) {
        $this->_aConfig['url'] = $sUrl;
    }


    public function setMethod($method) {
        if(in_array($method,array(self::METHOD_GET,self::METHOD_POST) )){
            $this->_aConfig['method'] = $method;
        }
    }


    public function setParams($aParams = array()) {
        $this->_aRequestData = $aParams;
    }

    public function expect($sExpectResultType = self::RESULT_TYPE_JSON) {
        $this->_aConfig['return_format'] = $sExpectResultType;
        return $this;
    }


    public function send($aRequestData = array()) {
        $this->_aRequestData = $aRequestData ? $aRequestData : $this->_aRequestData;
        return $this->{$this->_aConfig['method']}($this->_aRequestData);
    }


    public function get($aRequestData = array()) {
        $this->_aConfig['method'] = self::METHOD_GET;
        $this->_aRequestData = $aRequestData ? $aRequestData : $this->_aRequestData;
        $this->_aConfig['url'] = $this->_aConfig['url'].($this->_aRequestData ? '&'.http_build_query($this->_aRequestData) : '');
        return $this->_request()->_format();
    }


    public function post($aRequestData = array()) {
        $this->_aConfig['method'] = self::METHOD_POST;
        $this->_aRequestData = $aRequestData ? $aRequestData : $this->_aRequestData;
        return $this->_request()->_format();
    }


    public function getBody() {
        return $this->_r;
    }

    public function getHttp_code(){
        if( isset($this->_aRequestInfo['http_code']) ){
            return $this->_aRequestInfo['http_code'];
        }
        return NULL;
    }


    private function _format() {
        switch($this->_aConfig['return_format']) {
            case self::RESULT_TYPE_JSON:
                $this->_r = json_decode($this->_sResult, true);
                break;
        }
        return $this->_r;
    }


    private function _request() {
        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_URL, $this->_aConfig['url']);
        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, $this->_aConfig['return_transfer']);
        if( $this->_aConfig['timeout'] ){
            curl_setopt($objCurl, CURLOPT_CONNECTTIMEOUT, $this->_aConfig['timeout']);
        }

        if($this->_aConfig['method'] == self::METHOD_POST) {
            curl_setopt($objCurl, CURLOPT_POST, 1);
            curl_setopt($objCurl, CURLOPT_POSTFIELDS, $this->_aRequestData);
        }

        $this->_sResult = curl_exec($objCurl);
        $this->_aRequestInfo = curl_getinfo($objCurl);
        curl_close($objCurl);

        if($this->_aRequestInfo['http_code'] != '200'){
            throw new Exception("请求{$this->_aConfig['url']}失败,返回:".$this->_sResult);
        }

        return $this;
    }
}
