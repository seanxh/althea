<?php

class  FunctionsStack{
	
	const OPERATOR = 'operator';//操作符
	const FUNCTIONS = 'function';//函数名
	const INTEGER = 'integer';//整型字符
	const VARIABLE = 'variable';//变量
	const BRACKET = 'bracket'; //括号
	const PENDING = 'pending';//未决
	const STRING = 'string';//字符串
	const ARRAYS = 'array';//数组（类似函数名)
	const ARRAYVAL = 'array_value';//数组值。变量
	
	public static $FUNC_PRELOAD = array(
			'prev' => array('group',array(1,1)), // 需要prev的函数名=>preload函数,需要取原函数的第几个参数作为preload的参数，如果为数组，可以指定默认值
			'prevHour'=>array('groupHour',array(1,1)),
	);
	
	private $_stack=array();
	
	public $PROCESS_CLASS = array(
		'Method',
		'AlertDeploy',
	) ;
	
	public function __construct(){
		
	}
	
	public function __toString(){
		$str = '';
		foreach($this->_stack as $stack){
			$str .= $stack[0].':'.$stack[1]."\n";
		}
		return $str;
	}
	
	public function get(){
		return $this->_stack;
	}
	
	function push($type,$value){
		/* if($type == self::FUNCTIONS && $value == self::ARRAYS){
			$type = self::ARRAYS;
		} */
		$this->_stack[] = array($type,$value);
	}
	
	function pop(){
		return array_pop($this->_stack);
	}
	
	function getValue($rule_data,$key,$type='Method'){
		
		$method = new $type($rule_data, $key);
		
		/*
		 * bracket:)
		 * integer:9090
		 * integer:8080
		 * array:array
		 * bracket:(
		 */
		$function_stack = $this->_stack;
		
		$stack2 = array();
		while( count( $function_stack ) > 0){
			$element =  array_pop($function_stack);
			switch($element[0] ){
				case self::BRACKET:
					if ($element[1] == ')'){
						array_push($stack2, $element);
					}else{//碰到(，把函数栈中的push出来，计算重新入栈
						$func_ele_stack = array();
						while( count($stack2) >0){
							$ele = array_pop($stack2);
							if( $ele[1] !=  ')'  ){
								array_push($func_ele_stack, $ele);
							}else{
								break;
							}
						}
						switch ($func_ele_stack[0][0]){
							case self::FUNCTIONS:
								$function_name = array_shift($func_ele_stack);
								$function_name = $function_name[1];
								$params = array();
								foreach ($func_ele_stack as $parameter){
									$params[] = $parameter[1];
								}
								$val = call_user_func_array(array($method,$function_name), $params);
								if( is_int($val) || is_float($val)){
									array_push($stack2 , array(FunctionsStack::INTEGER,$val) );
								}else if(is_string($val)){
									array_push($stack2 , array(FunctionsStack::STRING,$val) );
								}else if(is_array($val)){
									array_push($stack2 , array(FunctionsStack::ARRAYVAL,$val) );
								}else{
									throw new Exception(get_class($method).':'.$function_name.' return value type error[int,float,string,array]');
								}
								
								break;
							default:
								break;
						}
						
					}
					break;
				case self::VARIABLE:
					
					$val = call_user_func_array(array($method,'getVal'), array($element[1]) );
					
					if( is_int($val) || is_float($val)){
						array_push($stack2 , array(FunctionsStack::INTEGER,$val) );
					}else if(is_string($val)){
						array_push($stack2 , array(FunctionsStack::STRING,$val) );
					}else if(is_array($val)){
						array_push($stack2 , array(FunctionsStack::ARRAYVAL,$val) );
					}
					break;
				case self::INTEGER:
				case self::STRING:
				case self::FUNCTIONS:
				case self::ARRAYS:
					array_push($stack2, $element);
					break;
			}
			
		}
		
		if( count($stack2) == 1){
			$current_satck  = current($stack2);;
			if( is_array($current_satck) ){
					return $current_satck[1];
			}
			return $current_satck;
		}else{
			throw new Exception('calc error');
			return false;
		}
		
	}

    /**
     * 分析函数调用栈
     * @param string $str
     * @return FunctionsStack
     */
    function analyseFuncStack($str){
        $func_stack = new FunctionsStack();

        //首先将{}替换为array()的格式
        $element = array();
        $type = null;


        for($i=0;$i<strlen($str);$i++){//挨个遍历函数调用表达式
            $char =  $str[$i];
            if( $char >= '0'  && $char <= '9' ){
                $element[] = $char;
                if($type == null)//如果以数字开头，且之前没有被定义类型，则为一个整型数字的开头
                    $type = self::INTEGER;
                continue;
            }else if( ($char >= 'A' && $char <= 'Z') || ($char>='a' && $char <= 'z')){
                $element[] = $char;
                if($type == null)//如果是以字母开头，则有可能是函数名也有可能是字符串
                    $type = self::PENDING;
                else if($type == self::INTEGER)//如果整数中包含除数字外的字符，则为字符串
                    $type = self::STRING;
                continue;
            }else if($char == '$'){
                $element[] = $char;
                if($type == null)//如果以$符开头，则为变量
                    $type = self::VARIABLE;
                else if($type == self::INTEGER)//如果整数中包含除数字外的字符，则为字符串
                    $type = self::STRING;
                continue;
            }else if($char == ','){
// 				$element[] = $char;
                if(!empty($element)){//如果是逗号，且之前处于未决状态，则应该是一个字符串。类似array(abc,ccc)异或prev(abc,addd)
                    if($type == self::PENDING)
                        $type = self::STRING;
                    else if($type == self::INTEGER)//如果整数中包含除数字外的字符，则为字符串
                        $type = self::STRING;
                    $func_stack->push( $type,implode('', $element) );
                    $element = array();
                    $type = null;
                }
                continue;
            }else if($char == ':' || $char=='_' || $char=='.'){//如果碰到以下字符，直接入栈
                $element[] = $char;
                continue;
            }else if( $char=='('){//如果碰到(，则当前type肯定为function
                $type = self::FUNCTIONS;
                if(!empty($element)){
                    $func_stack->push( self::BRACKET, '(' );
                    $func_stack->push($type ,implode('', $element) );
                    $element = array();
                    $type = null;
                }
            }else if($char == ')'){
                if($type == self::PENDING){//如果碰到)，且之前处于未决状态，则应该是一个字符串。
                    $type = self::STRING;
                }
                if(!empty($element)){//如果)之前的element不为空，把element都弹出入 function stack，再将括号入栈
                    $func_stack->push($type ,implode('', $element) );
                    $func_stack->push( self::BRACKET, ')' );
                    $element = array();
                    $type = null;
                }else{
                    $func_stack->push( self::BRACKET, ')' );
                }
            }

            /* 			switch ($char){
                            case '('://如果碰到(，则当前type肯定为function

                                break;
                            case ')':

                                break;
                        } */

        }

        /* foreach ($func_stack->get() as $k=>$v){
                echo $v[0].':' . $v[1]."\n";
            } */
        return $func_stack;
    }
	
}