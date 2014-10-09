<?php
class ConditionLogicOperator{
	
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
	
	/**
	 *  使用示例:
	 * <code>
	 * $expressions = array(
				1=> new  Expression('$test', '200', '==' ),
				2=> new  Expression('$ip', '{2.2,3.3}', 'in' ),
		);
	 * $c = new ConditionLogicOperator( '1 or 2 ',$expressions);
	 * </code>
	 * 
	 * @param string $condition_logic_operator 表达式逻辑运算式
	 * @param 表达式 $expressions 
	 */
	function __construct($condition_logic_operator,$expressions){
		
		$this->expressions = $expressions;
		
		$this->logic_stack = $this->analyseConditionLogicOperator($condition_logic_operator);
	}
	
	/**
	 * @todo 分析出表达式中的调用栈
	 *  使用示例:
	 * <code>
	 * $ConditionLogicOperator->analyseConditionLogicOperator('1 or 2');
	 * </code>
	 * 
	 *  返回结果:
	 * <code>
	 * 成功返回:
	 *
	 * 失败返回:
	 *
	 * </code>
	 * @param string $condition_logic_operator
	 * @return multitype:multitype:string unknown  multitype:NULL string  multitype:string Ambigous <NULL, string>  multitype:string
	 */
	public function analyseConditionLogicOperator($condition_logic_operator){
		
		$type = null;
		$ele = array(); 
		$stack = array();
		$str = $condition_logic_operator;
		
		for( $i=0; $i<strlen($str); $i++){//挨个遍历表达式
			$char =  $str[$i];
			if( $char >= '0'  && $char <= '9' ){
				
				//如果上一个非数字算符已过，当前产生了数字( 像"(1 and 2)" 中的"(1"这种情况   )。
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