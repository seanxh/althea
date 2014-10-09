/**
 * Created by sean on 14-9-30.
 */
(function($) {

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

    var Render = {
        init : function(){
            Render.renderData();
            Render.afterRender();
            Render.dispatchButton();
        },
        dispatchButton:function(){

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

                $form.find('textarea').each(function() {
                    getInputValue(this,params);
                });


                $('.has-error').attr('title','').removeClass('has-error');

                $.post("index.php?r=alert/submit",params,function(data){
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
            var plain_data = $('#alert_data').html();
            var data = eval('('+plain_data+')');
            console.log(data);
            for(var k in data){
                var dom = $('#'+k);
                if( dom.length == 1){
                    var form_element = dom.get(0);
                    if( form_element.tagName.toLowerCase() == 'input' || form_element.tagName.toLowerCase()=='textarea'){
                        dom.val(data[k]);
                    }else if( form_element.tagName.toLowerCase() == 'select'){
                        dom.find("option[value='"+data[k] +"']").attr('selected',true);
                    }else if(form_element.tagName.toLowerCase() == 'div'){
                        dom.find("input[value='"+data[k] +"']").attr('checked',true);
                    }
                }
            }

        },
        afterRender : function (){
        }
    }

    var Event = {
        init: function() {
            Render.init();
        }
    };

    $(Event.init);

})(jQuery);