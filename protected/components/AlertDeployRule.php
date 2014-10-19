<?php

class  AlertDeployRule {

    function __construct() {

    }


    function check($alert_deploy_rule) {
        //week,1|hour,2
        if (strpos($alert_deploy_rule, '|') !== false) {
            if (strpos($alert_deploy_rule, '&') !== false) return false;
            return $this->check_or($alert_deploy_rule);
        }

        //week,1&hour,2
        if (strpos($alert_deploy_rule, '&') !== false) {
            if (strpos($alert_deploy_rule, '|') !== false) return false;
            return $this->check_and($alert_deploy_rule);
        }
        return $this->check_or($alert_deploy_rule);
    }

    function check_and($alert_deploy_rule) {
        $rules = explode('&', trim($alert_deploy_rule));
        foreach ($rules as $rule) {
            $child_rules = explode('/', trim($rule));
            if (count($child_rules) != 2){
                throw new AlertDeployRuleException($rule.'格式不正确');
            }
            $method_name = trim($child_rules[0]);
            $parameters = trim($child_rules[1]);
            if (!method_exists($this, $method_name)){
                throw new AlertDeployRuleException('不存在验证规则'.$method_name);
            }

            if (!$this->$method_name($parameters)) return false;
        }
        return true;
    }

    function check_or($alert_deploy_rule) {
        $rules = explode('|', trim($alert_deploy_rule));
        foreach ($rules as $rule) {
            $child_rules = explode('/', trim($rule));
            if (count($child_rules) != 2){
                throw new AlertDeployRuleException($rule.'格式不正确');
            }
            $method_name = trim($child_rules[0]);
            $parameters = trim($child_rules[1]);

            if (!method_exists($this, $method_name)){
                throw new AlertDeployRuleException('不存在验证规则'.$method_name);
            }

            if ($this->$method_name($parameters)) return true;
        }
        return false;
    }

    function week($check) {
        $week_day = date('w');
        if ($week_day == 0) $week_day = '7';
        $week_days  = $this->generateArray($check);
        if (!in_array($week_day, $week_days)) return FALSE;

        return true;
    }

    function day($check) {
        $day = date('j');

        $days  = $this->generateArray($check);

        if (!in_array($day, $days)) return FALSE;

        return true;
    }

    function month($check) {
        $month = date('n');

        $monthes = $this->generateArray($check);

        if (!in_array($month, $monthes)) return FALSE;

        return true;
    }

    function hour($check) {
        $hour = date('G');
        $hours = $this->generateArray($check);
        if (!in_array($hour, $hours)) return FALSE;

        return true;
    }

    function generateArray($string) {
        if (strpos($string, '-') === false) {
            $result_arr = explode(',', $string);
        }else{
            $arr = explode('-',$string);
            if( count($arr) != 2){
                throw new AlertDeployRuleException($string.'格式不正确');
            }
            $result_arr = array();
            for($i=intval($arr[0]); $i<=intval($arr[1]) ; $i++){
                $result_arr[] = $i;
            }
        }
        return $result_arr;
    }

}

class AlertDeployRuleException extends  Exception{}