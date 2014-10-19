<?php

class Method {

    /**
     * @var RuleData
     */
    private $_rule_data = null;

    private $_key = null;

    public function __construct($rule_data, $key) {
        $this->_key = $key;
        $this->_rule_data = $rule_data;
    }

    /**
     * 返回上一个周期的数据(如果指定cycle，则为上N个周期的)
     * @param string $column 如 ：IP
     * @param array $group
     * @param number $cycle
     * @example $connection-prev(connection,{ip,port},1)
     * @return mixed
     */
    public function prev($column, $cycle = 1) {
        if (isset($this->_rule_data[$cycle] [$this->_key] [$column]))
            return $this->_rule_data[$cycle] [$this->_key] [$column];
        return 0;
    }

    public function str_prev_hour($n = 1) {
        return date('Y-m-d H:i:s', time() - 3600 * $n);
    }

    public function count() {
        return count($this->_rule_data[0]);
    }

    public function concatString($str1,$str2){
        return $str1.$str2;
    }


    public function dateFormatDay($time = null) {
        return $this->dateFormat('Ymd', $time);
    }

    public function dateFormatMonth($time = null) {
        return $this->dateFormat('Ym', $time);
    }

    public function dateFormatYear($time = null) {
        return $this->dateFormat('Y', $time);
    }

    public function dateFormatMinute($time = null) {
        return $this->dateFormat('Y-m-d H:i', $time);
    }

    public function date($time = null) {
        return $this->dateFormat('Y-m-d H:i:s', $time);
    }

    public function dateFormat($str, $time = null) {
        $stamp = time();
        if ($time !== null) {
            if (is_int($time)) $stamp = $time;
            else if (is_string($time)) $stamp = strtotime($time);
        }
        return date($str, $stamp);
    }

    public function prevHour($column, $hour = 1) {
        $time = $GLOBALS['CURRENT_TIME'] - 3600 * $hour;
        $cycle = $this->_rule_data->calcCycleIndex($time);
        if (isset($this->_rule_data[$cycle] [$this->_key] [$column]))
            return $this->_rule_data[$cycle] [$this->_key] [$column];
        return 0;
    }

    public function arrays() {
        $stack = func_get_args();
        $return_arr = array();
        foreach ($stack as $val) {
            $arr_key = 0;
            $arr_val = 0;
            if (is_int($val) || is_float($val)) {
                array_push($return_arr, $val);
            } else if (is_string($val)) {
                $arr = explode(':', $val);
                if (count($arr) > 1) {
                    $arr_key = $arr[0];
                    $arr_val = $arr[1];
                } else {
                    $arr_val = $val;
                }

                if (strstr($arr_key, '$') === 0) {
                    $arr_key = $this->_getVal($arr_key, 0);
                }

                if (strstr($arr_val, '$') === 0) {
                    $arr_val = $this->_getVal($arr_val, 0);
                }
                if ($arr_key === 0) {
                    array_push($return_arr, $val);
                } else {
                    $return_arr[$arr_key] = $arr_val;
                }

            }
        }

        return $return_arr;

    }

    /**
     * JOIN两个表，并选取第指定字符
     * @param string $table
     * @param array $field_map
     * @param mixed $val JOIN连接的值
     * @param string $fields 获取的字段
     * @example join(ip,{ip_id,id},$ip_id,ip)
     * @return mixed|NULL
     */
    public function join($origin_table, $target_table_map, $val, $fields) {

        $command = $this->_rule_data->createCommand();
        if (is_array($origin_table) && count($origin_table) == 2) {
            $table = $origin_table[0];
            $column = $origin_table[1];
        } else if (is_array($origin_table)) {
            $table = $this->_rule_data->getTable();
            $column = $origin_table[0];
        } else {
            $table = $this->_rule_data->getTable();
            $column = $origin_table;
        }

        $target_table = $target_table_map[0];
        $target_column = $target_table_map[1];

        $reader = $command->select($target_table . '.' . $fields)->from($table)
            ->join($target_table, $table . '.' . $column . '=' . $target_table . '.' . $target_column)
            ->where($table . '.' . $column . '=\'' . $val . '\'')->queryRow(0);

        if ($reader)
            return current($reader);

        return null;
    }

    /*
     * 获取一个变量的值
    */
    private function _getVal($val, $default = 0) {

        $val = ltrim($val, '$');
        if (isset($this->_rule_data[0][$this->_key][$val])) {
            return $this->_rule_data[0][$this->_key][$val];
        }
        return $default;

    }

    /**
     * 获取一个变量在本周期的值
     * @param string $val
     * @param mixed $default 默认值
     * @return number
     */
    public function getVal($val, $default = 0) {
        return $this->_getVal($val, $default);
    }
}