define([
    "Magento_Ui/js/modal/confirm",
    'Magento_Ui/js/modal/alert',
    'Magento_Customer/js/model/authentication-popup',
    'Magento_Customer/js/customer-data',
    "jquery",
    "jquery/ui",
    "js/jquery.webui-popover.min"
], function (confirm, alert, authenticationPopup, customerData, $) {
    "use strict";

    function main(config, element) {
        var $element = $(element);

        $('a.klloom-report-tooltip').webuiPopover('destroy').webuiPopover($.extend({}, {
            trigger  : 'click',
            title    : false,
            width    : 250,
            multi    : false,
            padding  : true,
            closeable: true,
            placement: 'auto-bottom',
            animation: 'pop',
            arrow    : true,
            style    : 'klloom-report'
        }));

        $(document).on('click', '.report-item', function (event) {
            event.preventDefault();

            $('a.klloom-report-tooltip').webuiPopover('hide');
            var customer = customerData.get('customer');
            if (!customer().firstname) {
                $.cookie('login_redirect', window.location.href);

                alert({
                    title           : "Report <i class='icon-report-klloom'></i>",
                    content         : "<p>You need to be logged in to report.<br /><a href='" + config.redirect + "'>Click here to Login</a> or <a href='/customer/account/create'>Create an Account</a></p>",
                    clickableOverlay: false,
                    buttons         : false
                });

            } else {
                var obj = {
                    product_id: config.productId,
                    report    : $(this).data('report')
                };
                confirm({
                    title  : 'Report <i class="icon-report-klloom"></i>',
                    content: 'Confirm your report of this photo as: <span style="color: #35a8e0;">' + obj.report + '</span>',
                    buttons: [{
                        text : $.mage.__('Cancel'),
                        class: 'action-secondary action-dismiss',
                        click: function (event) {
                            this.closeModal(event);
                        }
                    }, {
                        text : $.mage.__('Report'),
                        class: 'action-primary action-accept',
                        click: function (event) {
                            this.closeModal(event, true);
                        }
                    }],
                    actions: {
                        confirm: function () {
                            $.ajax({
                                showLoader: true,
                                url       : config.ajaxUrl,
                                data      : obj,
                                type      : "POST",
                                dataType  : 'json'
                            }).done(function (data) {
                                $('a.klloom-report-tooltip').webuiPopover('hide');
                            });
                        }
                    }
                });
            }
        });
    }

    return main;
});