<?php
/**
 * 表达式测试
 * @author  SeanXh 2014-6-7 下午4:48:12
 */
class ChildExpressionTest extends CTestCase {
    public function setup() {
    }
    public function tearDown() {
    }
    /**
     * 简单表达式，后缀转换验证
     */
    public function testPostix() {
        $expression = new ChildExpression ( '2.2+3' );
        $postix_expression = $expression->getPostFixExpression ();
        $expected_postix_expression = array (
            new Operator ( Operator::STRING, '2.2' ),
            new Operator ( Operator::INTEGER, '3' ),
            new Operator ( Operator::OPERATOR, '+' ) 
        );
        $this->assertEquals ( $expected_postix_expression, $postix_expression );
    }
    
    /**
     * 带有函数的表达式，后缀转换验证
     */
    public function testFuncPostix() {
        $expression = new ChildExpression ( '$conn-prev(conn)' );
        $postix_expression = $expression->getPostFixExpression ();
        $expected_postix_expression = array (
            new Operator ( Operator::VARIABLE, '$conn' ),
            new Operator ( Operator::FUNCTIONS, 'prev(conn)' ),
            new Operator ( Operator::OPERATOR, '-' ) 
        );
        $this->assertEquals ( $expected_postix_expression, $postix_expression );
    }
    
    /**
     * 带有数组的表达式，后缀转换验证
     */
    public function testArrayPostix() {
        $expression = new ChildExpression ( '$conn-{12,123}' );
        $postix_expression = $expression->getPostFixExpression ();
        $expression2 = new ChildExpression ( '$conn-array(12,123)' );
        $postix_expression2 = $expression->getPostFixExpression ();
        $expected_postix_expression = array (
            new Operator ( Operator::VARIABLE, '$conn' ),
            new Operator ( Operator::FUNCTIONS, 'arrays(12,123)' ),
            new Operator ( Operator::OPERATOR, '-' ) 
        );
        $this->assertEquals ( $expected_postix_expression, $postix_expression );
        $this->assertEquals ( $expected_postix_expression, $postix_expression2 );
    }
    
    /**
     * 比较复杂的中辍转后缀
     */
    public function testComplexPostfix(){
        $expression = new ChildExpression ( '1*2+(prev($abc,$ccc,abc,ccc)+30)/20*30' );
        $postix_expression = $expression->getPostFixExpression ();
        $expected_postix_expression = array (
                new Operator ( Operator::INTEGER, '1' ),
                new Operator ( Operator::INTEGER, '2' ),
                new Operator ( Operator::OPERATOR, '*'),
                new Operator ( Operator::FUNCTIONS, 'prev($abc,$ccc,abc,ccc)' ),
                new Operator(Operator::INTEGER, '30'),
                new Operator(Operator::OPERATOR, '+'),
                new Operator(Operator::INTEGER, '20'),
                new Operator(Operator::OPERATOR, '/'),
                new Operator(Operator::INTEGER, '30'),
                new Operator(Operator::OPERATOR, '*'),
                new Operator(Operator::OPERATOR, '+'),
        );
        $this->assertEquals ( $expected_postix_expression, $postix_expression );
    }

    /**
     * 比较复杂的中辍转后缀
     */
    public function testComplexPostfix2(){
        $expression = new ChildExpression ( '1*2+hour(prev($abc,test),test)' );
        $postix_expression = $expression->getPostFixExpression ();
        $expected_postix_expression = array (
            new Operator ( Operator::INTEGER, '1' ),
            new Operator ( Operator::INTEGER, '2' ),
            new Operator ( Operator::OPERATOR, '*'),
            new Operator ( Operator::FUNCTIONS, 'hour(prev($abc,test),test)' ),
            new Operator ( Operator::OPERATOR, '+'),
        );
        $this->assertEquals ( $expected_postix_expression, $postix_expression );
    }
}
