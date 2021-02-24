define(['autosize',
        "char_limit",
        'jquery',
        'ko',
        'uiComponent',
        'mage/validation',
        'mage/storage',
        "mage/translate",
        "Magento_Customer/js/customer-data",
        "Magento_Customer/js/model/customer",
        "Magento_Ui/js/modal/confirm",
        'Klloom_ProductComments/js/action/save-comment',
        'Klloom_ProductComments/js/action/load-comment',
        'Klloom_ProductComments/js/action/delete-comment'
    ], function (autosize, charLimit, $, ko, Component, validation, storage, $t, customerData, customer, confirm, saveAction, loadAction, deleteAction) {
        'use strict';

        var comments = ko.observableArray([]);

        return Component.extend({
            defaults                 : {
                template: self.templateHtml
            },
            currentAvatar            : ko.observable(),
            templateHtml             : ko.observable(),
            htmlLoggedMessage        : ko.observable(),
            isLoggedIn               : ko.observable(),
            login_url                : ko.observable(),
            isCustomerLoggedIn       : customer.isLoggedIn,
            customer                 : ko.observable({}),
            isEnabled                : ko.observable(false),
            isVisible                : ko.observable(false),
            initialize               : function () {
                this._super();
                var self = this;
                this.templateHtml(self.template);
                //this.isVisible(false);
                this.currentAvatar(self.currentSellerAvatar);
                this.login_url(self.loginUrl);
                if (this.isCustomerLoggedIn()) {
                    this.isLoggedIn(true);
                }
                this.checkCustomerLocalStorage();
                this.load();
            },
            textAreaBehavior         : function () {
                autosize($('textarea'));
                var maxLength = 200;
                var charLeft  = $('p.char-left');
                $('#comment_field').charLimit({
                    limit  : maxLength,
                    counter: charLeft
                });
                this.isEnabled(true);
            },
            checkCustomerLocalStorage: function () {
                var self = this;
                var time = setInterval(function () {
                    self.customer = customerData.get('customer');
                    if (localStorage["mage-cache-storage"] != '{}') {
                        clearInterval(time);
                    }
                    if (self.customer().fullname) {
                        self.isLoggedIn(true);
                    }
                }, 100);
            },
            save                     : function (saveForm) {
                var self          = this;
                var saveData      = {},
                    formDataArray = $(saveForm).serializeArray();

                formDataArray.push({name: 'product_id', value: self.product});
                formDataArray.push({name: 'form_key', value: self.form_key});

                formDataArray.forEach(function (entry) {
                    saveData[entry.name] = entry.value;
                });

                if ($(saveForm).validation() && $(saveForm).validation('isValid')) {
                    self.isEnabled(false);
                    saveAction(saveData, comments).always(function (data) {
                        self.isEnabled(true);
                        $('#comment_field').val('');
                        self.isVisible(data.length);
                    });
                }
            },
            load                     : function () {
                var self = this;
                loadAction({"product_id": self.product}, comments).always(function (data) {
                    self.isVisible(data.length);
                });
            },
            removeComment            : function (item, event) {
                var self = this;
                confirm({
                    title  : 'Comment <i class="icon-comment-klloom"></i>',
                    content: $.mage.__('<span class="color-text-light-blue">Confirm to delete this comment:</span> <br/><br/> <span style="font-size: 12px; line-height: 16px; display: inline-block">'+item.comment+'</span>'),
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
                            deleteAction(item.canRemove, comments).always(function () {
                                comments.remove(item);
                                // TODO Fix to hide HR element when array is empty
                                //self.isVisible(comments().length);
                                return comments;
                            });
                        }
                    }
                });

            },
            getComments              : function () {
                return comments;
            }
        });
    }
);