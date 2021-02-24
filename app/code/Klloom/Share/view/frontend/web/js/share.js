define([
    "jquery",
    "jquery/ui",
    "js/jquery.webui-popover.min"
], function ($) {
    "use strict";

    return function () {

        $.fn.customerPopup = function (e, intWidth, intHeight, blnResize) {
            // Prevent default anchor event
            e.preventDefault();

            // Set values for window
            intWidth = intWidth || "500";
            intHeight = intHeight || "400";
            var strResize = blnResize ? "yes" : "no";

            // Set title and open popup with focus on it
            var strTitle  =
                    typeof this.attr("title") !== "undefined"
                        ? this.attr("title")
                        : "Social Share",
                strParam  =
                    "width=" + intWidth +
                    ",height=" + intHeight +
                    ",resizable=" + strResize,
                objWindow = window.open(this.attr("href"), strTitle, strParam).focus();
        };

        $(document).ready(function ($) {

            $('.show-shareThis').appendTo($('.klloom-counters-label')).css({display: 'inline-block'});

            var settings = {
                trigger  : 'click',
                title    : false,
                width    : 38,
                multi    : false,
                padding  : false,
                closeable: false,
                placement: 'auto-top',
                animation: 'pop',
                arrow    : true,
                style    : 'klloom-share-options'
            };
            $('a.show-shareThis').webuiPopover('destroy').webuiPopover($.extend({}, settings, {
                content: $('#show-shareThis-content').html()
            }));

            $(document).on('click', '.webui-popover-klloom-share-options a', function (e) {
                $(this).customerPopup(e);
                $('a.show-shareThis').webuiPopover('hide');
                //e.preventDefault();
            });

        });
    };
});