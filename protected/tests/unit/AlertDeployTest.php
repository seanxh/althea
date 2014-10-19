<?php
/**
 * Class ChildExpressionCalcTest
 * @author xuhao05(SeanXh) 14-10-18 下午10:18
 */
class AlertDeployTest extends CTestCase {

    public function setup() {
        ini_set('date.timezone', 'Asia/Chongqing');
    }

    public function tearDown() {
    }

    public function  testSimple(){
        $alert_deploy = new AlertDeployRule();
        $result = $alert_deploy->check('hour/9,10,11');

        $hour = date('G');

        if( in_array($hour,array('9','10','11'))){
            $expect_result = true;
        }else{
            $expect_result = false;
        }
        $this->assertEquals ( $expect_result, $result );

        $result = $alert_deploy->check('hour/24');
        $expect_result = false;
        $this->assertEquals ( $expect_result, $result );
    }

    public function  testSimple2(){
        $alert_deploy = new AlertDeployRule();
        $result = $alert_deploy->check('hour/20-23');

        $hour = date('G');

        if( in_array($hour,array('20','21','22','23'))){
            $expect_result = true;
        }else{
            $expect_result = false;
        }
        $this->assertEquals ( $expect_result, $result );

        $result = $alert_deploy->check('hour/0-23');
        $expect_result = true;
        $this->assertEquals ( $expect_result, $result );
    }

    public function testLogic(){
        $alert_deploy = new AlertDeployRule();
        $result = $alert_deploy->check('hour/9,10,11&week/1,2,3,4,5,7');

        $hour = date('G');
        if( in_array($hour,array('9','10','11'))){
            $expect_result = true;
        }else{
            $expect_result = false;
        }
        $week = date('w');
        if ($week == 0) $week = '7';
        if( in_array($week,array('6'))){
            $expect_result = $expect_result && false;
        }else{
            $expect_result = $expect_result && true;
        }

        $this->assertEquals ( $expect_result, $result );


    }

    public function testLogic2(){
        $alert_deploy = new AlertDeployRule();
        $result = $alert_deploy->check('hour/9-11&week/1,7');
        $hour = date('G');
        if( in_array($hour,array('9','10','11'))){
            $expect_result = true;
        }else{
            $expect_result = false;
        }
        $week = date('w');
        if ($week == 0) $week = '7';

        if( in_array($week,array('7','1'))){
            $expect_result = $expect_result && true;
        }else{
            $expect_result = $expect_result && false;
        }
        $this->assertEquals ( $expect_result, $result );
    }

    public function testWeek(){
        $alert_deploy = new AlertDeployRule();
        $result = $alert_deploy->check('week/1,7');

        $week = date('w');
        if ($week == 0) $week = '7';

        if( in_array($week,array('7','1'))){
            $expect_result = true;
        }else{
            $expect_result = false;
        }
        $this->assertEquals ( $expect_result, $result );
    }

    public function testHour(){
        $alert_deploy = new AlertDeployRule();
        $result = $alert_deploy->check('hour/0,1');

        $hour = date('G');
        if( in_array($hour,array('0','1'))){
            $expect_result = true;
        }else{
            $expect_result = false;
        }
        $this->assertEquals ( $expect_result, $result );
    }


    public function testMonth(){
        $alert_deploy = new AlertDeployRule();
        $result = $alert_deploy->check('month/1-5');
        $hour = date('n');
        if( in_array($hour,array('1','2','3','4','5'))){
            $expect_result = true;
        }else{
            $expect_result = false;
        }
        $this->assertEquals ( $expect_result, $result );
    }

    public function testDay(){
        $alert_deploy = new AlertDeployRule();
        $result = $alert_deploy->check('day/1-5');
        $day = date('j');
        if( in_array($day,array('1','2','3','4','5'))){
            $expect_result = true;
        }else{
            $expect_result = false;
        }
        $this->assertEquals ( $expect_result, $result );
    }

    /**
     * @expectedException AlertDeployRuleException
     */
    public function testNotExistRule(){
        $alert_deploy = new AlertDeployRule();
        $alert_deploy->check('xxxx/1-5');
    }


    /**
     * @expectedException AlertDeployRuleException
     */
    public function testWrongFormat1(){
        $alert_deploy = new AlertDeployRule();
        $alert_deploy->check('xxxx');
    }

    /**
     * @expectedException AlertDeployRuleException
     */
    public function testWrongFormat2(){
        $alert_deploy = new AlertDeployRule();
        $alert_deploy->check('xxxx/123-111-222');
    }
}
