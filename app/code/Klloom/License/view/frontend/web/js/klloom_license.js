define([
    "jquery",
    "jquery/ui",
    "js/jquery.webui-popover.min"
], function ($) {
    "use strict";
    $(document).ready(function () {

        //autosize($('textarea'));

        $("#klloom_price").before($('#klloom_license').css({display:'block'}));

        var settings = {
            trigger: 'click',
            title: false,
            width: 380,
            multi: false,
            padding: true,
            closeable: true,
            placement: 'auto',
            animation: 'pop',
            arrow: true,
            onHide: function($element) {
                $('a.show-license-resume').css({display: 'inline-block'})
            }
        };

        $('a.show-license-resume').webuiPopover('destroy').webuiPopover($.extend({}, settings));

        /**
         * Standard License
         * @type {*|jQuery|HTMLElement}

        var ckbox = $('input#klloom_standard_license');
        if (!ckbox.is(':checked')) {
            $('button#save-btn').attr("disabled", true);
        }
        $(ckbox).on('click',function () {
            if (ckbox.is(':checked')) {
                $('button#save-btn').attr("disabled", false);
            } else {
                $('button#save-btn').attr("disabled", true);
            }
        });
         */

    });
});