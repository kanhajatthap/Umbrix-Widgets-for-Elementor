(function () {

    var appendBadge = function () {
        var $ = window.jQuery;
        if (!$) {
            return;
        }

        // Category view - badge only in "ElementsKey Elements" category
        $('.elementor-panel-category').each(function () {
            var $cat = $(this);
            var catTitle = $cat.find('.elementor-panel-heading-title').first().text().trim();

            if (catTitle !== 'ElementsKey Elements') {
                return;
            }

            $cat.find('.elementor-element').each(function () {
                var $title = $(this).find('.title-wrapper .title').first();
                if (!$title.length) {
                    return;
                }

                if (!$title.find('.es-badge').length) {
                    $title.append('<span class="es-badge">ES</span>');
                }
            });
        });

        // Search view - badge on widgets with elementskey_ prefix
        $('.elementor-element[data-library-element-type]').each(function () {
            var type = $(this).attr('data-library-element-type');
            if (type && type.indexOf('elementskey_') === 0) {
                var $title = $(this).find('.title-wrapper .title').first();
                if ($title.length && !$title.find('.es-badge').length) {
                    $title.append('<span class="es-badge">ES</span>');
                }
            }
        });
    };

    var startObserver = function () {
        var panel = document.querySelector('.elementor-panel');
        if (!panel) {
            setTimeout(startObserver, 1000);
            return;
        }

        var observer = new MutationObserver(function () {
            appendBadge();
        });

        observer.observe(panel, { childList: true, subtree: true });
        appendBadge();
    };

    setTimeout(startObserver, 1500);

})();
