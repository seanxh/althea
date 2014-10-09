<?php
/**
 * @todo 表达式计算结果测试
 *   $RCSfile$ RuleDataTest.php
 * @author  SeanXh 2014-6-7 上午10:08:08
 */
class RuleDataTest extends CTestCase{

	public $logic;
	
    public function setup(){
    	$this->logic = new ConditionLogicOperator('',null);
    }
    
    public function tearDown(){
    }

    /**
     * 简单测试
     */
	public function testSimple(){
		$expressions = array(
				1=> new  Expression('$test', '200', '==' ),
				2=> new  Expression('$ip', '{2.2,3.3}', 'in' ),
		);
		
		$rule_data = array(
				0=>array(0=>array('test'=>200,'ip'=>'2.2'),1=>array('test'=>300,'ip'=>'3.3')),
		);
		
		$condition = new Condition($expressions,'');
		
		$logic_stack =  $this->logic->analyseConditionLogicOperator('1 and 2 ');
		
		$this->assertTrue( $condition->judgeRuleData( $logic_stack,$rule_data,0) );
		$this->assertFalse( $condition->judgeRuleData( $logic_stack,$rule_data,1) );
	}
	
	/**
	 * 稍复杂的逻辑运算测试
	 */
	public function testNest(){
		$expressions = array(
				1=> new  Expression('$test', '200', '==' ),
				2=> new  Expression('$ip', '{2.2}', 'in' ),
				3=> new  Expression('$test', '300', '==' ),
				4=> new  Expression('$ip', '{3.3}', 'in' ),
		);
	
		$rule_data = array(
				0=>array(0=>array('test'=>200,'ip'=>'2.2'),1=>array('test'=>300,'ip'=>'3.3')),
		);
	
		$condition = new Condition($expressions,'');
	
		$logic_stack =  $this->logic->analyseConditionLogicOperator('(1 and 2) or (3 and 4)');
	
		$this->assertTrue( $condition->judgeRuleData( $logic_stack,$rule_data,0) );
		$this->assertTrue( $condition->judgeRuleData( $logic_stack,$rule_data,1) );
	}
	
	/**
	 * 带有函数运算符的测试
	 */
	public function testFunction(){
		$expressions = array(
				1=> new  Expression('$conn-prev(conn)', '200', '>=' ),
				2=> new  Expression('$ip', '2.2', '==' ),
		);
	
		$rule_data = array(
				0=>array(0=>array('conn'=>2000,'ip'=>'2.2'),1=>array('conn'=>300,'ip'=>'3.3')),
				1=>array(0=>array('conn'=>200,'ip'=>'2.2'),1=>array('conn'=>300,'ip'=>'3.3')),
		);
	
		$condition = new Condition($expressions,'');
	
		$logic_stack =  $this->logic->analyseConditionLogicOperator('1 and 2');
	
		$this->assertTrue( $condition->judgeRuleData( $logic_stack,$rule_data,0) );
		$this->assertFalse( $condition->judgeRuleData( $logic_stack,$rule_data,1) );
	}

    public function testIn(){
        $expressions = array(
            1=> new  Expression('$test', 'array(200,300)', 'in' ),
        );

        $rule_data = array(
            0=>array(0=>array('test'=>200,'ip'=>'2.2'),1=>array('test'=>300,'ip'=>'3.3'),2=>array('test'=>400,'ip'=>'3.3')),
        );

        $condition = new Condition($expressions,'1');

        $alert_data_key = $condition->judge( $rule_data);

        $excepted_alert_data_key = array(0=>1,1=>1);

        $this->assertEquals ( $alert_data_key, $excepted_alert_data_key );
    }

    public function testNotIn(){
        $expressions = array(
            1=> new  Expression('$test', 'array(200,300)', 'not in' ),
        );

        $rule_data = array(
            0=>array(0=>array('test'=>200,'ip'=>'2.2'),1=>array('test'=>300,'ip'=>'3.3'),2=>array('test'=>400,'ip'=>'3.3')),
        );

        $condition = new Condition($expressions,'1');

        $alert_data_key = $condition->judge( $rule_data);

        $excepted_alert_data_key = array(2=>1);

        $this->assertEquals ( $alert_data_key, $excepted_alert_data_key );
    }


    public function testWhole(){
        $expressions = array(
            1=> new  Expression('$test', 'array(200,300)', 'in' ),
        );
        $rule_data = array(
            0=>array(0=>array('test'=>200,'ip'=>'2.2'),1=>array('test'=>300,'ip'=>'3.3'),2=>array('test'=>400,'ip'=>'3.3')),
        );
        $condition = new Condition($expressions,'1');
        $alert_data = $condition->judgeCondition( $rule_data);
        $except_alert_data = array(
            0=>array(0=>array('test'=>200,'ip'=>'2.2'),1=>array('test'=>300,'ip'=>'3.3')),
        );
        $this->assertEquals ( $alert_data, $except_alert_data );
    }

    public function testCount(){
        $expressions = array(
            1=> new  Expression('count()', '3', '==' ),
        );
        $rule_data = array(
            0=>array(0=>array('test'=>200,'ip'=>'2.2'),1=>array('test'=>300,'ip'=>'3.3'),2=>array('test'=>400,'ip'=>'3.3')),
        );
        $condition = new Condition($expressions,'1');
        $alert_data = $condition->judge( $rule_data);
        $excepted_alert_data_key = array(0=>1,1=>1,2=>1);
        $this->assertEquals ( $alert_data, $excepted_alert_data_key );

        $alert_data = $condition->judgeCondition( $rule_data);
        $excepted_alert_data_key = $rule_data;
        $this->assertEquals ( $alert_data, $excepted_alert_data_key );
    }
	
}
