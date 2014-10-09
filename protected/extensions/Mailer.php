<?php
/**
 * 邮件发送类
 * 
 * @author reeze <xiaxuhong@baidu.com>
 *
 * @require Swift STMP Libaray
 */
 
class Mailer {
  /**
   * @param $to mixed array or comma seperated string
   * @param $cc mixed comma seperated string
   * @param $from sender email
   * @param $type mime-type
   * @param $attchments attachements
   *        array(
   *          array(
   *            'data' => "you-file-content",
   *            'file_name' => "first_file.doc",
   *            'mime-type' => "application/word"
   *          ),
   *          array()
   *        )
   * @param $options array smtp_host user name etc
   */
	public static function send($to, $subject, $body,$cc='', $from='', $type='text/html', $attchments=array(), $options=array()) {
		require_once dirname(__FILE__).'/Swift-4.0.4/swift_required.php';
	
		if(empty($to)) {
			throw new Exception("缺少收件人信息");
		}

		// 兼容冒号分隔地址
		if(is_string($to)) {
			$to = array_filter(explode(";", $to));
		}
		
		foreach ($to as $key=>$v){
			$to[$key] = $v.'@baidu.com';
		}
		
		$host = isset($options['host']) ? $options['host'] : 'mail1-in.baidu.com';
		$port = isset($options['port']) ? $options['port'] : 25;
		$username = isset($options['username']) ? $options['username'] : 'rms';
		$password = isset($options['password']) ? $options['password'] : 'sysUMP%f3 ';
		$handler_email = isset($options['handler_email']) ? $options['handler_email'] : 'rms@baidu.com';
		
		$transport = Swift_SmtpTransport::newInstance($host, $port)
			->setUsername($username)
			->setPassword($password);
			
		$mailer = Swift_Mailer::newInstance($transport);
			
		$message = Swift_Message::newInstance($subject)
			->setTo($to)
			->setBody($body)
			->setContentType($type);
			
		$message->setCharset('utf-8');
			
		// have attachements
		if(count($attchments)) {
		  foreach($attchments as $attachment) {
		    // add attchment
		    $message->attach(new Swift_Attachment($attachment['data'], $attachment['file_name'], $attachment['mime-type']));
		  }
		}
			 
		
		if(!$from) {
			$message->setFrom(array($handler_email => 'Althea'));
		} 

		if(is_string($cc)) {
			$cc = str_replace('；', ';', $cc);
			$ccs = explode(';', $cc);
			
			$cc_emails = array();
			
			foreach($ccs as $cc_email) {
				$cc_email = trim($cc_email);
				if($cc_email) {
					$cc_emails[] = $cc_email;
				}
			}
			
			$message->setCc($cc_emails);
		}else if(is_array($cc)){
			$message->setCc($cc);
		}
		try{
			return $mailer->send($message);
		}catch(Swift_TransportException $e){
			if(preg_match('/^Expected response code 250 but got code "530"/',$e->getMessage())){
				throw new Exception("发送邮件失败:请检查邮箱密码是否已经过期");
			}
			throw $e;
		}
	}
}
