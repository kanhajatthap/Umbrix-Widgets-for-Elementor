(function () {
    'use strict';

    var header      = document.querySelector('.bdea-hf-header');
    var announcement = document.querySelector('.bdea-hf-announcement');

    function getOffset(header) {
        return header && header.getAttribute('data-offset') ? parseInt(header.getAttribute('data-offset'), 10) : 0;
    }

    function onScroll() {
        var y = window.pageYOffset || document.documentElement.scrollTop || 0;

        if (header) {
            var offset = getOffset(header);

            if (y > offset) {
                header.classList.add('bdea-hf-scrolled');
            } else {
                header.classList.remove('bdea-hf-scrolled');
            }
        }
    }

    var lastY = 0;

    function onScrollDirection() {
        var y = window.pageYOffset || document.documentElement.scrollTop || 0;

        if (header && header.classList.contains('bdea-hf-hide-scroll')) {
            var offset = getOffset(header);

            if (y > offset && y > lastY) {
                header.classList.add('bdea-hf-scroll-down');
            } else {
                header.classList.remove('bdea-hf-scroll-down');
            }
        }

        lastY = y;
    }

    function onResize() {
        onScroll();
    }

    if (header) {
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onResize, { passive: true });
        window.addEventListener('scroll', onScrollDirection, { passive: true });
    }

    if (announcement) {
        var closeBtn = announcement.querySelector('.bdea-hf-announcement-close');

        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                var id   = announcement.getAttribute('data-id') || '';
                var days = parseInt(closeBtn.getAttribute('data-days'), 10) || 1;

                if (id) {
                    document.cookie = 'bdea_hf_dismiss_' + id + '=1; max-age=' + (days * 86400) + '; path=/; SameSite=Lax';
                }

                announcement.style.display = 'none';
            });
        }
    }
})();
