/**
 * Created by sean on 14-9-28.
 */
(function(window,document,undefined){
    $.fn.extend({
        table: function(option) {

            if( option.hasOwnProperty('tags') && typeof(option.tags) == 'object'){
                var thead = $("<thead></thead>>");
                var tr = $("<tr></tr>");

                var tags = option.tags;
                var columns = [];
                for( k in tags){
                    var column = {'data':k};
                    if( typeof(tags[k]) == 'string'){
                        tr.append('<th>' + tags[k] + '</th>')
                    }else if(typeof(tags[k]) == 'object'){
                        tr.append('<th>' + tags[k]['title'] + '</th>')
                        delete tags[k].title;
                        $.extend(true,column,tags[k]);
                        if( tags[k].hasOwnProperty('render') ){
                            column.render = function(data, type, full, meta){
                                var k = meta.settings.aoColumns[meta.col].data;
                                return tags[k].render(data, full);
                            }
                        }
                    }
                    columns.push( column );
                }

                thead.append(tr);
                $(this).append(thead);
                option.columns = columns;
                delete option.tags;
            }

            options = {
                "bLengthChange": false,
                "bSort": false,
                "sPaginationType":"full_numbers",
                "bPaginate" : true,
                "iDisplayLength" : 10,
                "processing": true,
                "serverSide": true,
                "searching" : false,
                "oLanguage": {
                    "sProcessing": "正在加载中......",
                    "sLengthMenu": "每页显示 _MENU_ 条记录",
                    "sZeroRecords": "对不起，查询不到相关数据！",
                    "sEmptyTable": "表中无数据存在！",
                    "sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_ 条记录",
                    "sInfoFiltered": "数据表中共为 _MAX_ 条记录",
                    "sSearch": "搜索",
                    "oPaginate": {
                        "sFirst": "首页",
                        "sPrevious": "上一页",
                        "sNext": "下一页",
                        "sLast": "末页"
                    }
                }
            };
            $.extend(true,options,option);
            $(this).dataTable(options);
            $(this).removeClass( 'display' ).addClass('table table-striped table-bordered');
        }
    });
}(window, document));