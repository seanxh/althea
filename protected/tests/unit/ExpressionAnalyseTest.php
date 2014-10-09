<?php
/**
 * 表达式测试
 * ExpressionAnalyseTest.php
 * @author  SeanXh 2014-6-7 下午4:48:12
 */
class ExpressionAnalyseTest extends CTestCase{

    public function setup(){
    }
    
    public function tearDown(){
    }
    
    /**
     * @expectedException 
     */
	public function testSimpleExpression(){
		$GLOBALS['CURRENT_TIME'] = $time =time();// strtotime('2014-03-09 23:19:00');
		
		$expressions = array(
				1=> new  Expression('$test', '200', '==' ),
				2=> new  Expression('$ip', '{2.2,3.3}', 'in' ),
		);
		
		$rule_data = array(
				0=>array(0=>array('test'=>200,'ip'=>'2.2'),1=>array('test'=>300,'ip'=>'3.3')),
		);
		
		/* $condition = new Condition($expressions,$rule_data);
		 $alert_data = $condition->judgeCondition(); */
		
		$expression = new ChildExpression('$test');
		$this->assertEquals(200 ,  $expression->calc($rule_data, 0) );
		
		$expression = new ChildExpression('$ip');
		$this->assertEquals('2.2' ,  $expression->calc($rule_data, 0) );
		
		$expression = new ChildExpression('$ip');
		$this->assertEquals('3.3' ,  $expression->calc($rule_data, 1) );
	}
	
}
