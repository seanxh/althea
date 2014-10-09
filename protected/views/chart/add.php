<style type="text/css">
    body{
        background-color: #eeeeee;
    }
</style>
<div class="container" name="top">
    <form class="form-horizontal" enctype="multipart/form-data" id="content_form" action=""
          method="post">
        <ul class="section">
            <li class="sectionTitle">
                <h2>创建图表</h2>
            </li>

            <input type="hidden" value="0" name="id" id="id" />


            <li class="form-group">
                <label class="name col-sm-2 control-label" for="name">图表名称</label>
                <div class="col-sm-10">
                    <input placeholder="请输入监控名称" class="form-control" name="name" id="name" type="text"
                           maxlength="45" value=""/>
                    <span class="help-block">键入有标识性的图表的名称，方便查看与管理，可中文</span>
                </div>
            </li>
            <li class="form-group">
                <label class="name col-sm-2 control-label" for="dataType1">数据源</label>
                <div class="col-sm-10" id="data_type">
                    <label class="radio-inline">
                        <input type="radio" checked name="data_type" id="dataType1" value="0"> MySQL
                    </label>
                    <label class="radio-inline">
                        <input type="radio"  name="data_type" id="dataType1" value="1"> HTTP
                    </label>

                </div>
            </li>

            <li class="form-group MySQL">
                <label class="name col-sm-2 control-label" for="database_id">数据库</label>
                <div class="col-sm-10">
                   <select class="col-lg-2 form-control" name='database_id' id="database_id" style="width: 35%;">
                       <?php foreach($db as $id=>$name):?>
                       <option value="<?php echo $id;?>"><?php echo $name;?></option>
                       <?php endforeach;?>
                   </select>

                </div>
            </li>

            <li class="form-group MySQL">
                <label class="name col-sm-2 control-label" for="select_sql">查询条件</label>
                <div class="col-sm-10">
                    <textarea placeholder="请输入应用描述信息" class="form-control" rows="2" cols="50" name="select_sql"
                              id="select_sql"></textarea>
                    <span class="help-block">写入一个SQL用来查询数据源，SQL中可使用自定义函数，如[prevHour()]，详见<a href="#">文档</a></span>
                </div>
            </li>

            <li class="form-group http">
                <label class="name col-sm-2 control-label" for="data_url">URL</label>
                <div class="col-sm-10">
                    <input placeholder="请键入一个URL" class="form-control" name="data_url" id="data_url" type="text"
                           maxlength="45" value=""/>
                </div>
            </li>


            <li class="form-group http">
                <label class="name col-sm-2 control-label" for="post">POST参数</label>
                <div class="col-sm-10">
                    <span class="content row">
                        <div class="form-inline">
                        </div>
                    </span>
                    <span class="btn btn-info" id="add_post_parms">添加</span>
                </div>
            </li>

            <li class="form-group">
                <label class="name col-sm-2 control-label" for="expression">表达式</label>
                <div class="col-sm-10">
                    <input placeholder="运算表达式" class="form-control" name="expression" id="expression" type="text"
                           maxlength="45" value=""/>
                    <span class="help-block">判定表达式用于对数据源进行过滤报警，语法参考<a href="#">文档</a></span>
                </div>
            </li>

            <li class="form-group">
                <label class="name col-sm-2 control-label" for="title">标题</label>
                <div class="col-sm-10">
                    <input placeholder="请输入标题" class="form-control" name="title" id="title" type="text"
                           maxlength="45" value=""/>
                    <span class="help-block">键入图表标题，中英文，不能为空</span>
                </div>
            </li>

            <li class="form-group">
                <label class="name col-sm-2 control-label" for="subtitle">副标题</label>
                <div class="col-sm-10">
                    <input placeholder="请输入图表" class="form-control" name="subtitle" id="subtitle" type="text"
                           maxlength="45" value=""/>
                    <span class="help-block">键入图表副标题，中英文，可留空</span>
                </div>
            </li>


            <li class="form-group">
                <label class="name col-sm-2 control-label" for="y_title">纵坐标</label>
                <div class="col-sm-10">
                    <input placeholder="纵坐标" class="form-control" name="y_title" id="y_title" type="text"
                           maxlength="45" value=""/>
                </div>
            </li>

            <li class="form-group">
                <label class="name col-sm-2 control-label" for="y_title">图表类型</label>
                <div class="col-sm-10" id="type">
                    <label class="radio-inline">
                        <input type="radio" checked name="type" id="type1" value="0"> 折线图
                    </label>
                    <label class="radio-inline">
                        <input type="radio"  name="type" id="type2" value="1"> 区域图
                    </label>
                    <label class="radio-inline">
                        <input type="radio"  name="type" id="type3" value="2"> 柱状图
                    </label>

                </div>
            </li>

            <li class="form-group">
                <label class="name col-sm-2 control-label" for="theme0">图表外观</label>
                <div class="col-sm-10" id="theme">
                    <label class="radio-inline">
                        <input type="radio" checked name="theme" id="theme0" value="0"> 默认
                    </label>
                    <label class="radio-inline">
                        <input type="radio"  name="theme" id="theme1" value="1"> 灰色
                    </label>
                    <label class="radio-inline">
                        <input type="radio"  name="theme" id="theme2" value="2"> 蓝色
                    </label>
                    <label class="radio-inline">
                        <input type="radio"  name="theme" id="theme3" value="3"> 绿色
                    </label>
                    <label class="radio-inline">
                        <input type="radio"  name="theme" id="theme4" value="4"> grid
                    </label>
                    <label class="radio-inline">
                        <input type="radio"  name="theme" id="theme5" value="5"> 天蓝
                    </label>

                </div>
            </li>

            <li class="form-group">
                <label class="name col-sm-2 control-label" for="realtime1">是否实时变换</label>
                <div class="col-sm-10" id="status">
                    <label class="radio-inline">
                        <input type="radio" checked name="realtime" id="realtime1" value="1"> 是
                    </label>
                    <label class="radio-inline">
                        <input type="radio"  name="realtime" id="realtime2" value="0"> 否
                    </label>

                </div>
            </li>

            <li class="form-group">
                <label class="name col-sm-2 control-label" for="cycle">周期</label>
                <div class="col-sm-10">
                    <input placeholder="周期" class="form-control" name="cycle" id="cycle" type="text"
                           maxlength="45" value=""/>
                </div>
            </li>

            <li class="form-group">
                <label class="name col-sm-2 control-label" for="max_points">最多点数</label>
                <div class="col-sm-10">
                    <input placeholder="最多点数" class="form-control" name="max_points" id="max_points" type="text"
                           maxlength="45" value=""/>
                </div>
            </li>



            <li class="form-group">
                <label class="name col-sm-2 control-label" for="status1">状态</label>
                <div class="col-sm-10" id="status">
                    <label class="radio-inline">
                        <input type="radio" checked name="status" id="status1" value="1"> 启用
                    </label>
                    <label class="radio-inline">
                        <input type="radio"  name="status" id="status2" value="0"> 禁用
                    </label>

                </div>
            </li>

            <li class="sectionConfirm">
                <span class="col-lg-10"></span>
                <button class="submit btn btn-success">创建</button>
                <a id="go_back" class="btn btn-warning" href="<?php echo Yii::app()->baseUrl;?>/index.php?r=chart">返回</a>
            </li>
        </ul>
    </form>
</div>

<div id="add_post_params" style="display: none;">
    <div class="form-inline" role="form">
    <div class="form-group">
        <input type="text" class="form-control" name="parameter" id="exampleInputEmail2" placeholder="参数名">
        <div class="form-control input-group-addon" style="width:auto;"> : </div>
        <input type="text" class="form-control" name="value" id="exampleInputEmail2" placeholder="参数值">
    </div>
    </div>
<!--    <div class="form-group">-->
<!--        <div class="input-group col-lg-2">-->
<!--            <input class="form-control" type="email" placeholder="Enter email">-->
<!--        </div>-->
<!--        <div class="input-group col-lg-2">-->
            <select class="form-control">
                <option><</option>
                <option><=</option>
                <option>></option>
                <option>>=</option>
            </select>
<!--        </div>-->
<!--        <div class="input-group">-->
<!--            <input class="form-control" type="email" placeholder="Enter email">-->
<!--        </div>-->
<!--    </div>-->

</div>

<div id="chart_data"  style="display: none;"><?php echo htmlspecialchars(json_encode($data));?></div>
<script>
    <?php include_once(dirname(__FILE__) . '/add.js'); ?>
</script>