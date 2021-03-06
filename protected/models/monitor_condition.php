<?php

/**
 * This is the model class for table "budget_unit".
 *
 * The followings are the available columns in table 'budget_unit':
 * @property integer $id
 * @property int $rule_id
 * @property int $serial_num
 * @property string $comparison_operator
 * @property string $left_expression
 * @property string $right_expression
 */
class monitor_condition extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return budget_unit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'monitor_condition';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rule_id,logic_operator,serial_number,left_expression,right_exression', 'required'),
			array('logic_operator,serial_number,left_expression,right_exression', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id,rule_id,logic_operator,serial_number,left_expression,right_exression', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'rule'	=> array(self::BELONGS_TO, 'monitor_rule', 'rule_id'),
// 			'operation_expression' => array(self::HAS_MANY, 'monitor_operation_expression', 'condition_id'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'rule_id' => '报警策略ID',
			'serial_num' => '表达式编号',
			'comparison_operator' => '比较运算符',
			'right_expression'=>'右运算表达式',
			'left_expression'=>'左运算表达式',
		);
	}

    public static function renew($rule_id,$attribute_arr){
        monitor_condition::model()->deleteAll('rule_id ='.$rule_id);

        if( empty($attribute_arr) ){
            return true;
        }
        $aInsertKey = array (
            'rule_id',
            'comparison_operator',
            'left_expression',
            'right_expression',
            'serial_num'
        );

        $sInsertSqlKey = '`' . str_replace ( ',', '`,`', implode ( ',', $aInsertKey ) ) . '`';
        //方便以后修改字段扩展
        $aInsertKeyMap = array ();
        $aInsertDefaultMap = array(
        );

        $sInsertSql = "INSERT INTO monitor_condition  ({$sInsertSqlKey}) VALUES ";

        $aValues = array ();
        foreach ( $attribute_arr as $conditon ) {
            $aInsert = array ();
            foreach ( $aInsertKey as $key ) {
                $aInsert [] = is_string ( $conditon [$key] ) ? "'{$conditon[$key]}'" : $conditon [$key];
            }
            $sInsert = '(' . implode ( ',', $aInsert ) . ')';
            $aValues [] = $sInsert;
        }

        $sInsertSql .= implode ( ',', $aValues );
        return Yii::app ()->db->createCommand ( $sInsertSql )->execute ();
    }

}// end class
