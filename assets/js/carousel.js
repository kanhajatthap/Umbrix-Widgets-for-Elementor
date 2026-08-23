(function () {
    'use strict';

    function initCarousel(widget) {
        if (!widget || widget.dataset.elementskeyCarouselInit === 'yes') {
            return;
        }

        var swiperEl = widget.querySelector('.elementskey-swiper');
        if (!swiperEl || typeof Swiper === 'undefined') {
            return;
        }

        var data = {};
        try {
            data = JSON.parse(widget.getAttribute('data-settings') || '{}');
        } catch (err) {
            data = {};
        }

        var options = {
            speed: data.speed || 650,
            loop: !!data.loop,
            effect: data.effect || 'slide',
            centeredSlides: !!data.centerMode,
            allowTouchMove: !!data.allowTouchMove,
            grabCursor: !!data.allowTouchMove,
            slidesPerView: data.slidesDesktop || 4,
            spaceBetween: data.spaceDesktop || 24,
            breakpoints: {
                0: {
                    slidesPerView: data.slidesMobile || 1,
                    spaceBetween: data.spaceMobile || 14
                },
                768: {
                    slidesPerView: data.slidesTablet || 2,
                    spaceBetween: data.spaceTablet || 20
                },
                1024: {
                    slidesPerView: data.slidesDesktop || 4,
                    spaceBetween: data.spaceDesktop || 24
                }
            }
        };

        if (options.effect === 'fade') {
            options.slidesPerView = 1;
            options.spaceBetween = 0;
            options.fadeEffect = {
                crossFade: true
            };
        }

        if (data.showArrows) {
            options.navigation = {
                nextEl: widget.querySelector('.elementskey-swiper-button-next'),
                prevEl: widget.querySelector('.elementskey-swiper-button-prev')
            };
        }

        if (data.showDots) {
            options.pagination = {
                el: widget.querySelector('.elementskey-swiper-pagination'),
                clickable: true
            };
        }

        if (data.autoplay) {
            options.autoplay = {
                delay: data.autoplayDelay || 3500,
                disableOnInteraction: false,
                pauseOnMouseEnter: !!data.pauseOnHover
            };
        }

        var swiper = new Swiper(swiperEl, options);

        if (data.equalHeight) {
            var setEqualHeights = function () {
                var cards = widget.querySelectorAll('.elementskey-carousel-slide-inner, .elementskey-loop-carousel-card');
                var maxHeight = 0;

                cards.forEach(function (card) {
                    card.style.minHeight = '';
                    if (card.offsetHeight > maxHeight) {
                        maxHeight = card.offsetHeight;
                    }
                });

                cards.forEach(function (card) {
                    card.style.minHeight = maxHeight + 'px';
                });
            };

            swiper.on('resize', setEqualHeights);
            swiper.on('slideChangeTransitionEnd', setEqualHeights);
            setTimeout(setEqualHeights, 60);
        }

        widget.dataset.elementskeyCarouselInit = 'yes';
    }

    function initAllCarousels(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;
        var widgets = root.querySelectorAll('.elementskey-carousel-widget');
        widgets.forEach(initCarousel);
    }

    document.addEventListener('DOMContentLoaded', function () {
        initAllCarousels(document);
    });

    if (window.jQuery) {
        window.jQuery(window).on('elementor/frontend/init', function () {
            if (window.elementorFrontend && window.elementorFrontend.hooks) {
                window.elementorFrontend.hooks.addAction(
                    'frontend/element_ready/elementskey_swiper_carousel.default',
                    function (scope) {
                        var root = scope && scope[0] ? scope[0] : document;
                        initAllCarousels(root);
                    }
                );

                window.elementorFrontend.hooks.addAction(
                    'frontend/element_ready/elementskey_loop_carousel.default',
                    function (scope) {
                        var root = scope && scope[0] ? scope[0] : document;
                        initAllCarousels(root);
                    }
                );

                window.elementorFrontend.hooks.addAction(
                    'frontend/element_ready/elementskey_image_carousel.default',
                    function (scope) {
                        var root = scope && scope[0] ? scope[0] : document;
                        initAllCarousels(root);
                    }
                );
            }
        });
    }
}());
