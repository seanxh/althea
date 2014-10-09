<?php
class Mail{
	public static function send($recevier,$title,$content){
	
		//log
		$str = '-----------------------------------------------------------------------------'."\n";
		$str .=  'Mail:'.date('Y-m-d H:i:s')."\n";
		$str .= 'Reciever:'. implode(',', $recevier )."\n";
		$str .= 'Title:'.$title."\n";
		$str .= 'Content:'.$content."\n";
		$str .= 'Mail end...'."\n";
		$fp = fopen('/tmp/mail', 'a+');
		try {
			Mailer::send($recevier, $title, $content);
		}catch ( Exception $e){
			$str .= "Result: Failed\n";
			$str .= "Error: ".$e->getMessage()."\n";
			$str .= "Error code: ".$e->getCode()."\n";
			fwrite($fp,$str);
			return false;
		}
		
		$str .= "Result: OK\n";
		fwrite($fp,$str);
		
		return true;
		
	}
}