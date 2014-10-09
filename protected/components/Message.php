<?php
class Message{
	public static function send($recevier,$title){
		$str = '-----------------------------------------------------------------------------'."\n";
		$str .=  'Message:'.date('Y-m-d H:i:s')."\n";
		$str .= 'Reciever:'. implode(',', $recevier )."\n";
		$str .= 'Title:'.$title."\n";
		$str .= 'Message end...'."\n";
		$fp = fopen('/tmp/message', 'a+');
		fwrite($fp,$str);  
		try {
			SMS::send($recevier, $title);
		}catch ( Exception $e){
			return false;
		}
		
		return false;
	}
}