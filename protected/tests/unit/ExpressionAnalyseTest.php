<?php

/**
 * 表达式测试
 * ExpressionAnalyseTest.php
 * @author  SeanXh 2014-6-7 下午4:48:12
 */
class ExpressionAnalyseTest extends CTestCase {

    public function setup() {
    }

    public function tearDown() {
    }

    public function testSimpleExpression() {

        $simple_expression = new  Expression('$test', '200', '==');

        $simple_expression2 = new  Expression('$ip', '{2.2,3.3}', 'in');
        $rule_data = array(
            0 => array(0 => array('test' => 200, 'ip' => '2.2'), 1 => array('test' => 300, 'ip' => '3.3')),
        );


        $this->assertTrue($simple_expression->bool($rule_data, 0));
        $this->assertFalse($simple_expression->bool($rule_data, 1));

        $this->assertTrue($simple_expression2->bool($rule_data, 0));
        $this->assertTrue($simple_expression2->bool($rule_data, 1));

    }

    public function testNullData() {

        $simple_expression = new  Expression('1', '1', '==');
        $simple_expression2 = new  Expression('2', '{2,3}', 'in');
        $rule_data = array(
        );

        $this->assertTrue($simple_expression->bool($rule_data, 0));
        $this->assertTrue($simple_expression2->bool($rule_data, 0));
    }

    public function testFunctionExpression() {

        $rule_data = array(
            0 => array(0 => array('test' => 200, 'ip' => '2.2'), 1 => array('test' => 300, 'ip' => '3.3')),
            1 => array(0 => array('test' => 300, 'ip' => '2.2'), 1 => array('test' => 200, 'ip' => '3.3')),
        );

        $expression = new  Expression('$test-prev(test)', '50', '<');

        $this->assertTrue($expression->bool($rule_data, 0));
        $this->assertFalse($expression->bool($rule_data, 1));
    }

}
