define(['ko',
        'jquery',
        //'Magento_Checkout/js/model/url-builder',
        'mage/storage',
        'mage/translate'
    ], function (ko, $, storage, $t) {
        'use strict';
        return function (commentData, commentTotal) {
            return $.ajax({
                url   : 'klloom_productcomments/ajax/read',
                type  : 'GET',
                data  : commentData,
                global: false
            }).done(function (response) {
                if (response) {
                    commentTotal([]);
                    $.each(response, function (i, value) {
                        commentTotal.push(value);
                    });
                }
            });
        };
    }
);