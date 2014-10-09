<?php
/**
 * 监控策略数据源
 * @author  Xuhao05(seanxh) 2014-6-7 上午12:25:04
 */
class RuleData extends  CDbConnection implements ArrayAccess,Iterator,Countable{
	
	public $current_cycle_timestamp;
	
	public $schema ;
	
	public $log_config;
	
	public $rule;
	
	public $_log_type ;
	
	protected  $_log_cycle;
	
	protected $_log_time_column;
	
	protected $_log_time_column_type;
	
	protected $_table;
	
	protected  $_data;

	public  $condition_logic_operator;
	
	
	protected  $_sql;
	
	protected  $_where;
	
	protected  $_table_alias;
	
	protected $_queryGroup;
	
	public $dsn;
	public $username;
	public $password;
	
	/**
	 * 
	 * @param string $dsn
	 * @param string $username
	 * @param string $password
	 * @param string $charset
	 * @param log_config $log_config
	 * @param monitor_rule $rule
	 * @param int $cycle_timestamp
	 */
	public function __construct($dsn,$username,$password,$log_config,$rule){
		$this->dsn = $dsn;
		$this->username = $username;
		$this->password = $password;
		
		parent::__construct($this->dsn,$this->username,$this->password);
        $this->setAttribute(PDO::ATTR_TIMEOUT,3);
		$this->active=true;

		$this->setLog($log_config);
		$this->setRule($rule);
	}
	
	/**
	 * @todo 获取表名
	 *  使用示例:
	 * <code>
	 * $rule_data->getTable()
	 * </code>
	 * 
	 *  返回结果:
	 * <code>
	 * mc_status
	 * </code>
	 */
	public function getTable(){
		return $this->_table;
	}
	
	/**
	 * @todo 设置监控数据所对应的Log表
	 * @param log_config $log_config
	 * 
	 *  使用示例:
	 * <code>
	 * $rule_data->setLog( log_config::model()->findByPk(1) ) 
	 * </code>
	 */
	public function setLog($log_config){
        if( !$log_config ){
            return;
        }
		$this->log_config = $log_config;
        //如果是指定周期的日志，则计算周期
        $this->current_cycle_timestamp =  ($log_config->log_type) ? time() :  intval(Yii::app()->params['CURRENT_TIME']/$log_config->log_cycle)*$log_config->log_cycle;;
		$this->schema =  $this->getSchema()->getTable($log_config->table_name);
		
		$this->_table = $this->parseCondition( $log_config->table_name );
		
		$this->_log_time_column = $log_config->time_column;
		
		$this->_log_time_column_type = $this->schema->columns[$log_config->time_column]->type;
		
		$this->_log_cycle = $log_config->log_cycle;
		
		$this->_log_type = $log_config->log_type;
		
		if( !$this->current_cycle_timestamp )
			$this->current_cycle_timestamp = intval( time() / $this->_log_cycle )  * $this->_log_cycle;
	}
	
	public function pp(){
		var_dump($this->_data);
	}
	
	/**
	 * @todo 设置监控策略
	 * @param monitor_rule $rule
	 * 
	 *  使用示例:
	 * <code>
	 * $rule_data->setRule( monitor_rule::model()->findByPk(1) );
	 * </code>
	 */
	public function setRule($rule){
		$this->rule = $rule;
		$this->analyseSql($rule->select_sql);
		$this->condition_logic_operator =  empty($rule->condition_logic_operator) ? '1' : $rule->condition_logic_operator; 
	}

	/**
	 * @todo 分析监控策略对应的查询SQL。并从SQL中分析出where条件,表别名,QueryGroup列
	 * @param string $sql
	 * 
	 *  使用示例:
	 * <code>
	 *  $rule_data->analyseSql( 'select * from TABLE where result=0 querygroup by api_id' );
	 * </code>
	 */
	public function analyseSql($sql){
		preg_match('/TABLE[ ]+(?:as |AS )?[ ]?([a-z0-9A-Z]+)/', $sql,$match);
		if( !empty($match) && isset($match[1]))
			$alias_name = $match[1];
		else 
			$alias_name = null;
		
// 		if( in_array($alias_name,array('WHERE','Where','where','GROUP','Group','group','ORDER','Order','order','LIMIT','Limit','limit')) )
// 			$alias_name = null;
		if( strcasecmp($alias_name,'where') ===0 ||
			 strcasecmp($alias_name,'group') ===0 ||
			 strcasecmp($alias_name,'order') ===0 ||
			 strcasecmp($alias_name,'limit') ===0 ||
			 strcasecmp($alias_name,'querygroup') ===0
			)
		$alias_name = null;
		
		
		
$sql = str_replace('TABLE',$this->_table,$sql);

		$where_explode = preg_split('/WHERE/i', $sql);
		
		
		if(isset($where_explode[1])){
			$where = $where_explode[1];
			if( ($pos = stripos($where,'group by') ) !== false  &&  stripos($sql,'querygroup by') !== $pos-strlen('query') ){//where 之后有group by
				$where = 'where'.substr($where, 0,$pos).' and ';
			}else if( ($pos = stripos($where,'having') ) !== false){//where 之后有having
				$where = 'where'.substr($where, 0,$pos).' and ';
			}else if( ($pos = stripos($where,'order by') ) !== false){//where 之后有order by
				$where = 'where'.substr($where, 0,$pos).' and ';
			}else if( ($pos = stripos($where,'limit')) !== false){//where 之后有limit
				$where = 'where'.substr($where, 0,$pos).' and ';
			}else if( ($pos = stripos($where,'querygroup by')) !== false){//where 之后有limit
				$where = 'where'.substr($where, 0,$pos).' and ';
			}else{
				$where =  'where'.$where.' and ';
			}
// 			$where_sql = preg_match('/(?:where|WHERE) /', $subject);
			
		}else{
			if( ($pos = stripos($sql,'group by') ) !== false &&  stripos($sql,'querygroup by') !== $pos-strlen('query')  ){//where 之后有group by
				$sql = substr($sql,0,$pos) .' where '.substr($sql, $pos);
			}else if( ($pos = stripos($sql,'having') ) !== false){//where 之后有having
				$sql = substr($sql,0,$pos) .' where '.substr($sql, $pos);
			}else if( ($pos = stripos($sql,'order by') ) !== false){//where 之后有order by
				$sql = substr($sql,0,$pos) .' where '.substr($sql, $pos);
			}else if( ($pos = stripos($sql,'limit')) !== false){//where 之后有limit
				$sql = substr($sql,0,$pos) .' where '.substr($sql, $pos);
			}else if( ($pos = stripos($sql,'querygroup by')) !== false){//where 之后有limit
				$sql = substr($sql,0,$pos) .' where '.substr($sql, $pos);
			}else{
				$sql =$sql.' where ';
			}
			$where = 'where ';
		}
		$query_group = preg_split('/QUERYGROUP BY/i', $sql);
		if( isset($query_group[1]) ) {
			$this->_queryGroup = explode(',',trim($query_group[1]));
			$pos = stripos($sql,'querygroup by');
			$sql = substr($sql,	0,$pos);
			 
		}
		
		$this->_sql = $sql;
		$this->_where = $where;
		$this->_table_alias = $alias_name;
		
/* 		echo $this->_sql."\n";
		echo $this->_where."\n";
		echo ( $this->_table_alias == null) ? 'null':$this->_table_alias ,"\n";
		var_dump($this->_queryGroup);
		exit; */
	}
	
	/**
	 * 预加载函数
	 * @param array $group
	 * @param number $cycle
	 */
	public function preloadGroup($cycle=1){
		$this->offsetGet(0);
		$this->offsetGet($cycle);
	}
	
	public function preloadGroupHour($hour=1){
		$time = $GLOBALS['CURRENT_TIME']  - 3600*$hour;
		$cycle = $this->calcCycleIndex($time);
		$this->preloadGroup($group,$cycle);
	}
	
	/**
	 * 废弃的函数，不知道用途
	 * @param unknown $condition
	 * @return mixed
	 */
	public function parseCondition($condition){
		preg_match_all('/\[([^\[\]]+)\]/',$condition,$expressions);
	
		if( !empty($expressions)){
			
			$values = array();
			foreach ($expressions[1] as  $expression){
				$child_expression = new ChildExpression($expression);
				$values[]  =  array(
					$expression,
					$child_expression->calc(array(),''),
				);
			}
			foreach ($values as $value){
				$condition = str_replace("[{$value[0]}]", $value[1], $condition);
			}
			
		}
	
		return $condition;
	}
	
	
	/**
	 * 根据Index 获取某个周期的数据
	 * @param int $index
	 * @return array
	 */
	private function _get($index){
		
		$cycle_where = '';
		
		if ( $this->_log_type == log_config::WITHCYCLE  && $this->_table_alias === null)
			$cycle_where = $this->_log_time_column.'>='.$this->calcCycle($index).' and '.$this->_log_time_column.'<'.$this->calcCycle($index-1);
		else if($this->_log_type == log_config::WITHCYCLE  && $this->_table_alias !== null)
			$cycle_where = $this->_table_alias.'.'.$this->_log_time_column.'>='.$this->calcCycle($index).' and '.$this->_table_alias.'.'.$this->_log_time_column.'<'.$this->calcCycle($index-1);
		else
			$this->_where = rtrim($this->_where,'and ');
		
		$where = $this->_where. $cycle_where;
		
		//空SQL
		if( trim($where) == 'where' )throw new Exception('the rule '.$this->rule->id.' was monitor as a empty condition.Please check');

		
		$sql = str_replace($this->_where, $where, $this->_sql);
 		$reader = $this->createCommand($sql)->queryAll();
 		
 		$return_arr  = $reader;
 		
 		if( !empty($this->_queryGroup) ) {
 			
 			$return_arr = array();
 			
 			foreach ( $reader as $row){
 				$key = array();
 				foreach ( $this->_queryGroup as $k) {
                    if( $k ){
                        $key[] = $row[$k];
                    }
 				}	 
                if( !empty($key))
     				$return_arr[ implode(':',$key) ]  = $row;
 				
 			}
 			
 		}
		return $return_arr;
	}
	
	/**
	 * 判断该时间点属于哪个周期。
	 * 本周期为 0
	 * 前一个周期为 1
	 * @param unknown $type
	 * @return number
	 */
	public function judgeCycle($time){
	
		if ( $this->_log_time_column_type  == 'integer')
			$interval =  $this->current_cycle_timestamp  - intval( $time ) ;
		else
			$interval = $this->current_cycle_timestamp  - strtotime($time);
	
		if( $interval < 0 )
			return 0;
		return ceil( $interval /   $this->_log_cycle );
	}
	
	/**
	 *  判断该时间点属于哪个周期。
	 */
	public function calcCycleIndex($time){
		return ceil( (  $this->current_cycle_timestamp - $time ) /  $this->_log_cycle ) ;
	}
	
	/**
	 * 根据周期索引，返回周期的起始时间
	 * @param unknown $index
	 * @return number|string
	 */
	public function calcCycle($index){
		if ( $this->_log_time_column_type  == 'integer')
			return $this->current_cycle_timestamp - $index*$this->_log_cycle;
		else
			return date( "'Y-m-d H:i:s'",$this->current_cycle_timestamp - $index*$this->_log_cycle );
	}
	
	
	// Interface实现
	//countable,iterable,arrayaccess实现
	
	public function count() {
		return count($this->_data);
	}
 	
 	function rewind() {
        reset($this->_data);
    }

    function current() {
        return current($this->_data);
    }

    function key() {
        return key($this->_data);
    }

    function next() {
        next($this->_data);
    }

    function valid() {
         return ( $this->current() !== false ); 
    }
	
	/**
	 * @param offset
	 */
	public function offsetExists ($offset) {
		return isset($this->_data[$offset] );
	}
	
	/**
	 * @param offset
	 */
	 public function offsetGet ($offset) {
	 	if(!isset( $this->_data[$offset])){
	 		$this->_data[$offset] = $this->_get($offset);
	 	}
	 	return $this->_data[$offset];
	 }
	
	/**
	 * @param offset
	 * @param value
	 */
	public function offsetSet ($offset, $value) {
			$this->_data[$offset] = $value;
	}
	
	/**
	 * @param offset
	 */
	public function offsetUnset ($offset) {
		if(isset($this->_data[$offset]))
			unset($this->_data[$offset]);
	}
	
}
