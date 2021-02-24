define([
    "lity",
    "jquery",
    "asAccordion",
    "slick"

], function (lity, $) {
    "use strict";

    function main(config, element) {

        $(document).on('click', '#watch-video', function () {
            lity('https://www.youtube.com/embed/164tesSTr7o?controls=0&rel=0');
        });

        $(window).on('resize orientationchange', function() {
            $('.responsive-scroll').slick('resize');
        });

        $('.responsive-scroll').on('init', function () {
            $(this).css({
                visibility: 'visible'
            });
        });

        $('.responsive-scroll').slick({
            dots: false,
            infinite: true,
            autoplay: true,
            speed: 300,
            slidesToShow: 2,
            slidesToScroll: 2,
            arrows: false,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 500,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });

        /*$(".owl-carousel").owlCarousel({
            loop: true,
            autoplay: true,
            responsiveClass: true,
            autoheight: true,
            responsive: {
                0: {
                    items: 1,
                    dots: false,
                    nav: false,
                },
                480: {
                    items: 2,
                    dots: false,
                    nav: false,
                },
                650: {
                    items: 3,
                    dots: false,
                    nav: false,
                },
                1000: {
                    items: 2,
                    dots: false,
                    nav: false,
                }
            }
        });*/

        $('.-accordion').asAccordion({
            //namespace: '-accordion',
            // accordion theme. WIP
            //skin: null,
            // breakpoint for mobile devices. WIP
            //mobileBreakpoint: 768,
            // initial index panel
            //initialIndex: false,
            // CSS3 easing effects.
            //easing: 'ease-in-out',
            // animation speed.
            //speed: 500,
            // vertical or horizontal
            //direction: 'vertical',
            // jQuery mouse events. click, mousehover, etc.
            event: 'click',
            // multiple instance
            multiple: false
        });

    }

    return main;

});