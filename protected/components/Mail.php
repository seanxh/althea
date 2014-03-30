<?php
class Mail{
	public static function send($recevier,$title,$content){
		$str = '-----------------------------------------------------------------------------'."\n";
		$str .=  'Mail:'.date('Y-m-d H:i:s')."\n";
		$str .= 'Reciever:'. implode(',', $recevier )."\n";
		$str .= 'Title:'.$title."\n";
		$str .= 'Content:'.$content."\n";
		$str .= 'Mail end...'."\n";
		echo $str;
		$fp = fopen('/tmp/mail', 'a+');
		fwrite($fp,$str);
	}
}