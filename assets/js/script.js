(function ($) {
    'use strict';

    var initProgressBars = function (scope) {
        var root = scope && scope.length ? scope[0] : document;
        var wrappers = root.querySelectorAll('.bdea-progress-wrapper');

        wrappers.forEach(function (wrapper) {
            if (wrapper.dataset.bdeaInitDone === 'yes') {
                return;
            }

            var speed = wrapper.getAttribute('data-speed') || 1200;
            var bars = wrapper.querySelectorAll('.bdea-progress-fill');

            var setWidths = function () {
                bars.forEach(function (bar) {
                    var width = bar.getAttribute('data-width') || 0;
                    bar.style.transition = 'width ' + speed + 'ms ease';
                    bar.style.width = width + '%';
                });
            };

            wrapper.dataset.bdeaInitDone = 'yes';

            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            setWidths();
                            observer.unobserve(entry.target);
                        }
                    });
                });
                observer.observe(wrapper);
            }

            setTimeout(setWidths, 60);
        });
    };

    $(window).on('elementor/frontend/init', function () {
        if (window.elementorFrontend && window.elementorFrontend.hooks) {
            window.elementorFrontend.hooks.addAction(
                'frontend/element_ready/bdea_progress_bar.default',
                initProgressBars
            );
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        initProgressBars();
    });
}(jQuery));