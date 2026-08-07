(function () {
    'use strict';

    function markCopied(link) {
        link.classList.add('is-copied');
        setTimeout(function () {
            link.classList.remove('is-copied');
        }, 1200);
    }

    function copyTextToClipboard(text, onSuccess) {
        function fallbackCopy() {
            var temp = document.createElement('textarea');
            temp.value = text;
            temp.setAttribute('readonly', 'readonly');
            temp.style.position = 'absolute';
            temp.style.left = '-9999px';
            document.body.appendChild(temp);
            temp.select();

            try {
                document.execCommand('copy');
                onSuccess();
            } catch (err) {
                /* Do nothing when copy fails in unsupported browsers. */
            }

            document.body.removeChild(temp);
        }

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(onSuccess).catch(fallbackCopy);
            return;
        }

        fallbackCopy();
    }

    function initCopyLinks(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;
        var links = root.querySelectorAll('.bdea-share-it-copy');

        links.forEach(function (link) {
            if (link.dataset.bdeaShareItInit === 'yes') {
                return;
            }

            link.addEventListener('click', function (event) {
                event.preventDefault();
                var copyUrl = link.getAttribute('data-copy-url') || '';
                if (!copyUrl) {
                    return;
                }

                copyTextToClipboard(copyUrl, function () {
                    markCopied(link);
                });
            });

            link.dataset.bdeaShareItInit = 'yes';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initCopyLinks(document);
    });

    if (window.jQuery) {
        window.jQuery(window).on('elementor/frontend/init', function () {
            if (window.elementorFrontend && window.elementorFrontend.hooks) {
                window.elementorFrontend.hooks.addAction(
                    'frontend/element_ready/bdea_share_it.default',
                    function (scope) {
                        var root = scope && scope[0] ? scope[0] : document;
                        initCopyLinks(root);
                    }
                );
            }
        });
    }
}());
