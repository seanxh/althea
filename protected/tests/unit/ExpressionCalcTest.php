<?php
/**
 * 表达式测试
 * ExpressionAnalyseTest.php
 * @author  SeanXh 2014-6-7 下午4:48:12
 */
class ExpressionCalcTest extends CTestCase{

    public function setup(){
        ini_set('date.timezone','Asia/Chongqing');
    }

    public function tearDown(){
    }

    /**
     * @expectedException
     */
    public function testSimpleExpression(){

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


        $expression = new ChildExpression('dateFormatYear()');
        $this->assertEquals(date('Y') ,  $expression->calc($rule_data, 0) );

        $expression = new ChildExpression('dateFormatMonth()');
        $this->assertEquals(date('Ym'),  $expression->calc($rule_data, 0) );

        $expression = new ChildExpression('dateFormatDay()');
        $this->assertEquals(date('Ymd') ,  $expression->calc($rule_data, 1) );

        $expression = new ChildExpression('count()');
        $this->assertEquals(count($rule_data[0]) ,  $expression->calc($rule_data, 0) );

        $rule_data = array(
            0=>array(0=>array('test'=>200,'ip'=>'2.2'),1=>array('test'=>300,'ip'=>'3.3')),
            1=>array(0=>array('test'=>300,'ip'=>'2.2'),1=>array('test'=>200,'ip'=>'3.3')),
        );
        $expression = new ChildExpression('$test-prev(test)');
        $this->assertEquals( -100 ,  $expression->calc($rule_data, 0) );
        $this->assertEquals( 100 ,  $expression->calc($rule_data, 1) );
    }

}
