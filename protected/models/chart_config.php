<?php

/**
 * This is the model class for table "budget_unit".
 *
 * The followings are the available columns in table 'budget_unit':
 * @property integer $id
 * @property string $log_id
 * @property string $name
 * @property string $select_sql
 * @property string $title
 * @property string $subtitle
 * @property string $expression
 * @property int $cycle
 * @property int $realtime
 * @property string $y_title
 * @property int $max_points
 * @property int $theme
 * @property int $status
 * @property log_config $log_config
 */
class chart_config extends CActiveRecord {
    const DATA_TYPE_MYSQL = 0;
    const DATA_TYPE_HTTP = 1;


    const LOG_TYPE_NO_CYCLE  = 0;
    const LOG_TYPE_WITH_CYCLE  = 1;

    static $DATA_TYPE = array(
        0 => 'MySQL',
        1 => 'http',
    );


    const LINE = 0;
    const AREA = 1;
    const COLUMN = 2;
    const SPLINE = 3;
    public static $CHART = array(
        self::LINE => 'line',
        self::AREA => 'area',
        self::COLUMN => 'column',
        self::SPLINE => 'spline',
    );

    const DEF = 0;
    const GRAY = 1;
    const BLUE = 2;
    const GREEN = 3;
    const GRID = 4;
    const SKY = 5;

    public static $THEME = array(
        self::DEF => 0,
        self::GRAY => 'gray',
        self::BLUE => 'dark-blue',
        self::GREEN => 'dark-green',
        self::GRID => 'grid',
        self::SKY => 'skies',
    );


    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return budget_unit the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'chart_config';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name,select_sql,title,expression,status', 'required'),
            array('log_id,name,select_sql,title,expression,cycle,status', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,name,select_sql,title,expression,cycle,status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'log_config' => array(self::BELONGS_TO, 'log_config', 'log_id'),
            'database'	=> array(self::BELONGS_TO, 'database_config', 'database_id'),
        );
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => '表名称',
            'cycle' => '图表周期(秒)',
            'log_id' => '日志ID',
            'select_sql' => '指定查询SQL',
            'type' => '图表类型',
            'realtime' => '是否实时变化',
            'expression' => 'Y轴计算表达式',
            'title' => '图表标题',
            'subtitle' => '副标题',
            'max_points' => '动态图最多点数',
            'y_title' => 'Y轴标题',
            'status' => '图表状态',
        );
    }


    public static function add($arr) {
        $chart = new chart_config();
        $new_attributes = self::mixData($arr);

        foreach ($new_attributes as $key => $value) {
            $chart->setAttribute($key, $value);
        }
        $id = $chart->save();
        if (!$id) {
            if ($chart->hasErrors()) {
                $errors = $chart->getErrors();
                $key = key($errors);
                throw new Exception($key . ':' . implode(',',$errors[$key]));
            } else {
                throw new Exception('添加图表失败');
            }

        }
    }

    public static function mixData($arr) {
        $parameters_arr = array();
        foreach ($arr['parameter'] as $key => $value) {
            $parameters_arr[$value] = $arr['value'][$key];
        }

        $arr['data_url_parameters'] = json_encode($parameters_arr);

        unset($arr['parameter']);
        unset($arr['value']);
        $new_attributes = $arr;
        return $new_attributes;
    }


    public static function renew($id, $arr) {
        $chart = self::model()->findByPk($id);
        if (empty($chart)) {
            throw new Exception('未知的图表ID，请不要手动修改表单隐藏字段！');
        }


        $new_attributes = self::mixData($arr);

        foreach ($new_attributes as $key => $value) {
            $chart->setAttribute($key, $value);
        }
        $status = $chart->save();

        if ($chart->hasErrors()) {
            $errors = $chart->getErrors();
            $key = key($errors);
            throw new Exception($key . ':' . implode(',',$errors[$key]));
        }

        if( !$status ){
            throw new Exception('修改图表策略失败');
        }



    }


}// end class
