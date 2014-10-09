<?php

define('SMS_WSDL', 'http://emp01.baidu.com:8080/smsp/services/SmspOutService?wsdl');
define('SMS_USER', 'zhangleiqiang');
define('SMS_PASSWORD', 'sysatm@f6');
define('SMS_BUSINESS_CODE', '99');

class sendSms {
  public $arg0; //  Array Of smsDTO
}

class smsDTO{
	public $businessCode; // String
	public $msgId; // String
	public $priority; // String
	public $scheduledDate; // String
	public $smsContent; // String
	public $smsDest; // String
}

class sendSmsResponse{
	public $return; // String
}

/**
 * SmsWebService class
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class SmsWebService extends SoapClient {

	private static $classmap = array(
		'sendSms' => 'sendSms',
		'sendSmsResponse' => 'sendSmsResponse',
	);

	private $_header;
	public function SmsWebService($wsdl = SMS_WSDL, $options = array()) {
		foreach(self::$classmap as $key => $value) {
			if(!isset($options['classmap'][$key])) {
				$options['classmap'][$key] = $value;
			}
		}
		$options['trace'] = 1;
		parent::__construct($wsdl, $options);
		$obj = new stdClass();
		$obj->userName = SMS_USER;
		$obj->password = md5(SMS_PASSWORD);

		$header = new SoapHeader("http://ws.webmodule.smsp.iit.baidu.com/", 'AuthenticationToken', $obj);
		$this->_header = $header;
	}

	/**
	 *  
	 *
	 * @param sendSms $parameters
	 * @return sendSmsResponse
	 */
	public function sendSms(sendSms $parameters) {
		return $this->__soapCall('sendSms', array($parameters),       array(
			'uri' => "http://ws.webmodule.smsp.iit.baidu.com/",
			'soapaction' => ''
		), $this->_header
	);
	}

}
class SMS{
	public static function send($mobile,$content,$msgId=0,$businessCode=99,$priority=3){
		/*{{{*/
		$client = new SmsWebService();
		$request = new sendSms();
		$data = new SmsDTO();
		$data->businessCode=$businessCode;
		$data->priority=$priority;
		$data->msgId=$msgId;
		$data->smsContent=$content;
		$data->smsDest=$mobile;
		$request->arg0 = array($data);
		$ret = $client->sendSms($request);
		$result = $ret->return;
		$result = json_decode($result);
		if(!$result->success){
			throw new Exception('发送短信失败:'.$result->message);
		}
		return true;/*}}}*/
	}
}
?>	
