<?php
/**
 * FunctionStackAnalyseTest.php
 * @author xuhao05(SeanXh) 14-9-25 下午10:08
 */
class FunctionStackAnalyseTest extends CTestCase{


    public function setup(){
    }

    public function tearDown(){
    }

    public function testSimpleFunction(){

        $str = 'prev($abc,$ccc,abc,ccc)';

        $function_stack = new FunctionsStack();
        $stack = $function_stack->analyseFuncStack($str)->get();
        $expected_postix_expression = array (
            array(FunctionsStack::BRACKET,'('),
            array(FunctionsStack::FUNCTIONS,'prev'),
            array(FunctionsStack::VARIABLE,'$abc'),
            array(FunctionsStack::VARIABLE,'$ccc'),
            array(FunctionsStack::STRING,'abc'),
            array(FunctionsStack::STRING,'ccc'),
            array(FunctionsStack::BRACKET,')'),
        );
        $this->assertEquals ( $stack, $expected_postix_expression );
    }

    public function testNestedFunction(){

        $str = 'hour(prev($abc,test),test)';

        $function_stack = new FunctionsStack();
        $stack = $function_stack->analyseFuncStack($str)->get();
        $expected_postix_expression = array (
            array(FunctionsStack::BRACKET,'('),
            array(FunctionsStack::FUNCTIONS,'hour'),
            array(FunctionsStack::BRACKET,'('),
            array(FunctionsStack::FUNCTIONS,'prev'),
            array(FunctionsStack::VARIABLE,'$abc'),
            array(FunctionsStack::STRING,'test'),
            array(FunctionsStack::BRACKET,')'),
            array(FunctionsStack::STRING,'test'),
            array(FunctionsStack::BRACKET,')'),
        );
        $this->assertEquals ( $stack, $expected_postix_expression );
    }

}
