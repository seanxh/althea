<div class="container">
    <div class="page-header">
        <h1>Hello <small>Bidu</small></h1>
    </div>


    <script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/assets/althea.js"></script>

    <script type="text/javascript">
        $(function () {
            Althea.charts({
                'container' : $('#chart3'),
                'chart' : '3',
            });
            Althea.charts({
                'container' : $('#chart1'),
                'chart' : '1',
            });
        });
    </script>

    <div id="chart3" style="width:450px; height: 300px; margin: 5px;float: left;"></div>
    <div id="chart1" style="width:450px; height: 300px; margin: 5px;float:left;"></div>

</div>