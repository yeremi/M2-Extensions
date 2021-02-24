define([
    "Magento_Ui/js/modal/confirm",
    "Magento_Ui/js/modal/alert",
    "Magento_Customer/js/customer-data",
    "jquery",
    "jquery/ui",
    "mage/validation",
    "ko",
    "uiComponent"
], function (confirm, alert, customerData, $, ko, Component) {
    "use strict";

    function main(config, element) {

        $(element).unbind('click').on('click', function () {

            var button       = $(this);
            var pcounter     = button.data('pcounter');
            var likesCounter = $('.likes-counter-' + pcounter);
            var currentLikes = parseInt(likesCounter.html(), 10);

            if (!config.isLoggedIn) {

                alert({
                    title           : "Like <i class='icon-like-klloom'></i>",
                    content         : "<p>You need to be logged in to like. Click <a href='" + config.loginUrl + "'>here to Login</a> or <a href='" + config.createAccountUrl + "'>Create an Account</a></p>",
                    clickableOverlay: false,
                    buttons         : false
                });

            } else {

                event.preventDefault();
                var url = button.data('url-like');
                if (url !== 'javascript:;') {

                    if (button.hasClass('icon-like-klloom')) {
                        button.removeClass('icon-like-klloom').addClass('icon-like-active-klloom');
                        likesCounter.html(currentLikes + 1);
                    } else {
                        if (button.hasClass('icon-like-active-klloom')) {
                            button.removeClass('icon-like-active-klloom').addClass('icon-like-klloom');
                            if (currentLikes > 0) {
                                likesCounter.html(currentLikes - 1);
                            }
                        }
                    }

                    $.ajax({
                        url     : url,
                        type    : "POST",
                        dataType: 'json'
                    });

                    if (window.location.href.indexOf("productlike/customer/productlikelist") > -1) {
                        var id = pcounter;
                        $('#' + id).fadeOut('fast').remove();
                    }

                }
            }

        });
    }

    return main;

});