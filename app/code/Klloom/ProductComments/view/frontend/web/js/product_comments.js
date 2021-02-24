define([
    "char_limit",
    "Magento_Ui/js/modal/confirm",
    "Magento_Ui/js/modal/alert",
    "Magento_Customer/js/customer-data",
    "jquery",
    "jquery/ui",
    "mage/validation"
], function (charLimit, confirm, alert, customerData, $) {
    "use strict";

    $(document).ready(function () {
        $('.klloom-flat-button').prop('disabled', false);
        //autosize($('textarea'));
    });

    function main(config, element) {

        var maxLength = 200;
        var textarea  = $(element).find('#comment_field');
        var charLeft  = $(element).find('p.char-left');

        textarea.charLimit({
            limit  : maxLength,
            counter: charLeft
        });

        var dataForm = $(element);
        dataForm.mage('validation', {});

        $(document).on('click', '.klloom-flat-button', function () {
            var button   = $(this);
            var customer = customerData.get('customer');
            if (!customer().firstname) {
                $.cookie('login_redirect', window.location.href);

                alert({
                    title           : "Comments <i class='icon-comment-klloom'></i>",
                    content         : "<p>You need to be logged in to comment. Click <a href='" + config.redirect + "'>here to Login</a> or <a href='/customer/account/create'>Create an Account</a></p>",
                    clickableOverlay: false,
                    buttons         : false
                });

            } else {
                if (dataForm.valid()) {
                    event.preventDefault();
                    $.ajax({
                        showLoader: true,
                        url       : config.ajaxUrl,
                        data      : dataForm.serialize() + '&product_id=' + config.productId,
                        type      : "POST",
                        beforeSend: function () {
                            button.prop("disabled", true);
                        }
                    }).done(function (data) {
                        console.log(data)
                        //document.getElementById("comment-form").reset();
                        //button.prop('disabled', false);
                    }).complete(function () {
                        //location.reload();
                    });
                }
            }

        }).on('click', 'ul.klloom-mini-comment li a', function () {
            var elm = $(this);
            var ci  = elm.data('digest');
            var pi  = elm.data('pi');
                event.preventDefault();

            var customer = customerData.get('customer');
            if (!customer().firstname) {

                $.cookie('login_redirect', window.location.href);

                alert({
                    title           : "Comments <i class='icon-comment-klloom'></i>",
                    content         : "<p>You need to be logged in to delete this comment. Click <a href='" + config.redirect + "'>here to Login</a> or <a href='/customer/account/create'>Create an Account</a></p>",
                    clickableOverlay: false,
                    buttons         : false
                });

            } else {

                confirm({
                    title  : 'Comment <i class="icon-comment-klloom"></i>',
                    content: $.mage.__('<span class="color-text-light-blue">Confirm to delete this comment:</span> <br/><br/> <span style="font-size: 12px; line-height: 16px; display: inline-block">') + elm.parent().find('span.mdl-list__item-text-body').html() + '</span>',
                    buttons: [{
                        text : $.mage.__('Cancel'),
                        class: 'action-secondary action-dismiss',
                        click: function (event) {
                            this.closeModal(event);
                        }
                    }, {
                        text : $.mage.__('Delete Comment'),
                        class: 'action-primary action-accept',
                        click: function (event) {
                            this.closeModal(event, true);
                        }
                    }],
                    actions: {
                        confirm: function () {
                            $.ajax({
                                url : config.ajaxUrlDelete + '?ci=' + ci + '&pi=' + pi,
                                type: "DELETE"
                            }).done(function (data) {
                                elm.parent().css({'backgroundColor': '#c6c6c5'}).fadeOut('fast', function () {
                                    elm.remove();
                                });
                            });
                        }
                    }
                });
            }

        });
    }

    return main;

});