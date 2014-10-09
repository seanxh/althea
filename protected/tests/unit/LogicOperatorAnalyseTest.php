<?php
/**
 *  $RCSfile$ LogicOperatorAnalyseTest.php
 * @author  SeanXh 2014-6-7 上午10:08:08
 */
class LogicOperatorAnalyseTest extends CTestCase{

	public $logic;
	
    public function setup(){
    	$this->logic = new ConditionLogicOperator( '1 or 2 ',null);
    }
    
    public function tearDown(){
    }

	public function testSimpleOperator(){
		$logic_stack =  $this->logic->analyseConditionLogicOperator('1 or 2 ');
		$expected_statck = array(
			array(ConditionLogicOperator::EXPRESSION_NUM,'1'),
			array(ConditionLogicOperator::LOGIC_OPERATOR,'or'),
			array(ConditionLogicOperator::EXPRESSION_NUM,'2'),
		);
		$this->assertTrue( $expected_statck == $logic_stack);
		
		$logic_stack =  $this->logic->analyseConditionLogicOperator('1 or 20 ');
		$expected_statck = array(
				array(ConditionLogicOperator::EXPRESSION_NUM,'1'),
				array(ConditionLogicOperator::LOGIC_OPERATOR,'or'),
				array(ConditionLogicOperator::EXPRESSION_NUM,'20'),
		);
		$this->assertTrue( $expected_statck == $logic_stack);
	}
	
	public function testBracketOperator(){
		$logic_stack =  $this->logic->analyseConditionLogicOperator('1 or (2 and 3) ');
		$expected_statck = array(
				array(ConditionLogicOperator::EXPRESSION_NUM,'1'),
				array(ConditionLogicOperator::LOGIC_OPERATOR,'or'),
				array(ConditionLogicOperator::LEFT_BRACKET,'('),
				array(ConditionLogicOperator::EXPRESSION_NUM,'2'),
				array(ConditionLogicOperator::LOGIC_OPERATOR,'and'),
				array(ConditionLogicOperator::EXPRESSION_NUM,'3'),
				array(ConditionLogicOperator::RIGHT_BRACKET,')'),
		);
		$this->assertTrue( $expected_statck == $logic_stack);
		
		$logic_stack =  $this->logic->analyseConditionLogicOperator(' 1  or ( 2  and 3 )   ');
		$this->assertTrue( $expected_statck == $logic_stack);
	}
	
	public function testNestOperator(){
		$logic_stack =  $this->logic->analyseConditionLogicOperator('1 or (2 and ( 3 or 4) ) ');
		$expected_statck = array(
				array(ConditionLogicOperator::EXPRESSION_NUM,'1'),
				array(ConditionLogicOperator::LOGIC_OPERATOR,'or'),
				array(ConditionLogicOperator::LEFT_BRACKET,'('),
				array(ConditionLogicOperator::EXPRESSION_NUM,'2'),
				array(ConditionLogicOperator::LOGIC_OPERATOR,'and'),
				array(ConditionLogicOperator::LEFT_BRACKET,'('),
				array(ConditionLogicOperator::EXPRESSION_NUM,'3'),
				array(ConditionLogicOperator::LOGIC_OPERATOR,'or'),
				array(ConditionLogicOperator::EXPRESSION_NUM,'4'),
				array(ConditionLogicOperator::RIGHT_BRACKET,')'),
				array(ConditionLogicOperator::RIGHT_BRACKET,')'),
		);
		$this->assertTrue( $expected_statck == $logic_stack);
	}
	
	/**
	 * @expectedException  ConditionLogicOperatorException
	 */
/* 	public function testException(){
		$logic_stack =  $this->logic->analyseConditionLogicOperator('1 or 2a ');
	} */
	
}
