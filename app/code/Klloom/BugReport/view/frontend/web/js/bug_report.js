define([
    "autosize",
    "jquery",
    "jquery/ui"
], function (autosize, $) {
    "use strict";
    return function (config, element) {
        autosize($('textarea'));
    };
});