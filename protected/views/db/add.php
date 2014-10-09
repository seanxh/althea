<div class="bodyWrapper">
    <form class="form-horizontal" enctype="multipart/form-data" id="content_form" action=""
          method="post">
        <input class="form-control" type="hidden" value="" name="id" id="id">
        <ul class="section">
            <li class="sectionTitle">
                <h2>创建数据库</h2>
            </li>
            <li class="form-group">
                <span class="name col-sm-2 control-label">数据库代号</span>

                <div class="col-sm-10">
                    <input class="form-control" type="text" value="" name="name" id="name" placeholder="请输入数据库代号，可中文">
                </div>
            </li>
            <li class="form-group">
                <span class="name col-sm-2 control-label">数据库名</span>

                <div class="col-sm-10">
                    <input class="form-control" type="text" value="" name="dbname" id="dbname"
                           placeholder="请输入数据库名,必须为英文字母">
                </div>
            </li>
            <li class="form-group">
                <span class="name col-sm-2 control-label">Host</span>

                <div class="col-sm-10">
                    <input class="form-control" type="text" value="" name="host" id='host' placeholder="请输入主机名">
                </div>
            </li>
            <li class="form-group">
                <span class="name col-sm-2 control-label">端口</span>

                <div class="col-sm-10">
                    <input class="form-control" type="text" value="3306" name="port" id="port" placeholder="请输入端口">
                </div>
            </li>
            <li class="form-group">
                <span class="name col-sm-2 control-label">用户名</span>

                <div class="col-sm-10">
                    <input class="form-control" type="text" value="" name="user" id="user" placeholder="请输入用户名">
                </div>
            </li>
            <li class="form-group">
                <span class="name col-sm-2 control-label">密码</span>

                <div class="col-sm-10">
                    <input class="form-control" type="text" value="" name="passwd" id="passwd" placeholder="请输入密码">
                </div>
            </li>
            <li class="sectionConfirm">
                <span class="col-lg-10"></span>
                <button class="submit btn btn-success">创建</button>
                <a id="go_back" class="btn btn-warning" href="<?php echo Yii::app()->baseUrl; ?>/index.php?r=db">返回</a>
            </li>
        </ul>
    </form>
</div>

<div id="db_data" style="display: none;"><?php echo htmlspecialchars(json_encode($data)); ?></div>
<script>
    <?php include_once(dirname(__FILE__) . '/add.js'); ?>
</script>