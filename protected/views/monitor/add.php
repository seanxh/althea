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
                <h2>创建监控</h2>
            </li>

            <input type="hidden" value="0" name="id" id="id" />


            <li class="form-group">
                <label class="name col-sm-2 control-label" for="monitor_name">监控名称</label>
                <div class="col-sm-10">
                    <input placeholder="请输入监控名称" class="form-control" name="monitor_name" id="monitor_name" type="text"
                           maxlength="45" value=""/>
                    <span class="help-block">键入有标识性的监控的名称，方便查看与管理，可中文</span>
                </div>
            </li>
            <li class="form-group">
                <label class="name col-sm-2 control-label" for="cycle">监控周期</label>
                <div class="col-sm-10">
                    <div class="input-group">
                    <input placeholder="不小于1的整数" class="form-control" name="cycle" id="cycle" type="text"
                           maxlength="45" value=""/>
                    <div class="input-group-addon" style="width:auto;">秒</div>
                    </div>
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


            <li class="form-group expression">
                <label class="name col-sm-2 control-label" for="expression">判定表达式</label>
                <div class="col-sm-10">
                      <span class="content row">
                        <div class="form-inline">
                        </div>
                    </span>

                    <span class="btn btn-info" id="add_expression">添加</span>
                    <span class="help-block">判定表达式用于对数据源进行过滤报警，语法参考<a href="#">文档</a></span>
                </div>
            </li>


            <li class="form-group">
                <label class="name col-sm-2 control-label" for="condition_logic_operator">表达式运算</label>
                <div class="col-sm-10">
                    <input placeholder="表达式逻辑运算" class="form-control" name="condition_logic_operator" id="condition_logic_operator" type="text"
                           maxlength="45" value=""/>
                    <span class="help-block">判定表达式小于一个时，不需要关心此项。此项针对第表达式有如下算法(1 and 2) or (2 and 3). 用来判定当前项是否需要报警</span>
                </div>
            </li>

            <li class="form-group">
                <label class="name col-sm-2 control-label" for="alert_deploy_id">报警策略</label>
                <div class="col-sm-10">
                    <select class="col-lg-2 form-control" name="alert_deploy_id" id="alert_deploy_id" style="width: 35%;">
                        <?php foreach($alert as $id=>$name):?>
                            <option value="<?php echo $id;?>"><?php echo $name;?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </li>

            <li class="form-group">
                <label class="name col-sm-2 control-label" for="alert_title">报警标题</label>
                <div class="col-sm-10">
                    <textarea placeholder="请输入应用描述信息" class="form-control" rows="2" cols="50" name="alert_title"
                              id="alert_title"></textarea>
                    <span class="help-block">键入有标识性的监控的名称，方便查看与管理，可中文</span>
                </div>
            </li>
            <li class="form-group">
                <label class="name col-sm-2 control-label" for="alert_head">报警头</label>

                <div class="col-sm-10">
                    <textarea placeholder="请输入应用描述信息" class="form-control" rows="2" cols="50" name="alert_head"
                              id="alert_head"></textarea></div>
            </li>
            <li class="form-group">
                <label class="name col-sm-2 control-label" for="alert_content">报警内容</label>

                <div class="col-sm-10">
                    <textarea placeholder="请输入应用描述信息" class="form-control" rows="2" cols="50" name="alert_content"
                              id="alert_content"></textarea></div>
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
                <a id="go_back" class="btn btn-warning" href="<?php echo Yii::app()->baseUrl;?>/index.php?r=monitor">返回</a>
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

<div id="monitor_data"  style="display: none;" value='<?php echo htmlspecialchars(json_encode($data));?>'></div>
<script>
    <?php include_once(dirname(__FILE__) . '/add.js'); ?>
</script>