/**
 * Created by sean on 14-9-30.
 */
(function($) {

    var Render = {
        init : function (){
            $('#example').table( {
                'tags':{
                    'id' : 'ID',
                    'name' : '名称',
                    'dbname' : 'DB',
                    'host' : 'HOST',
                    'port':'端口',
                    'user': '用户',
                    'passwd':'密码',
                    'edit' : {
                        'title':'操作',
                        'render':function(data,full){
                            return '<a href="index.php?r=db/add&id=' + full.id + '">编辑</a>  '+
                                '<a href="index.php?r=db/add&id=' + full.id + '">删除</a>';
                        }
                    }
                },
                "iDisplayLength" : 10,
                "ajax": {
                    "url": "index.php?r=db/list",
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