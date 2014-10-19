<?php

class Operator {

    public $type;
    public $value;
    const OPERATOR = 'operator'; //操作符
    const FUNCTIONS = 'function'; //函数名
    const INTEGER = 'integer'; //整型字符
    const VARIABLE = 'variable'; //变量

    const PENDING = 'pending'; //未决
    const STRING = 'string'; //字符串

    /**
     * @var FunctionsStack
     */
    private $_func_stack = null;

    function __construct($type, $value) {
        $this->type = $type;
        $this->value = $value;

        if ($type == self::FUNCTIONS) {
            if( strpos($value,'(') === false){
                $this->type = self::STRING;
            }else{
                $function_stack = new FunctionsStack();
                $this->_func_stack = $function_stack->analyseFuncStack($this->value);
            }
        }
    }

    function __toString() {
        return strtolower($this->type) . ': ' . $this->value;
    }

    function getValue($rule_data, $key, $func_class = 'Method') {
        switch ($this->type) {
            case self::FUNCTIONS :
// 				echo $this."\n";
// 				echo $this->_func_stack;
                return $this->_func_stack->getValue($rule_data, $key, $func_class);
                break;
            case self::VARIABLE :
                $k = ltrim($this->value, '$');
                return $rule_data[0][$key][$k];
                break;
            case self::INTEGER:
                return $this->value;
                break;
            case self::OPERATOR:
                return $this->value;
                break;
            case self::STRING:
                return $this->value;
                break;
        }
    }

    /*
	function getAlarmValue($user){
		switch ($this->type) {
			case self::FUNCTIONS :
				return $this->_func_stack->getValue($rule_data,$key,$func_class);
				break;
			case self::VARIABLE :
				$k = ltrim( $this->value , '$');
				return $rule_data[0][$key][$k];
				break;
			case self::INTEGER:
				return $this->value;
				break;
			case self::OPERATOR:
				return $this->value;
				break;
		}
	}*/

    function preloadData() {
// 	echo $this->type ."\n";
        if ($this->type == self::FUNCTIONS) {
            $func_stack = $this->_func_stack->get();

            /* foreach ($func_stack as $v){
                 echo $v[0].':'.$v[1]."\n";
            } */

            $arr = $this->checkIsNeedPreload($func_stack);
            if ($arr)
                return $arr;

        }

        return false;
    }

    //检查是否需要preload数据
    function checkIsNeedPreload($arr) {

        $method = new Method(null, '');

        $arr2 = array(); //从function栈pop出来的函数调用
        $flag = false;
        while ($stack = array_pop($arr)) { //依次检查函数是否需要preload，如果需要，break。并拼凑相应参数
            if ($stack['0'] == FunctionsStack::FUNCTIONS && array_key_exists($stack['1'], FunctionsStack::$FUNC_PRELOAD)) {
                $flag = true;
                break;
            }
            array_push($arr2, $stack);
        }

        if (!$flag) return false;

        //函数名
        $func_name = $stack[1];

        /* foreach ($arr2 as $v){
            echo $v[0].':'.$v[1]."\n";
        } */
        $stack2 = array();

        while ($stack = array_shift($arr2)) {
            if ($stack[0] == FunctionsStack::BRACKET) { //如果为括号
                if ($stack[1] == ')') {
                    array_push($stack2, $stack);

                } else if ($stack[1] == '(') { //有子调用

                    $func_ele_stack = array();
                    while (count($stack2) > 0) {
                        $ele = array_pop($stack2);
                        if ($ele[1] != ')') {
                            array_push($func_ele_stack, $ele);
                        } else {
                            break;
                        }
                    }
                    switch ($func_ele_stack[0][0]) {
                        case FunctionsStack::FUNCTIONS:
                            $function_name = array_shift($func_ele_stack);
                            $function_name = $function_name[1];
                            $params = array();
                            foreach ($func_ele_stack as $parameter) {
                                $params[] = $parameter[1];
                            }
                            $val = call_user_func_array(array($method, $function_name), $params);

                            if (is_int($val) || is_float($val)) {
                                array_push($stack2, array(FunctionsStack::INTEGER, $val));
                            } else if (is_string($val)) {
                                array_push($stack2, array(FunctionsStack::STRING, $val));
                            } else if (is_array($val)) {
                                array_push($stack2, array(FunctionsStack::ARRAYVAL, $val));
                            }

                            break;
                        default:
                            break;
                    }

                }

            }

            switch ($stack[0]) {
                case FunctionsStack::INTEGER:
                case FunctionsStack::STRING:
                case FunctionsStack::VARIABLE:
                case FunctionsStack::FUNCTIONS:
                case FunctionsStack::ARRAYS:
                    array_push($stack2, $stack);
                    break;
            }

        }

        array_shift($stack2);

        $params = array();
        foreach ($stack2 as $val)
            array_unshift($params, $val);

        $preload_params = array();
        for ($i = 1; $i < count(FunctionsStack::$FUNC_PRELOAD[$func_name]); $i++) {
            if (is_array(FunctionsStack::$FUNC_PRELOAD[$func_name][$i])) { //此参数提供了默认值

                if (isset($params[FunctionsStack::$FUNC_PRELOAD[$func_name][$i][0]])) {
                    $preload_params[] = $params[FunctionsStack::$FUNC_PRELOAD[$func_name][$i][0]][1];
                } else {
                    $preload_params[] = FunctionsStack::$FUNC_PRELOAD[$func_name][$i][1];
                }

            } else {
                $preload_params[] = $params[FunctionsStack::$FUNC_PRELOAD[$func_name][$i]][1];
            }

        }

        return
            array(
                FunctionsStack::$FUNC_PRELOAD[$func_name][0],
                $preload_params
            );

    }

    /**
     * 估计已经废弃
     * @param unknown $variable
     * @return multitype:string unknown |NULL|unknown|multitype:|number
     */
    function getData($variable) {
        if (is_array($variable) && !empty($variable)) { //主要是分析数组值
            if (is_array($variable[0])) {
                array_shift($variable); //把两端括号pop掉
                array_pop($variable);

                if ($variable[0][0] == FunctionsStack::ARRAYS) { //分析出数组的值
                    array_shift($variable);
                    $arr = array();
                    foreach ($variable as $v) {
                        $exploded = explode(':', $v[1]);
                        if (count($exploded) > 1) {
                            $arr[$exploded[0]] = implode('', array_slice($exploded, 1));
                        } else {
                            $arr[] = $v[1];
                        }
                    }
                    return $arr;
                }

                return null;
            } else {
                return $variable[1];
            }
        } else if (is_array($variable)) {
            return array();
        } else {
            return 1;
        }


    }

}