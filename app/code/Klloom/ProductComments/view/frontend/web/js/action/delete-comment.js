define(['ko',
        'jquery',
        //'Magento_Checkout/js/model/url-builder',
        'mage/storage',
        'mage/translate'
    ], function (ko, $, storage, $t) {
        'use strict';
        return function (serviceUrl, commentTotal) {
            return storage.delete(serviceUrl, false);
        };
    }
);