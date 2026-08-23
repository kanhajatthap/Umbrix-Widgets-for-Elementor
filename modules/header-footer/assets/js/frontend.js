(function () {
    'use strict';

    var header      = document.querySelector('.elementskey-hf-header');
    var announcement = document.querySelector('.elementskey-hf-announcement');

    function getOffset(header) {
        return header && header.getAttribute('data-offset') ? parseInt(header.getAttribute('data-offset'), 10) : 0;
    }

    function onScroll() {
        var y = window.pageYOffset || document.documentElement.scrollTop || 0;

        if (header) {
            var offset = getOffset(header);

            if (y > offset) {
                header.classList.add('elementskey-hf-scrolled');
            } else {
                header.classList.remove('elementskey-hf-scrolled');
            }
        }
    }

    var lastY = 0;

    function onScrollDirection() {
        var y = window.pageYOffset || document.documentElement.scrollTop || 0;

        if (header && header.classList.contains('elementskey-hf-hide-scroll')) {
            var offset = getOffset(header);

            if (y > offset && y > lastY) {
                header.classList.add('elementskey-hf-scroll-down');
            } else {
                header.classList.remove('elementskey-hf-scroll-down');
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
        var closeBtn = announcement.querySelector('.elementskey-hf-announcement-close');

        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                var id   = announcement.getAttribute('data-id') || '';
                var days = parseInt(closeBtn.getAttribute('data-days'), 10) || 1;

                if (id) {
                    document.cookie = 'elementskey_hf_dismiss_' + id + '=1; max-age=' + (days * 86400) + '; path=/; SameSite=Lax';
                }

                announcement.style.display = 'none';
            });
        }
    }
})();
