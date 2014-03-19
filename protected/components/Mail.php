<?php
class Mail{
	public static function send($recevier,$title,$content){
		echo 'Mail:';
		echo implode(',', $recevier );
		echo $title;
		echo $content;
		echo 'Mail end...'."</br>\n";
	}
}