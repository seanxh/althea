    <div class="bodyWrapper">
        <form class="form-horizontal" enctype="multipart/form-data" id="content_form" action=""
              method="post">
        <input class="form-control" type="hidden" value="" name="id" id="id">
        <ul class="section">
            <li class="sectionTitle">
                <h2>创建报警策略</h2>
            </li>
            <li class="form-group">
                <span class="name col-sm-2 control-label">名称</span>

                <div class="col-sm-10">
                    <input class="form-control" type="text" value="" name="alert_name" id="alert_name"  placeholder="请输入报警策略代号，可中文">
                </div>
            </li>
            <li class="form-group">
                <span class="name col-sm-2 control-label">邮件报警接收人</span>
                <div class="col-sm-10">
                    <input class="form-control" type="text" value="" name="mail_receiver" id="mail_receiver"  placeholder="请输入邮箱，多个以逗号分隔">
                </div>
            </li>
            <li class="form-group">
                <span class="name col-sm-2 control-label">短信接收人</span>
                <div class="col-sm-10">
                    <input class="form-control" type="text" value="" name="message_receiver" id="message_receiver"  placeholder="请输入短信接收人，多个以逗号分隔">
                </div>
            </li>
            <li class="form-group">
                <span class="name col-sm-2 control-label">URL回调</span>
                <div class="col-sm-10">
                    <input class="form-control" type="text" value="" name="url_receiver" id="url_receiver"  placeholder="请输入短信接收人，多个以逗号分隔">
                    <span class="help-block">每次报警时会回调此接口，接口格式请参考<a href="#">文档</a></span>
                </div>
            </li>
            <li class="form-group">
                <span class="name col-sm-2 control-label">制定报警策略</span>
                <div class="col-sm-10">
                    <input class="form-control" type="text" value="" name="rule" id="rule"  placeholder="请输入报警策略">
                    <span class="help-block">只允许某几个小时报区:hour/9,10,11,12,13 . 只允许某工作日报警: day/1,2,3,4,5 </span>
                </div>
            </li>
            <li class="sectionConfirm">
                <span class="col-lg-10"></span>
                <button class="submit btn btn-success">创建</button>
                <a id="go_back" class="btn btn-warning" href="<?php echo Yii::app()->baseUrl;?>/index.php?r=alert">返回</a>
            </li>
        </ul>
        </form>
    </div>
    <div id="alert_data" style="display: none;"><?php echo htmlspecialchars(json_encode($data)); ?></div>
    <script>
        <?php include_once(dirname(__FILE__) . '/add.js'); ?>
    </script>