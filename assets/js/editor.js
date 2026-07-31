jQuery(function ($) {

    var targets = {
        'Progress Bar': '.eicon-skill-bar',
        'Data Table': '.eicon-table',
        'Carousel': '.eicon-slider-push',
        'Feature Comparison Table': '.eicon-table-of-contents'
    };

    var appendBadge = function () {
        $('.elementor-panel-elements .elementor-widget, .elementor-panel-elements .elementor-element').each(function () {
            var $widget = $(this);
            var $title = $widget.find('.elementor-widget-title, .elementor-panel-element-title, .elementor-element-title, .title').first();
            if (!$title.length) {
                return;
            }

            var titleText = $title.clone().children().remove().end().text().trim();
            Object.keys(targets).forEach(function (widgetTitle) {
                if (titleText.indexOf(widgetTitle) !== -1) {
                    var $icon = $widget.find('.icon ' + targets[widgetTitle]).first();
                    if ($icon.length) {
                        var $wrapper = $icon.parent();
                        if (!$wrapper.hasClass('bdea-icon-wrapper')) {
                            $wrapper.addClass('bdea-icon-wrapper');
                        }
                    } else {
                        if (!$title.find('.es-badge').length) {
                            $title.append('<span class="es-badge">ES</span>');
                        }
                    }
                }
            });
        });
    };

    var initObserver = function () {
        var panel = document.querySelector('.elementor-panel-elements');
        if (!panel) {
            return;
        }

        var observer = new MutationObserver(function () {
            appendBadge();
        });

        observer.observe(panel, { childList: true, subtree: true });
    };

    var init = function () {
        appendBadge();
        setTimeout(initObserver, 500);
    };

    if (window.elementor && window.elementor.hooks) {
        init();
    } else {
        $(window).on('elementor:init', init);
    }

    if (window.elementor && window.elementor.hooks) {
        elementor.hooks.addFilter('panel/elements/regionViews', function (panel) {
            setTimeout(appendBadge, 300);
            return panel;
        });
    }
});