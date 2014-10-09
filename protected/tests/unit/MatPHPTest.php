<?php
/**
  * MatPHPTest.php
  * @author xuhao05
*/
class MatPHPTest extends CTestCase {

    public function testRevert(){
        $array = array (
            array (
                'status' => 1,
                'id' => 1,
                'name' => 'xuhao'
            ),
            array (
                'status' => 0,
                'id' => 2,
                'name' => 'xuhao05'
            ),
        );

        $result = MatPHP::model ( $array )->equal ( 'status', 0 )->revert ( 'id', 'name' );
        $this->assertEquals ( $result, array (
            2 => 'xuhao05'
        ) );

        $result = MatPHP::model ( $array )->revert ( 'id', 'name' );
        $this->assertEquals ( $result, array (
            1=>'xuhao',
            2 => 'xuhao05',
        ) );

        $result = MatPHP::model ( $array )->equal ( 'status', 0 )->revert ( 'id' );
        $this->assertEquals ( $result, array (
            2 => array (
                'status' => 0,
                'id' => 2,
                'name' => 'xuhao05'
            ) ,
        ) );

    }

    public function testFilter() {
        $array = array (
            array (
                'status' => 1,
                'id' => 1,
                'name' => 'xuhao' 
            ),
            array (
                'status' => 0,
                'id' => 2,
                'name' => 'xuhao05' 
            ),
        );
        

        $result = MatPHP::model ( $array )->equal ( 'status', 0 )->filter ();
        $this->assertEquals ( $result, array (
            1 => array (
                'status' => 0,
                'id' => 2,
                'name' => 'xuhao05' 
            ) ,
        ) );
        
        $result = MatPHP::model ( $array )->equal ( 'status', 0 )->filter ( false );
        $this->assertEquals ( $result, array (
            array (
                'status' => 0,
                'id' => 2,
                'name' => 'xuhao05' 
            ) ,
        ) );


        $result = MatPHP::model ( $array )->nequal ( 'status', 0 )->filter ();
        $this->assertEquals ( $result, array (
            0 => array (
                'status' => 1,
                'id' => 1,
                'name' => 'xuhao'
            ) ,
        ) );

        $result = MatPHP::model ( $array )->match ( 'name', '/^\w+\d+$/' )->filter ();
        $this->assertEquals ( $result, array (
            1 => array (
                'status' => 0,
                'id' => 2,
                'name' => 'xuhao05'
            ) ,
        ) );
    }

    public function testColumn(){
        $array = array (
            array (
                'status' => 1,
                'id' => 1,
                'name' => 'xuhao'
            ),
            array (
                'status' => 0,
                'id' => 2,
                'name' => 'xuhao05'
            ),
        );

        $result = MatPHP::model($array)->column('status');
        $this->assertEquals($result,array(1,0));

        $result = MatPHP::model($array)->equal('name','xuhao05')->column('status');
        $this->assertEquals($result,array(0));
    }



    public function testInit() {
        $array = array ();
        
        MatPHP::initArray ()->key ( 'test' )->init ( $array );
        $this->assertEquals ( $array, array (
            'test' => array () ,
        ) );
        
        MatPHP::initArray ()->key ( 'test' )->init ( $array, 1 );
        $this->assertEquals ( $array, array (
            'test' => array () ,
        ) );
        
        $array = array ();
        MatPHP::initArray ()->key ( 'test' )->init ( $array, 1 );
        $this->assertEquals ( $array, array (
            'test' => 1 ,
        ) );

        $array = array ();
        MatPHP::initArray ()->key ( 'test' )->key('test2')->init ( $array, 1 );
        $this->assertEquals ( $array, array (
            'test' => array(
                'test2'=>1 ,
            ),
        ) );

    }


    public function testImplode(){
        $arr = array('1','2','3','4');
        $str = MatPHP::model($arr)->implodeStr(',',"[","]");
        $this->assertEquals($str,'[1],[2],[3],[4]');
    }



    public function testGroup(){
        $arr = array(
            array(
                'idc_id'=>'1',
                'amount'=>2,
                'status'=>0,
            ),
            array(
                'idc_id'=>'1',
                'amount'=>3,
                'status'=>1,
            ),
            array(
                'idc_id'=>'2',
                'amount'=>3,
                'status'=>1,
            ),

        );
        $arrays = MatPHP::model($arr)->grouper('idc_id')->group('amount');
        $this->assertEquals ( $arrays, array (
            '1' => array(2,3) ,
            '2'=>array(3)
        ) );


        $arrays = MatPHP::model($arr)->equal('status',1)->grouper('idc_id')->group('amount');
        $this->assertEquals ( $arrays, array (
            '1' => array(3) ,
            '2'=>array(3)
        ) );

    }

    /**
     * @expectedException MatPHPException
     */
    public function testColumnKeyNotExist(){

        $arr = array(
            array(
                'idc_id'=>'1',
                'amount'=>2,
                'status'=>0,
            ),
        );
        $arrays = MatPHP::model($arr)->column('test');
    }

    /**
     * @expectedException MatPHPException
     */
    public function testRevertKeyNotExist(){

        $array = array (
            array (
                'status' => 1,
                'id' => 1,
                'name' => 'xuhao'
            ),
            array (
                'status' => 0,
                'id' => 2,
                'name' => 'xuhao05'
            ),
        );
        $arrays = MatPHP::model($array)->revert('test');
    }


    /**
     * @expectedException MatPHPException
     */
    public function testFilterKeyNotExist(){

        $array = array (
            array (
                'status' => 1,
                'id' => 1,
                'name' => 'xuhao'
            ),
            array (
                'status' => 0,
                'id' => 2,
                'name' => 'xuhao05'
            ),
        );
        $result = MatPHP::model ( $array )->equal ( 'test', 0 )->filter ();
    }

    /**
     * @expectedException MatPHPException
     */
    public function testGroupKeyNotExist(){

        $arr = array(
            array(
                'idc_id'=>'1',
                'amount'=>2,
                'status'=>0,
            ),
            array(
                'idc_id'=>'1',
                'amount'=>3,
                'status'=>1,
            ),
            array(
                'idc_id'=>'2',
                'amount'=>3,
                'status'=>1,
            ),

        );
        $arrays = MatPHP::model($arr)->grouper('idc')->group('amount');
    }

    /**
     * @expectedException MatPHPException
     */
    public function testGroupKeyNotExist2(){

        $arr = array(
            array(
                'idc_id'=>'1',
                'amount'=>2,
                'status'=>0,
            ),
            array(
                'idc_id'=>'1',
                'amount'=>3,
                'status'=>1,
            ),
            array(
                'idc_id'=>'2',
                'amount'=>3,
                'status'=>1,
            ),

        );
        $arrays = MatPHP::model($arr)->grouper('idc_id')->group('amount2');
    }

}