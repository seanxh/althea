/**
 * Created by sean on 14-9-28.
 */
(function($) {

    var Render = {
        init : function (){
            $('#example').table( {
                'tags':{
                    'id' : 'ID',
                    'name' : '名称',
                    'realtime' : {
                        'title':'实时',
                        'render':function(data,full){
                            if( data == 0){
                                return '否';
                            }else{
                                return '是';
                            }
                        }
                    },
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
                    'title': '标题',
                    'edit' : {
                        'title':'操作',
                        'render':function(data, full){
                            return '<a target="_blank" href="index.php?r=site/chart&id=' + full.id + '">查看</a>  '+
                                    '<a href="index.php?r=chart/add&id=' + full.id + '">编辑</a> '+
                                    '<a href="index.php?r=chart/add&id=' + full.id + '">删除</a>';
                        }
                    }
                },
                "iDisplayLength" : 10,
                "ajax": {
                    "url": "index.php?r=chart/list",
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