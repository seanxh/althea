/**
 * Created by sean on 14-9-29.
 */
/**
 * @author Paul Chan / KF Software House
 * http://www.kfsoft.info
 *
 * Version 0.5
 * Copyright (c) 2010 KF Software House
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 *
 */

(function($) {

    var _options = null;

    jQuery.fn.MyTopMessageBar = function(options) {
        _options = $.extend({}, $.fn.MyTopMessageBar.defaults, options);
        var closeHtml = " <DIV id=messageBarCloseBtn><img src='" + _options.closeImage + "'></DIV>";

        $("#topMessageBar").remove();

        $("body").prepend("<DIV id='topMessageBar'>" + _options.message +  closeHtml + "</DIV>");
        $("#topMessageBar").addClass(_options.cssClass);

        if (_options.bFading)
        {
            $("#topMessageBar").fadeIn();
            $("#messageBarCloseBtn").fadeIn();
        }
        else
        {
            $("#topMessageBar").slideDown();
            $("#messageBarCloseBtn").slideDown();
        }

        $("#messageBarCloseBtn").live("click", function(){
            if (_options.bFading)
            {
                $("#messageBarCloseBtn").fadeOut();
                $("#topMessageBar").fadeOut();
            }
            else
            {
                $("#messageBarCloseBtn").hide();
                $("#topMessageBar").slideUp();
            }
        });
    }

    //default values
    jQuery.fn.MyTopMessageBar.defaults = {
        message: "Notification",
        bFading: false,
        cssClass: "MessageBarOk",
        closeImage: "assets/messagebar/close.png"
    };

})(jQuery);