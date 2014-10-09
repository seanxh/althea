<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <!-- Bootstrap core CSS -->
    <link href="<?php echo Yii::app()->baseUrl;?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo Yii::app()->baseUrl;?>/assets/jqueryui/jquery-ui-1.10.0.custom.css" rel="stylesheet">


    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="<?php echo Yii::app()->baseUrl;?>/assets/bootstrap/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?php echo Yii::app()->baseUrl;?>/assets/bootstrap/js/ie-emulation-modes-warning.js"></script>
    <script src="<?php echo Yii::app()->baseUrl?>/assets/jquery.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl?>/assets/jqueryui/jquery-ui-1.10.0.custom.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl;?>/assets/messagebar/jquery.MyTopMessageBar.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl?>/assets/datatable/css/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl?>/assets/messagebar/MyTopMessageBar.css">
    <script type="text/javascript" language="javascript" src="<?php echo Yii::app()->baseUrl?>/assets/datatable/js/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo Yii::app()->baseUrl?>/assets/datatable/js/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo Yii::app()->baseUrl?>/assets/table.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo Yii::app()->baseUrl;?>/assets/bootstrap/js/html5shiv.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl;?>/assets/bootstrap/js/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        body {
            padding-top: 70px;
        }
        .section{
            width: 70%;
            margin: 40px auto;
            background: #FFFFFF;
            box-shadow: 0 2px 40px 0 rgba(0, 0, 0, 0.4);
            border-radius: 3px;
            margin-bottom: 14px;
            overflow: hidden;
        }
        .section li{
            list-style: none;
            padding: 15px 21px 15px 27px;
            border-top: 1px solid #EFEFEF;
            margin-bottom: 2px;
        }
        .section li .name{
            text-align: left;
            font-size: 15px;
        }
        .content .outline{
            margin-bottom: 15px;
        }
    </style>
    <title>Althea---创建新监控</title>

</head>

<body class="ashley personal  create-widget prod">
<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo Yii::app()->baseUrl?>/index.php"><?php echo Yii::app()->name?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="index active"><a href="<?php echo Yii::app()->baseUrl?>/index.php">首页</a></li>
                <li class="monitor"><a href="<?php echo Yii::app()->baseUrl?>/index.php?r=monitor">监控</a></li>
                <li class="chart"><a href="<?php echo Yii::app()->baseUrl?>/index.php?r=chart">图表</a></li>
                <li class="db"><a href="<?php echo Yii::app()->baseUrl?>/index.php?r=db">数据库</a></li>
                <li class="alert" style="padding:0;margin-bottom:0;"><a href="<?php echo Yii::app()->baseUrl?>/index.php?r=alert">报警组</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="help active"><a href="<?php echo Yii::app()->baseUrl?>/index.php?r=help">帮助</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>


<?php echo $content;?>
</body>
<script>
(function($) {
    var controller = '<?php echo Yii::app()->controller->id ?>';

    $('#navbar li.active').removeClass('active');
    $('#navbar li.'+controller).addClass('active');

})(jQuery);
</script>
<script src="<?php echo Yii::app()->baseUrl?>/assets/bootstrap/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="<?php echo Yii::app()->baseUrl;?>/assets/bootstrap/js/ie10-viewport-bug-workaround.js"></script>
</html>