<?php
class Condition {
	
	/**
	 * @var Array[Expression]
	 */
	public $expressions;
	/**
	 * @var RuleData
	 */
	public $rule_data;
	
	/**
	 * 报警条件判断
	 * @param 表达式 $expressions
	 * @param 日志数据 $rule_data
	 */
	function __construct($expressions,$rule_data){
		//子表达式数组
		$this->expressions = $expressions;
		
		$this->rule_data = $rule_data;
	}
	
	public function preload(){
		
		foreach ($this->expressions as $expression){
			$expression->preload($this->rule_data);
		}
		
	}
	
	public function judgeCondition(){
	
		$alert_data = new AlertData($this->rule_data);
		$alert_arr = array();
		
		$condition_logic = new ConditionLogicOperator($key,$this->rule_data,$this->expressions);
		
		
		foreach( $this->rule_data[0] as $key=>$value){
			$arr = $condition_logic->logic_stack;
// 			$condition_logic->pp();
			$stack2 = array();
			
			$cache = array();
			
			
			while( $operator =array_shift($arr) ){
				switch ($operator[0]){
					case ConditionLogicOperator::EXPRESSION_NUM:
						if( !isset($cache[$operator[1]])){
// 							$cache[ $operator[1] ] = array(ConditionLogicOperator::LOGIC, $operator[1] == '1' );
							$cache[$operator[1]] = array(ConditionLogicOperator::LOGIC, $this->expressions[$operator[1]]->bool($this->rule_data,$key) );
						}
						$stack2[] = $cache[$operator[1]];
						break;
						
					case ConditionLogicOperator::LOGIC_OPERATOR:
						if( in_array($operator[1],array('and','AND','&','&&')) ) {
							$result = array_pop($stack2);
							if( $result[0] == ConditionLogicOperator::LOGIC  ){
								if( $result[1] === false ){
									$incr = 1;
									while( $ele = array_shift($arr)){
										if($ele[0] == ConditionLogicOperator::RIGHT_BRACKET){
											$incr -- ;
											if($incr == 0)
												break;
										}else if($ele[0] == ConditionLogicOperator::LEFT_BRACKET){
											$incr ++;
										}
									}
									 
									$stack2[] = array(ConditionLogicOperator::LOGIC,false);
								}
								
							}else{
								$stack2[] = $result;
							}
								
						}
							
						if( in_array($operator[1],array('or','OR','|','||')) ) {
							$result = array_pop($stack2);
							if( $result[0] == ConditionLogicOperator::LOGIC  ){
								if( $result[1] === true ){
									$incr = 1;
									while( $ele = array_shift($arr)){
										if($ele[0] == ConditionLogicOperator::RIGHT_BRACKET){
											$incr -- ;
											if($incr == 0)
												break;
										}else if($ele[0] == ConditionLogicOperator::LEFT_BRACKET){
											$incr ++;
										}
									}
						
									$stack2[] = array(ConditionLogicOperator::LOGIC,true);
								}
						
							}else{
								$stack2[] = $result;
							}
							
						}
						
						break;
						
					case ConditionLogicOperator::LEFT_BRACKET:
						break;
						
					case ConditionLogicOperator::RIGHT_BRACKET:
						break;
						
				}
			}
			
			
			if(count($stack2) != 1)
				throw new ConditionLogicOperatorException('condition logic error');
			
			$boolean = $stack2[0][1];
/* 			$condition = $this->rule_data->condition_logic_operator;
		
			$boolean = true;
			foreach ($this->expressions as $expression){
				$expression->bool($this->rule_data,$key) ;
			}
			
			if( count( $this->expressions) ){
				if( $this->expressions[0]->logic == Expression::LOGICAND )
					$boolean = true;
				else
					$boolean = false;
			}
			
			
			foreach ($this->expressions as $expression){
				if( strtolower($expression->logic) == Expression::LOGICAND  ) {
					
					if( !$expression->result ){
						$boolean = false;
						break;
					}
					
				}else if( strtolower($expression->logic) == Expression::LOGICOR  ){
					
					if( $expression->result ){
						$boolean = true;
						break;
					}
					
				}else{
					$boolean = false;
				}
			}
 */			
			if ($boolean ){
				foreach ($this->rule_data as $cycle=>$value){
					if ( !isset($alert_arr[$cycle]) ) {
						$alert_arr[$cycle] = array();
					}
					$alert_arr[$cycle][$key] = $value[$key];
				}
			}
			
		}
		
		foreach ( $alert_arr as $cycle=>$value){
			$alert_data[$cycle] = $value;
		}
		
		
		return $alert_data;
		
	}
	
	
}