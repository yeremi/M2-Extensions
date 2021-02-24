define([
    'require',
    "Magento_Ui/js/modal/confirm",
    "Magento_Ui/js/modal/alert",
    "Magento_Customer/js/customer-data",
    "js/isotope.pkgd",
    "jquery",
    "jquery/ui",
    "mage/validation"

], function (require, confirm, alert, customerData, Isotope, $) {
    "use strict";

    var collection;

    require(['jquery-bridget/jquery-bridget'],
        function (jQueryBridget) {
            jQueryBridget('isotope', Isotope, $);
            var $grid  = $('.klloom-grid');
            collection = $grid.isotope();
        }
    );

    function main(config, element) {

        $(document).on('click', '.klloom-remove-mention', function () {
            var elm = $(this);
            var pi  = elm.data('pi');

            event.preventDefault();

            var customer = customerData.get('customer');
            if (!customer().firstname) {

                $.cookie('login_redirect', window.location.href);

                alert({
                    title           : "Remove Mention <i class='icon-delete-klloom'></i>",
                    content         : "<p>You need to be logged in to delete this comment. Click <a href='" + config.redirect + "'>here to Login</a> or <a href='/customer/account/create'>Create an Account</a></p>",
                    clickableOverlay: false,
                    buttons         : false
                });

            } else {

                confirm({
                    title  : 'Remove Mention <i class="icon-delete-klloom"></i>',
                    content: $.mage.__('<span class="color-text-light-blue">Confirm to remove this mention:</span>'),
                    buttons: [{
                        text : $.mage.__('Cancel'),
                        class: 'action-secondary action-dismiss',
                        click: function (event) {
                            this.closeModal(event);
                        }
                    }, {
                        text : $.mage.__('Remove'),
                        class: 'action-primary action-accept',
                        click: function (event) {
                            this.closeModal(event, true);
                        }
                    }],
                    actions: {
                        confirm: function () {
                            $.ajax({
                                url : config.ajaxUrl + 'id/' + pi,
                                type: "DELETE"
                            }).done(function (data) {
                                elm.parent().parent().css({'backgroundColor': '#c6c6c5'}).fadeOut('fast', function () {
                                    elm.parent().parent().remove();
                                    collection.isotope('layout');
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