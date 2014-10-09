/**
 * Created by sean on 14-9-30.
 */
(function($) {

    var Render = {
        init : function (){
            $('#example').table( {
                'tags':{
                    'id' : 'ID',
                    'alert_name' : '名称',
                    'mail_receiver' : '邮件',
                    'message_receiver' : '短信',
                    'url_receiver' : '回调',
                    'rule' : '报警规则',
                    'edit' : {
                        'title':'操作',
                        'render':function(data,full){
                            return '<a href="index.php?r=alert/add&id=' + full.id + '">编辑</a>  '+
                                '<a href="index.php?r=alert/add&id=' + full.id + '">删除</a>';
                        }
                    }
                },
                "iDisplayLength" : 10,
                "ajax": {
                    "url": "index.php?r=alert/list",
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