define(['ko',
        'jquery',
        'mage/storage',
        'mage/translate'
    ], function (ko, $, storage, $t) {
        'use strict';
        return function (commentData, commentTotal) {
            return storage.post(
                'klloom_productcomments/ajax/save',
                JSON.stringify(commentData),
                false
            ).done(
                function (response) {
                    if (response) {
                        commentTotal([]);
                        $.each(response, function (i, value) {
                            commentTotal.push(value);
                        });
                    }
                }
            ).fail(
                function (response) {
                }
            );
        };
    }
);