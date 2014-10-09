<?php

class Alarm {

    /**
     * @var monitor_rule
     */
    public $rule;

    /**
     * @var AlertDeployRule
     */
    public $alert_deploy_rule;

    public function __construct($rule) {
        $this->rule = $rule;
        $this->alert_deploy_rule = new AlertDeployRule();
    }

    public function getAlertReceiver() {
        $receivers_arr = array(
            'mail' => array(),
            'msg' => array(),
            'url' => array(),
        );
        $alert_deploy = $this->rule->alert_deploy;
//		$receivers = $alert_deploy->receiver;
        if ($alert_deploy != NULL) {
//			foreach ($receivers as $receiver){
//				if( !empty($receiver->rule)){
//						if( ! $this->alert_deploy_rule->check($receiver->rule) )
//							continue;
//				}
//				if ( !isset($receivers_arr[$receiver->type])) $receivers_arr[$receiver->type] = array();
//				array_push($receivers_arr[$receiver->type],$receiver->receiver);
//			}

            if (!empty($alert_deploy->rule)) {
                if (!$this->alert_deploy_rule->check($alert_deploy->rule)) {
                    $receivers_arr['mail'] = implode(',', $alert_deploy->mail_receiver);
                    $receivers_arr['msg'] = implode(',', $alert_deploy->message_receiver);
                    $receivers_arr['url'] = implode(',', $alert_deploy->url_receiver);
                }
            }

        }
        return $receivers_arr;
    }

    public function oneMail($alert_data) {
        var_dump($alert_data);
        $receiver = $this->getAlertReceiver();
        $alert_key = $alert_data[0];
        $title = $this->getData($this->rule->alert_title, $alert_data, key($alert_key));
        $contents = array();
        foreach ($alert_data[0] as $key => $value) {
            $contents[] = $this->getData($this->rule->alert_content, $alert_data, $key);
        }
// 		var_dump( $receiver );
        $mail_content = <<<EOT
<style>
table, td {
 border:1px solid #ccc;
 border-collapse:collapse;
 background-color:#F2FAFE;
}
table, td {
 padding:4px;
}
 table tr td{
 font-size:13px;
 font-family:"宋体";
 }
 a img{
	border:0;
 }
</style>
EOT;
        $mail_content .= <<<EOT

<table>
{$this->rule->alert_head}
EOT;
        $mail_content .= implode('',$contents);
$mail_content .= <<<EOT

</table>

EOT;
        echo $mail_content;
        return;
        if (isset($receiver['mail']) && !empty($receiver['mail'])) {
            Mail::send($receiver['mail'], $title, $mail_content);
        }
        if (isset($receiver['msg']) && !empty($receiver['msg'])) {
            Message::send($receiver['msg'], $title);
        }
        if (isset($receiver['url']) && !empty($receiver['url'])) {
            foreach ($receiver['url'] as $url) {
                Request::init()->url($url)->post(array('alert' => $alert_data, 'title' => $title, 'content' => $mail_content));
            }
        }
    }


    public function getData($alert_title, $alert_data, $key) {
        preg_match_all('/\[([^\[\]]+)\]/', $alert_title, $title_expressions);

        if (!empty($title_expressions)) {

            $values = array();
            foreach ($title_expressions[1] as $expression) {
                $child_expression = new ChildExpression($expression);
                $values[] = array(
                    $expression,
                    $child_expression->calc($alert_data, $key),
                );
            }
            foreach ($values as $value) {
                $alert_title = str_replace("[{$value[0]}]", $value[1], $alert_title);
            }

        }

        return $alert_title;
    }

}