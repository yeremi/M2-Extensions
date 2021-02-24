define([
    "jquery",
    "jquery/ui",
    "slick"
], function ($, slick) {
    "use strict";

    $(document).ready(function () {
        $('.join-klloom').slick({
            dots          : false,
            infinite      : false,
            speed         : 300,
            slidesToShow  : 4,
            slidesToScroll: 4,
            responsive    : [
                {
                    breakpoint: 1024,
                    settings  : {
                        slidesToShow  : 3,
                        slidesToScroll: 3,
                        arrows        : false
                    }
                },
                {
                    breakpoint: 600,
                    settings  : {
                        slidesToShow  : 2,
                        slidesToScroll: 2,
                        arrows        : false
                    }
                },
                {
                    breakpoint: 480,
                    settings  : {
                        slidesToShow  : 1,
                        slidesToScroll: 1,
                        arrows        : false
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
    });

});