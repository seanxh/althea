<?php
/**
 * $RCSfile$ RequestValidator.php
 * 参数校验类
 *
 * 使用示例:
 * <code>
 * try{
 * $validator = new RequestValidator( $data,true);
 * $validator->require('name', 'message' => '请输入名称');
 * $validator->int('age', 'max' => 30, 'message' => '年龄必须为整数，且不能超过{$30}.');
 * }catch (RequestValidatorException $e){
 * 		print $e->getMessage();
 * }
 * </code>
 * @author (seanXh)
 * @date 2013-05-12
 */
class RequestValidator {
    private $_model = null;
    private $_data = null;
    private $_throw_exception=false;

    public function __construct($data,$throw_exception=true) {
        $this->_model = new RequestValidatorErrors();
        $this->_data = $data;
        if( is_bool($throw_exception) )
            $this->_throw_exception = $throw_exception;
    }


    /**
     * Validator Methods
     * @param $field field name
     * @return boolean
     */
    public function required($field, $message = null) {
        if (!array_key_exists($field, $this->_data) || $this->_data[$field] == null || $this->_data[$field] == '') {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    public function equals($field1, $field2, $message = null) {
        if ($this->_data[$field1] != $this->_data[$field2]) {
            $this->addError($field1, $message);
            return false;
        }
        return true;
    }

    public function match($field, $pattern, $message = null) {
        if (preg_match($pattern, $this->_data[$field]) == false) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    public function int($field, $min = null, $max = null, $message = null) {
        $value = $this->_data[$field];
        if (!ctype_digit((string) $value)) {
            $this->addError($field, $message);
            return false;
        }
        $intval = intval($value);
        if ($min != null && $intval < intval($min)) {
            $this->addError($field, $message);
            return false;
        }
        if ($max != null && $intval > intval($max)) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    public function float($field, $message = null) {
        if (preg_match('/^[0-9]+\.[0-9]*$/', $this->_data[$field]) == false) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    public function ip($field, $message = null) {
        $value = $this->_data[$field];
        $long = ip2long($value);
        if($long == false || $long == -1) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    public function hostname($field, $message = null) {
        $host = $this->_data[$field];
        $regexLocal = "/^[a-zA-Z0-9\-\_\.]+$/";
        if (!preg_match($regexLocal, $host)) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    /**
     * @param data array('min'=>?,'max'=>?)
     */
    public function in($field, $range, $message = null) {
        if (!in_array($this->_data[$field], $range)) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    /**
     * Validate value if every character is alphabetic
     *
     * @param mixed $value
     */
    public function alpha($field, $message = null) {
        $value = $this->_data[$field];
        if (!ctype_alpha($value)) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    /**
     * 检查参数长度
     */
    public function length($field, $min = null, $max = null, $message = null) {
        $len = strlen($this->_data[$field]);
        if ($min != null && $len < $min) {
            $this->addError($field, $message);
            return false;
        }
        if ($max != null && $len > $max) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    public function email($field, $message = null) {
        $value = $this->_data[$field];
        if (preg_match('/^[^@]+@([-\w]+\.)+[A-Za-z]{2,4}$/', $value) == false) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    /**
     * 数字或英文字母
     * @param $field
     * @param mixed $value
     */
    public function alnum($field, $message = null) {
        if (!ctype_alnum($this->_data[$field])) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }


    protected function addError($field, $message) {
        if ($message != null) {
            $this->_model->addError($field, $message);
            if($this->_throw_exception) 
            	throw new RequestValidatorException($field,$message);
        }
    }
    
    public function getErrors(){
    	return $this->_model->getError();
    }
}

class RequestValidatorErrors {
	
	private $_errors = array();
	
	public function addError($field, $message) {
			$this->_errors[$field] = $message;
	}
	
	public function getError($field=null){
		if( $field !== null){
			return isset($this->_errors[$field]) ? $this->_errors[$field] : null;
		}
		return $this->_errors;
	}
	
	public function __toString(){
		return implode('; ',$this->_errors);
	}
}

class RequestValidatorException extends Exception {
    public $field;

    public  function __construct($field,$message = ""){
        parent::__construct($message);
        $this->field = $field;
    }
}
?>
