<?php
/**
 * MatPHP可以过滤数组or对数组Key值进行转置 
 * @author  Xuhao05(seanxh) 2014-7-31 下午6:18:03
 * EG:
 * $arr = array( 
 *          array('status'=>1,'id'=>1,'name'=>'xuhao',),
 *          array('status'=>0,'id'=>2,'name'=>'xuhao05',),
 * );
 * //数组转置
 * print_r( MatPHP::model($arr)->equal('status',0)->revert('id','name') );
 * // array( 2=>'xuhao05')
 * print_r( MatPHP::model($arr)->equal('status',0)->revert('id') );
 * // array( 2=> array('status'=>0,'id'=>2,'name'=>'xuhao05',) )
 * //数组过滤
 * print_r( MatPHP::model($arr)->equal('status',0)->filter() );
 * // array( 1=> array('status'=>0,'id'=>2,'name'=>'xuhao05',) )
 * print_r( MatPHP::model($arr)->equal('status',0)->filter(false) );
 * // array( array('status'=>0,'id'=>2,'name'=>'xuhao05',) )
 * //数组聚合
 *  $arr = array( 
 *          array('status'=>1,'id'=>1,'name'=>'xuhao',),
 *          array('status'=>0,'id'=>2,'name'=>'xuhao',),
 *          array('status'=>0,'id'=>3,'name'=>'xuhao',),
 *          array('status'=>1,'id'=>4,'name'=>'heihei',),
 * );
 * print_r( MatPHP::model($arr)->grouper('status')->group();
 * // array( 0=> array( array('status'=>0,'id'=>2,'name'=>'xuhao',),array('status'=>0,'id'=>3,'name'=>'xuhao',),)
 *               1=> array('status'=>1,'id'=>1,'name'=>'xuhao',),array('status'=>1,'id'=>4,'name'=>'heihei',),
 *             )
 * print_r( MatPHP::model($arr)->grouper('status')->grouper('name')->group();
 * // array( '0_xuhao'=> array( array('status'=>0,'id'=>2,'name'=>'xuhao',),array('status'=>0,'id'=>3,'name'=>'xuhao',),)
 *               '1_xuhao'=> array('status'=>1,'id'=>1,'name'=>'xuhao',)
 *               '1_heihei'=>  array('status'=>1,'id'=>4,'name'=>'heihei',)
 *             )
 * print_r( MatPHP::model($arr)->grouper('status')->group('name');
 * // array( '0'=> array( 'xuhao','xuhao',)
 *               '1'=> array(''xuhao','heihei')
 *             )
 */
class MatPHP{

    public $arr = null;
    
    public $rule = array();
    
    public $initKey = array();
    
    public $grouper = array();
    
    public $strGrouperSeperator = '_';
    
    /**
     * @param array $arr
     * @return MatPHP
     */
    public function __construct($arr){
        $this->arr = $arr;
    }
    
    /**
     * 初始化对象用, MatPHP::model($arr)
     * @param unknown $arr
     * @return MatPHP
     */
    public static function model($arr){
        return new MatPHP($arr);
    }


    /**
     * 初始化一个数组级联的N层key
     * $a = array();
     * MatPHP::initArray()->key("key1")->key("key2")->init($a,0))
     * //$a = array(1) { ["a"]=> array(1) { ["b"]=> int(0) } }
     * $b = array('b'=>1)
     * MatPHP::initArray()->key("b")->init($b,"c"))
     * //$b=array('b'=>1)
     * @return MatPHP
     */
    public static function initArray(){
        return new MatPHP(array());
    }


    /**
     * 检查某一个数组元素是否符合
     * @param unknown $tuple
     * @return boolean
     */
    protected function checkRule($tuple){
        $flag = false;
        //依次检查每一个条件，如果有条件 未通过flag变为true
        foreach ($this->rule as $filed=>$type_filter){
            foreach ($type_filter as $type=>$filter){
                $method = 'filter'.ucfirst($type);
                if( !isset($tuple[$filed]) ){
                    throw new MatPHPException('使用'.$method.'过滤时不存在键值'.$filed);
                }
                if( !$this->$method($tuple[$filed],$filter) ) {
                    $flag = true;
                    break;
                }
            }
            if($flag == true) {
                break;
            }
        }
        return $flag;
    }

    
    /**
     * @param data array('min'=>?,'max'=>?)
     * @return boolean
     */
    protected function filterIn($value, $filter) {
        if (!in_array($value, $filter['range'])) {
            return false;
        }
        return true;
    }
    
    /**
     * @param mixed $field
     * @param array $range
     * @return MatPHP
     */
    public function in($field,$range){
        if( !isset( $this->rule[$field] ) ){
            $this->rule[$field] = array();
        }
        
        $this->rule[$field]['in'] = array('range'=>$range);
        
        return $this;
    }
    
    /**
     * 过滤条件“等于”
     * @param scalar $field
     * @param mixed $value
     * @return MatPHP
     */
    public function equal($field,$value) {
        if( !isset( $this->rule[$field] ) ){
            $this->rule[$field] = array();
        }
        
        $this->rule[$field]['equal'] = array('value'=>$value);
        
        return $this;
    }
    
    /**
     * 过滤条件“等于”
     * @param string $value
     * @param string $filter
     * @return boolean
     */
    protected  function filterEqual($value, $filter) {
        if ( $value != $filter['value'] ) {
            return false;
        }
        return true;
    }
    
    /**
     * 过滤条件“数字”
     * @param string $field
     * @param string $min
     * @param string $max
     * @return MatPHP
     */
    public function int($field, $min = null, $max = null){
        if( !isset( $this->rule[$field] ) ){
            $this->rule[$field] = array();
        }
        
        $this->rule[$field]['int'] = array('min'=>$min,'max'=>$max);
        
        return $this;
    }
    
    /**
     * @param string $value
     * @param string $filter
     * @return boolean
     */
    protected  function filterInt($value,$filter){
        $min = $filter['min'];
        $max = $filter['max'];
        if (!ctype_digit((string) $value)) {
            return false;
        }
        $intval = intval($value);
        if ($min != null && $intval < intval($min)) {
            return false;
        }
        if ($max != null && $intval > intval($max)) {
            return false;
        }
        return true;
    }


    protected  function  filterNequal($value,$filter){
        if ( $value == $filter['value'] ) {
            return false;
        }
        return true;
    }

    public function nequal($field,$value){
        if( !isset( $this->rule[$field] ) ){
            $this->rule[$field] = array();
        }

        $this->rule[$field]['nequal'] = array('value'=>$value);

        return $this;
    }

    protected  function filterMatch($value,$filter){
        $pattern = $filter['pattern'];
        if (preg_match($pattern, $value) == false) {
            return false;
        }
        return true;
    }

    public function match($field, $pattern) {
        if( !isset( $this->rule[$field] ) ){
            $this->rule[$field] = array();
        }

        $this->rule[$field]['match'] = array('pattern'=>$pattern);

        return $this;
    }
    
    /**
     * @param string $keep
     * @return multitype:unknown
     */
    public function filter($keep=true){
        $arr = array();
        foreach ($this->arr as $key=>$tuple){
            //有条件未通过，直接检查下一下元素
            if( $this->checkRule($tuple) ){
                continue;
            }
            
            if($keep){
                $arr[$key] = $tuple;
            }else{
                $arr[]= $tuple;
            }
        }
        $this->rule = array();
        return $arr;
    }

    /**
     * 需要init的key
     * @param string $key
     * @return MatPHP
     */
    public function key($key){
        if( is_array($key)) {
            $this->initKey = array_merge($this->initKey,$key);
        }else{
            array_push($this->initKey, $key);
        }
        return $this;
    }

    /**
     * @param string $column
     * @return array
     */
    public function column($column,$force=true){
        $arr = array();
        foreach ($this->arr as $key=>$tuple){
            //有条件未通过，直接检查下一下元素
            if( $this->checkRule($tuple) ){
                continue;
            }
            
            if( !isset($tuple[$column])){
                if( $force ){
                    throw new MatPHPException('使用column或取数组时，不可处理的取值方式,没有key值:'.$column);
                }else{
                    continue;
                }
            }
            $arr[]= $tuple[$column];
        }
        $this->rule = array();
        return $arr;
    }


    
    /**
     * @param array $arr
     * @param $value 如果最后一个元素不存在 ，就把他初始为value
     * @throws MatPHPException
     * @return array
     */
    public  function init(&$arr,$value=array()){
        if(count($this->initKey) < 1 ){
            throw new MatPHPException('错误的使用方法.Usage: MatPHP::initArray()->key("key1")->key("key2")->init($a,"0"))');
        }
        $current = &$arr;
        end($this->initKey);
        $endKey = key($this->initKey);
        reset($this->initKey);
        $flag = false;
        foreach ( $this->initKey as $k=>$key){
            if(!isset($current[$key])){
                $current[$key] = array();
                if($k == $endKey){
                    $flag = true;
                }
            }
            $current = &$current[$key];
        }
        
        if($flag == true){
            $current = $value;
        }
        return $arr;
    }

    /**
     * 数组聚合
     *  $arr = array( 
     *          array('status'=>1,'id'=>1,'name'=>'xuhao',),
     *          array('status'=>0,'id'=>2,'name'=>'xuhao',),
     *          array('status'=>0,'id'=>3,'name'=>'xuhao',),
     *          array('status'=>1,'id'=>4,'name'=>'heihei',),
     * );
     * print_r( MatPHP::model($arr)->grouper('status')->group();
     * // array( 0=> array( array('status'=>0,'id'=>2,'name'=>'xuhao',),array('status'=>0,'id'=>3,'name'=>'xuhao',),)
     *               1=> array('status'=>1,'id'=>1,'name'=>'xuhao',),array('status'=>1,'id'=>4,'name'=>'heihei',),
     *             )
     * print_r( MatPHP::model($arr)->grouper('status')->grouper('name')->group();
     * // array( '0_xuhao'=> array( array('status'=>0,'id'=>2,'name'=>'xuhao',),array('status'=>0,'id'=>3,'name'=>'xuhao',),)
     *               '1_xuhao'=> array('status'=>1,'id'=>1,'name'=>'xuhao',)
     *               '1_heihei'=>  array('status'=>1,'id'=>4,'name'=>'heihei',)
     *             )
     * @param 根据哪个key进行分组
     * @throws MatPHPException
     * @return Ambigous <multitype:multitype: , unknown>
     */
    public function group($exceptKey=null){
        if(count($this->grouper) < 1 ){
            throw new MatPHPException('错误的使用方法.Usage: MatPHP::model($arr)->grouper("key1")->group())');
        }
        $arrGrouper = array();
        foreach ($this->arr as $key=>$array){
            
            //有条件未通过，直接检查下一下元素
            if( $this->checkRule($array) ){
                continue;
            }
            
            $arrGrouperValue = array();
            //组啥group
            foreach ($this->grouper as $grouper){
                if( !isset($array[$grouper])){
                    throw new MatPHPException('使用group对数组聚合时，存在不可处理的group方式,没有key值:'.$grouper);
                }
                $arrGrouperValue[] = $array[$grouper];
            }
            
            //填充内容
            $strGroups = implode($this->strGrouperSeperator,$arrGrouperValue);
            if( !isset($arrGrouper[$strGroups]) ){
                $arrGrouper[$strGroups] = array();
            }
            if( $exceptKey === null){
                $arrGrouper[$strGroups][] = $array;
            }else{
                if( !isset($array[$exceptKey])){
                    throw new MatPHPException('使用group对数组聚合时,存在不可处理的取值方式,没有key值:'.$exceptKey);
                }
                $arrGrouper[$strGroups][] = $array[$exceptKey];
            }

        }
        
        return $arrGrouper;
    }
    
    /**
     * @param scalar $key
     * @return MatPHP
     */
    public function grouper($key){
        array_push($this->grouper,$key);
        return $this;
    }

    /**
     * 组合数组
     * @param string $glue
     * @param string $left
     * @param string $right
     * @return string
     */
    public function implodeStr($glue=',',$left="'",$right="'"){
        $str = '';
        foreach ($this->arr as $v){
            $str .= "{$left}{$v}{$right}{$glue}";
        }

        return rtrim($str,$glue);
    }

    /**
     * 对数组进行转置
     * @param scalar $key
     * @param string $value
     * @throws MatPHPException
     * @return array
     */
    public function revert($key,$value=null,$force=true){
        $arr = array();
        foreach ($this->arr as $k=>$tuple){

            //有条件未通过，直接检查下一下元素
            if( $this->checkRule($tuple) ){
                continue;
            }

            if( !isset($tuple[$key])) {
                if($force){
                    throw new MatPHPException('使用revert对数组进行置时，不存在数组键值'.$key);
                }else{
                    continue;
                }
            }
            if( $value === null){
                $arr[$tuple[$key]] = $tuple;
            }else{
                if( !isset($tuple[$value])) {
                    if($force){
                        throw new MatPHPException('使用revert对数组进行置时,取值异常，不存在数组键值'.$value);
                    }else{
                        continue;
                    }
                }
                $arr[$tuple[$key]] = $tuple[$value];
            }
        }
        $this->rule = array();
        return $arr;
    }
}

/**
 * MatPHPException 
 */
class MatPHPException extends Exception{
}