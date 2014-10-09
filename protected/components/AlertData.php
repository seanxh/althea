<?php
class AlertData implements ArrayAccess,Iterator,Countable{
	
	/**
	 * @param RuleData $rule_data
	 */
	public function __construct(){
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
            return null;
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