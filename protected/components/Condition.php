<?php
/**
 *  $Althea$ Condition.php
 *  
 * @author  Xuhao05(seanxh) 2014-6-7 上午12:25:29
 */
class Condition {
	
	/**
	 * @var Array[Expression]
	 */
	public $expressions;
	/**
	 * @var RuleData
	 */
// 	public $rule_data;
	
	public $condition_logic_operator;
	
	/**
	 * 报警条件判断
	 * @param 表达式 $expressions
	 * @param 日志数据 $rule_data
	 */
	function __construct($expressions,$condition_logic_operator="0"){
		//子表达式数组
		$this->expressions = $expressions;
		
		$this->condition_logic_operator = empty($condition_logic_operator) ? "0" :$condition_logic_operator;
// 		$this->rule_data = $rule_data;
	}
	
	public function preload($rule_data){
		foreach ($this->expressions as $expression){
			$expression->preload($rule_data);
		}
	}
	
	/**
	 * @todo 判断rule_data的当前周期的Key是否符合规则
	 *  使用示例:
	 * <code>
	 * $Condition = new Condition($expressions,'1 or 2');
	 * $logic_stack = array(
	 *	array(ConditionLogicOperator::EXPRESSION_NUM,'1'),
	 *	array(ConditionLogicOperator::LOGIC_OPERATOR,'or'),
	 *	array(ConditionLogicOperator::EXPRESSION_NUM,'2'),
	 *	);
	 * $Condition->judgeRuleData( $logic_stack,$rule_data,0);
	 * $Condition->judgeRuleData( $logic_stack,$rule_data,'api1');
	 * </code>
	 * 
	 *  返回结果:
	 * <code>
	 * 成功返回:
	 * true / false
	 * </code>
	 * 
	 * @param array $logic_stack
	 * @param string $key
	 * @throws ConditionLogicOperatorException
	 */
	public function judgeRuleData($logic_stack,$rule_data,$key){
		$stack2 = array();
		$cache = array();
		while( $operator =array_shift($logic_stack) ){
			//测试逻辑运算符类型，有可能为：数字（1，2，3），左括号，右括号，逻辑运算符（and,AND,,&,&&,or,OR,|,||）
			switch ($operator[0]){
				case ConditionLogicOperator::EXPRESSION_NUM:
					//如果编号为 $operator[1] 的表达式没有计算过，先计算再缓存
					if( !isset($cache[$operator[1]])){
						$cache[$operator[1]] = array(ConditionLogicOperator::LOGIC, $this->expressions[$operator[1]]->bool($rule_data,$key) );
					}
					$stack2[] = $cache[$operator[1]];
					break;
		
				case ConditionLogicOperator::LOGIC_OPERATOR:
					//如果是AND运算符
					if( in_array($operator[1],array('and','AND','&','&&')) ) {
						$result = array_pop($stack2);
						if( $result[0] == ConditionLogicOperator::LOGIC  ){
							if( $result[1] === false ){
								$incr = 1;
								//如果当前结果是false，并且后跟一个AND运算符，把当前括号里的所有值跳过计算
								while( $ele = array_shift($logic_stack)){
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
								//如果当前结果是true，并且后跟一个or运算符，把当前括号里的所有值跳过计算
								while( $ele = array_shift($logic_stack)){
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
// 		var_dump($cache);
		return $stack2[0][1];
	}
	
	public function judgeCondition($rule_data){
		$alert_data = array();
        $alert_data_key  = $this->judge($rule_data);
//        $alert_data_tmp = array();
        //这些key是有问题的
        foreach($rule_data as $cycle =>$data){
            foreach($alert_data_key as $key=>$value){
                if ( !isset($alert_data[$cycle]) ) {
                    $alert_data[$cycle] = array();
                }
                $alert_data[$cycle][$key] = $data[$key];
            }
        }

//        foreach($alert_data_tmp as $key=>$value){
//            $alert_data[$key] = $value;
//        }
        return $alert_data;

	}

    public function judge($rule_data){
        $condition_logic = new ConditionLogicOperator($this->condition_logic_operator,$this->expressions);
        $alert_data_key = array();//有异常的key值
        //取出第一个周期的所有数据，并依次比较判断
        foreach( $rule_data[0] as $key=>$value){

            if( count($this->expressions) > 0 ){
                $boolean = $this->judgeRuleData($condition_logic->logic_stack, $rule_data,$key);
            }else{//如果没有表达试，默认SQL取出的数据都需要报警
                $boolean = true;
            }

            if ($boolean ){
                $alert_data_key[$key] = 1;
            }
        }

        return $alert_data_key;
    }
	
	
}