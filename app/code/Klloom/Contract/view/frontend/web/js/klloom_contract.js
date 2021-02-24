define([
    "jquery",
    "jquery/ui"

], function ($) {
    "use strict";
    $(document).ready(function () {

        $("#klloom_price").after($('#klloom_contract').css({display:'block'}));

        /**
         * Terms Agreement
         * @type {*|jQuery|HTMLElement}
         */
        var ckbox = $('input#klloom_contract_input');
        /*if (!ckbox.is(':checked')) {
            $('button#save-btn').attr("disabled", true);
        }*/
        $(ckbox).on('click',function () {
            var hasImage = $('#klloom-photo-size').val();
            var hasPrice = $('#product-price').val();

            if (ckbox.is(':checked') && hasPrice !== '' && hasImage !== '') {
                $('button#save-btn').attr("disabled", false);
            } else {
                $('button#save-btn').attr("disabled", true);
            }

        });

    });
});