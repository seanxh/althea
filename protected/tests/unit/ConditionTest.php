<?php
/**
 * 条件测试
 * @author (SeanXh) 14-10-9 下午11:55
 */
class ConditionTest extends CTestCase {
    public function setup() {
    }
    public function tearDown() {
    }

    public function testNullCondition(){

        $simple_expression = new  Expression('1', '1', '==');
        $simple_expression2 = new  Expression('2', '{2,3}', 'in');

        $expressions = array(
            0=>$simple_expression,
            1=>$simple_expression2,
        );

        $rule_data = array(
            0=>array(array('test'=>1),array('test'=>2)),
        );

        $condition = new Condition($expressions, '0 and 1');
        $alert_data = $condition->judgeCondition($rule_data);
        $this->assertEquals($rule_data,$alert_data);
    }

    public function testSimpleCondition(){

        $simple_expression = new  Expression('$test', '200', '==');

        $simple_expression2 = new  Expression('$ip', '{2.2,3.3}', 'in');

        $rule_data = array(
            0 => array(0 => array('test' => 200, 'ip' => '2.2'), 1 => array('test' => 300, 'ip' => '3.3')),
        );

        $expressions = array(
            0=>$simple_expression,
            1=>$simple_expression2,
        );

        $condition = new Condition($expressions, '0 and 1');
        $alert_data = $condition->judgeCondition($rule_data);
        $this->assertEquals(array(0=>array($rule_data[0][0])),$alert_data);
    }


    public function testFunctionCondition(){

        $rule_data = array(
            0 => array(0 => array('test' => 200, 'ip' => '2.2'), 1 => array('test' => 300, 'ip' => '3.3')),
            1 => array(0 => array('test' => 300, 'ip' => '2.2'), 1 => array('test' => 200, 'ip' => '3.3')),
        );

        $expression = new  Expression('$test-prev(test)', '50', '<');
        $expression2 = new  Expression('$ip', '{2.2,3.3}', 'in');

        $expressions = array(
            0=>$expression,
            1=>$expression2,
        );

        $condition = new Condition($expressions, '0 and 1');
        $alert_data = $condition->judgeCondition($rule_data);
        $this->assertEquals(array(0=>array($rule_data[0][0]),1=>array($rule_data[1][0])),$alert_data);
    }

    public function testComplexCondition(){

        $simple_expression = new  Expression('1', '1', '==');
        $simple_expression2 = new  Expression('2', '{2,3}', 'in');
        $simple_expression3 = new  Expression('1', '2', '==');

        $expressions = array(
            0=>$simple_expression,
            1=>$simple_expression2,
            2=>$simple_expression3,
        );

        $rule_data = array(
            0=>array(array('test'=>1),array('test'=>2)),
        );

        $condition = new Condition($expressions, '0 and 1 or 2');
        $alert_data = $condition->judgeCondition($rule_data);
        $this->assertEquals($rule_data,$alert_data);

        $condition = new Condition($expressions, '0 and 1 and 2');
        $alert_data = $condition->judgeCondition($rule_data);
        $this->assertEquals(array(),$alert_data);


        $condition = new Condition($expressions, '0 && 1 || 2');
        $alert_data = $condition->judgeCondition($rule_data);
        $this->assertEquals($rule_data,$alert_data);

        $condition = new Condition($expressions, '0 && 1 && 2');
        $alert_data = $condition->judgeCondition($rule_data);
        $this->assertEquals(array(),$alert_data);

    }
}
