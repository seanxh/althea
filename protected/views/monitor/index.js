/**
 * Created by sean on 14-9-28.
 */
(function($) {

    var Render = {
        init : function (){
            $('#example').table( {
                'tags':{
                    'id' : 'ID',
                    'monitor_name' : '名称',
                    'cycle' : '周期(秒)',
                    'status' :  {
                        'title':'状态',
                        'render':function(data,full){
                            if( data == 0){
                                return '禁用';
                            }else{
                                return '启用';
                            }
                        }
                    },
                    'alert_title': '报警标题',
                    'edit' : {
                        'title':'操作',
                        'render':function(data,full){
                            return '<a href="index.php?r=monitor/add&id=' + full.id + '">编辑</a>  '+
                                    '<a href="index.php?r=monitor/add&id=' + full.id + '">删除</a>';
                        }
                    }
                },
                "iDisplayLength" : 10,
                "ajax": {
                    "url": "index.php?r=monitor/list",
                    "type":'POST',
                    "data": {
                    }
                }
            } );
        }

    }

    var Event = {
        init: function() {
            Render.init();
        }
    };
    $(Event.init);

})(jQuery);