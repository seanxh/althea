<?php
class Message{
	public static function send($recevier,$title){
		echo 'Message:';
		echo implode(',',  $recevier );
		echo $title;
		echo 'Message end...'."</br>\n";
	}
}