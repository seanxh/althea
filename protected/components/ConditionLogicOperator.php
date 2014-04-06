<?php
class ConditionLogicOperator{
	
	protected $key;
	
	/**
	 * @var RuleData
	 */
	protected $rule_data;
	
	protected $expressions;
	
	const EXPRESSION_NUM = 'num';
	
	const LOGIC_OPERATOR = 'logic';
	
	const LEFT_BRACKET = 'left';
	
	const RIGHT_BRACKET = 'right';
	
	const LOGIC = 'logics';
	
	public $logic_stack;
	
	function __construct($key,$rule_data,$expressions){
		$this->key = $key;
		
		$this->rule_data = $rule_data;
		
		$this->expressions = $expressions;
		
		$this->logic_stack = $this->analyseConditionLogicOperator($this->rule_data->condition_logic_operator);
	}
	
	public function analyseConditionLogicOperator($condition_logic_operator){
		
		$type = null;
		$ele = array(); 
		$stack = array();
		$str = $condition_logic_operator;
		
		for( $i=0; $i<strlen($str); $i++){//挨个遍历表达式
			$char =  $str[$i];
			if( $char >= '0'  && $char <= '9' ){
				
				if($type !== null && $type != self::EXPRESSION_NUM ){
					$stack[] = array($type,implode('', $ele));
					$ele = array();
					$type = null;
				}
				
				if( $type === null)
					$type = self::EXPRESSION_NUM;
				
// 				if( $type != self::EXPRESSION_NUM)
// 					throw new ConditionLogicOperatorException('monitor rule\'s condition logic operator error');

				$ele[] = $char;
		
			}else if($char ==')' || $char == '('){
				if( !empty($ele) ) {
					$stack[] = array($type,implode('', $ele));
					$ele = array();
					$type = null;
				}
				
				if($char == '(')
					$stack[] = array(self::LEFT_BRACKET,$char);
				else 
					$stack[] = array(self::RIGHT_BRACKET,$char);
				
			}else if( ($char >= 'A' && $char<='Z') || ($char>='a' && $char <= 'z') || $char=='&' || $char=='|'){
				if($type !== null && $type != self::LOGIC_OPERATOR ){
					$stack[] = array($type,implode('', $ele));
					$ele = array();
					$type = null;
				}
				
				if( $type === null)
					$type = self::LOGIC_OPERATOR;
				
				$ele[] = $char;
			}
				
		}
		
		if( !empty($ele) ) {
			$stack[] = array($type,implode('', $ele) );
		}
		
		return $stack;
	}

	public function pp(){
		foreach ($this->logic_stack as $v){
			echo $v[0].':'.$v[1]."\n";
		}
	}
}

class ConditionLogicOperatorException extends Exception{}