/**
 * Created by sean on 14-9-27.
 */
(function($) {

    function htmlspecialchars(str)
    {
        str = str.replace(/&amp;/gi, '&');
        str = str.replace(/&nbsp;/gi, ' ');
        str = str.replace(/&quot;/gi, '"');
        str = str.replace(/&#39;/g, "'");
        str = str.replace(/&lt;/gi, '<');
        str = str.replace(/&gt;/gi, '>');
        str = str.replace(/<br[^>]*>(?:(rn)|r|n)?/gi, 'n');
        return str;
    }

    function getInputValue(eventer,params){
        if( $(eventer).attr('id').substr(-2) == '[]' ){
            var key =  $(eventer).attr('id').substr(0,$(eventer).attr('id').length-2);
            if( params[key] == undefined){
                params[key] = new Array();
            }
            params[key].push($(eventer).val());
        }else{
            params[$(eventer).attr('id')] = $(eventer).val();
        }
    }

    function fillHttpParam(key,value){
        key =  (key==undefined ) ? '' : key;
        value =  (value==undefined ) ? '' : value;

        var dom = $('<div class="form-group outline">'+
            '<input type="text" class="form-control" name="parameter[]" id="parameter[]" placeholder="参数名" value="'+key+'">'+
            '<div class="form-control input-group-addon" style="width:auto;"> : </div>'+
            '<input type="text" class="form-control" name="value[]" id="value[]" placeholder="参数值" value="' + value +'">'+
            '<button class="btn btn-link delete">删除</button>'+
            '</div>');
        return dom;
    }

    var Render = {
        init : function(){
           Render.renderData();
           Render.afterRender();
           Render.dispatchButton();
        },
        dispatchButton:function(){
            $('#add_post_parms').click(function(){
                var dom = fillHttpParam();
                $('li.http .content .form-inline').append(dom);

            })

            $('li .content .form-inline').delegate('button.delete','click',function(){
                $(this).parent().remove();
            });

            $('#content_form button.submit').click(function(){

                var $form = $('#content_form');

                var params = {};
                $form.find('select').each(function() {
                    getInputValue(this,params);
                });
                $form.find('input[type="text"]').each(function() {
                    getInputValue(this,params);
                });

                $form.find('input[type="hidden"]').each(function() {
                    getInputValue(this,params);
                });

                $('.rms_hidden_form').find('input[type="hidden"]').each(function() {
                    getInputValue(this,params);
                });

                $form.find('input[type="radio"]:checked').each(function() {
                    params[$(this).attr('name')] = $(this).val();
                });
                $form.find('textarea').each(function() {
                    getInputValue(this,params);
                });


                $('.has-error').attr('title','').removeClass('has-error');

                $.post("index.php?r=chart/submit",params,function(data){
                    if( data.status == 0){
                        if( data.hasOwnProperty('field') && $('#'+data.field).length > 0){
                            $('#'+data.field).parent().parent().addClass('has-error');
                            $('#'+data.field).focus();
                            $('#'+data.field).attr('title',data.message);
    //                        $('<p><strong>Hey!</strong> Sample ui-state-highlight style.</p>').highlight();
                            $('#'+data.field).tooltip();
                        }else{
                            $("body, html").animate({
                                scrollTop: "0px"
                            });
                            $.fn.MyTopMessageBar({message:data.message, cssClass:"MessageBarWarning"});
                            setTimeout(function(){$('#topMessageBar').fadeOut(1000,function(){$(this).remove()});},5000);
                        }
                    }else{
                        $("body, html").animate({
                            scrollTop: "0px"
                        });
                        $.fn.MyTopMessageBar({message:data.message, cssClass:"MessageBarOk"});
                        setTimeout(function(){window.location.href=$('#go_back').attr('href');},1000);
                    }

                });

                return false;
            })
        },
        renderData : function (){
            var plain_data = $('#chart_data').html()
            var data = eval('('+plain_data+')');
            for(var k in data){
                var dom = $('#'+k);
                if( dom.length == 1){
                    var form_element = dom.get(0);
                    if( form_element.tagName.toLowerCase() == 'input' || form_element.tagName.toLowerCase()=='textarea'){
                        dom.val(htmlspecialchars(data[k]));
                    }else if( form_element.tagName.toLowerCase() == 'select'){
                        dom.find("option[value='"+data[k] +"']").attr('selected',true);
                    }else if(form_element.tagName.toLowerCase() == 'div'){
                        dom.find("input[value='"+data[k] +"']").attr('checked',true);
                    }
                }
            }

            if( data.hasOwnProperty('parameter') && data.hasOwnProperty('value')
                && data.parameter.length == data.value.length
                ){
                for (var i=0; i<data.parameter.length;i++ ){
                    var dom = fillHttpParam(data.parameter[i],data.value[i]);
                    $('li.http .content .form-inline').append(dom);
                }
            }

        },
        afterRender : function (){
            Render.renderDataType();
        },
        renderDataType : function (){//根据选中的是htt还是mysql更新前端
            var value  = $("input[name='data_type']:checked").val();
            var dict = {
                "0": 'MySQL',
                "1": 'http'
            };
            value = dict[value]
            $('li.'+value).show();
            if( value == 'MySQL'){
                $('li.http').hide();
            }else{
                $('li.MySQL').hide();
            }
        }
    }

    var Event = {
        init: function() {
            Render.init();
            $("input[name='data_type']").click(function(event){
                Render.renderDataType();
            });
        }
    };

    $(Event.init);

})(jQuery);