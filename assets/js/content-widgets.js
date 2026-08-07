(function () {
    'use strict';

    /* ---------- Portfolio filter ---------- */
    function initPortfolioFilter(widget) {
        if (!widget || widget.dataset.bdeaPortfolioInit === 'yes') {
            return;
        }

        var grid = widget.querySelector('.bdea-portfolio-grid');
        if (!grid) {
            return;
        }

        var cards = grid.querySelectorAll('.bdea-portfolio-card');
        var buttons = widget.querySelectorAll('.bdea-portfolio-filter-btn');

        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var filter = this.getAttribute('data-filter');

                buttons.forEach(function (b) {
                    b.classList.remove('is-active');
                });
                this.classList.add('is-active');

                cards.forEach(function (card) {
                    var terms = (card.getAttribute('data-terms') || '').split(' ');
                    var show = (filter === '*') || terms.indexOf(filter) !== -1;
                    card.style.display = show ? '' : 'none';
                });
            });
        });

        widget.dataset.bdeaPortfolioInit = 'yes';
    }

    /* ---------- Off-Canvas ---------- */
    function initOffCanvas(widget) {
        if (!widget || widget.dataset.bdeaOffCanvasInit === 'yes') {
            return;
        }

        var trigger = widget.querySelector('.bdea-off-canvas-trigger');
        var closeBtn = widget.querySelector('.bdea-off-canvas-close');
        var overlay = widget.querySelector('.bdea-off-canvas-overlay');
        var panel = widget.querySelector('.bdea-off-canvas-panel');

        if (!trigger || !panel) {
            return;
        }

        var closeOverlay = widget.getAttribute('data-close-overlay') !== 'no';
        var closeEsc = widget.getAttribute('data-close-esc') !== 'no';

        function openPanel() {
            widget.classList.add('is-open');
            document.body.style.overflow = 'hidden';
            panel.setAttribute('aria-hidden', 'false');
        }

        function closePanel() {
            widget.classList.remove('is-open');
            document.body.style.overflow = '';
            panel.setAttribute('aria-hidden', 'true');
        }

        trigger.addEventListener('click', openPanel);

        if (closeBtn) {
            closeBtn.addEventListener('click', closePanel);
        }

        if (overlay && closeOverlay) {
            overlay.addEventListener('click', closePanel);
        }

        if (closeEsc) {
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && widget.classList.contains('is-open')) {
                    closePanel();
                }
            });
        }

        widget.dataset.bdeaOffCanvasInit = 'yes';
    }

    /* ---------- Counter ---------- */
    function initCounter(widget) {
        if (!widget || widget.dataset.bdeaCounterInit === 'yes') {
            return;
        }

        var valueEl = widget.querySelector('.bdea-counter-value');
        if (!valueEl) {
            return;
        }

        var target = parseFloat(valueEl.getAttribute('data-target')) || 0;
        var data = {};
        try {
            data = JSON.parse(widget.getAttribute('data-settings') || '{}');
        } catch (err) {
            data = {};
        }
        var duration = data.duration || 2000;

        var prefixEl = widget.querySelector('.bdea-counter-prefix');
        var suffixEl = widget.querySelector('.bdea-counter-suffix');
        var prefix = prefixEl ? prefixEl.textContent : '';
        var suffix = suffixEl ? suffixEl.textContent : '';

        function format(value) {
            var decimals = (String(target).split('.')[1] || '').length;
            return Number(value).toFixed(decimals);
        }

        function run() {
            var startTime = null;

            function step(ts) {
                if (!startTime) {
                    startTime = ts;
                }
                var progress = Math.min((ts - startTime) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                valueEl.textContent = format(target * eased);
                if (progress < 1) {
                    requestAnimationFrame(step);
                }
            }

            requestAnimationFrame(step);
        }

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        run();
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.3 });
            observer.observe(widget);
        } else {
            run();
        }

        widget.dataset.bdeaCounterInit = 'yes';
    }

    /* ---------- Tabs ---------- */
    function initTabs(widget) {
        if (!widget || widget.dataset.bdeaTabsInit === 'yes') {
            return;
        }

        var tabs = widget.querySelectorAll('.bdea-tab-item');
        var panes = widget.querySelectorAll('.bdea-tab-pane');
        if (!tabs.length || !panes.length) {
            return;
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var index = this.getAttribute('data-tab');

                tabs.forEach(function (t) {
                    t.classList.remove('is-active');
                    t.setAttribute('aria-selected', 'false');
                });
                panes.forEach(function (p) {
                    p.classList.remove('is-active');
                });

                this.classList.add('is-active');
                this.setAttribute('aria-selected', 'true');

                panes.forEach(function (p) {
                    if (p.getAttribute('data-pane') === index) {
                        p.classList.add('is-active');
                    }
                });
            });
        });

        widget.dataset.bdeaTabsInit = 'yes';
    }

    /* ---------- Accordion ---------- */
    function initAccordion(widget) {
        if (!widget || widget.dataset.bdeaAccordionInit === 'yes') {
            return;
        }

        var items = widget.querySelectorAll('.bdea-accordion-item');
        if (!items.length) {
            return;
        }

        items.forEach(function (item) {
            var header = item.querySelector('.bdea-accordion-header');
            var body = item.querySelector('.bdea-accordion-body');
            if (!header || !body) {
                return;
            }

            var content = item.querySelector('.bdea-accordion-content');
            if (item.classList.contains('is-active')) {
                body.style.maxHeight = (content ? content.scrollHeight : 0) + 'px';
            }

            function toggle() {
                var isOpen = item.classList.contains('is-active');

                items.forEach(function (other) {
                    var otherBody = other.querySelector('.bdea-accordion-body');
                    other.classList.remove('is-active');
                    if (otherBody) {
                        otherBody.style.maxHeight = '';
                    }
                    var otherHeader = other.querySelector('.bdea-accordion-header');
                    if (otherHeader) {
                        otherHeader.setAttribute('aria-expanded', 'false');
                    }
                });

                if (!isOpen) {
                    item.classList.add('is-active');
                    header.setAttribute('aria-expanded', 'true');
                    var height = content ? content.scrollHeight : 0;
                    body.style.maxHeight = height + 'px';
                } else {
                    header.setAttribute('aria-expanded', 'false');
                }
            }

            header.addEventListener('click', toggle);
            header.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggle();
                }
            });
        });

        widget.dataset.bdeaAccordionInit = 'yes';
    }

    function initSwiperWidgets(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        var selectors = ['.bdea-slides', '.bdea-media-carousel', '.bdea-testimonial-carousel'];

        selectors.forEach(function (selector) {
            root.querySelectorAll(selector).forEach(function (el) {
                if (typeof Swiper === 'undefined' || el.dataset.bdeaSwiperInit === 'yes') {
                    return;
                }

                var data = {};
                try {
                    data = JSON.parse(el.getAttribute('data-settings') || '{}');
                } catch (err) {
                    data = {};
                }

                var options = {
                    speed: 600,
                    loop: true,
                    slidesPerView: data.slides || 1,
                    spaceBetween: 20,
                    breakpoints: {
                        0: {
                            slidesPerView: data.slidesMobile || 1,
                            spaceBetween: 14
                        },
                        768: {
                            slidesPerView: data.slidesTablet || (data.slides > 1 ? 2 : 1),
                            spaceBetween: 20
                        },
                        1024: {
                            slidesPerView: data.slides || 1,
                            spaceBetween: 20
                        }
                    }
                };

                if (data.arrows) {
                    options.navigation = {
                        nextEl: el.querySelector('.swiper-button-next'),
                        prevEl: el.querySelector('.swiper-button-prev')
                    };
                }

                if (data.dots) {
                    options.pagination = {
                        el: el.querySelector('.swiper-pagination'),
                        clickable: true
                    };
                }

                if (data.autoplay) {
                    options.autoplay = {
                        delay: 4000,
                        disableOnInteraction: false
                    };
                }

                new Swiper(el, options);
                el.dataset.bdeaSwiperInit = 'yes';
            });
        });
    }

    function initAnimatedHeadline(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.bdea-headline-words').forEach(function (el) {
            if (el.dataset.bdeaHeadlineInit === 'yes') {
                return;
            }

            var words = el.querySelectorAll('.bdea-headline-word');
            if (words.length < 2) {
                return;
            }

            var speed = parseInt(el.getAttribute('data-speed'), 10) || 2500;
            var index = 0;

            setInterval(function () {
                words[index].classList.remove('is-active');
                index = (index + 1) % words.length;
                words[index].classList.add('is-active');
            }, speed);

            el.dataset.bdeaHeadlineInit = 'yes';
        });
    }

    function initCountdown(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.bdea-countdown').forEach(function (el) {
            if (el.dataset.bdeaCountdownInit === 'yes') {
                return;
            }

            var dateAttr = el.getAttribute('data-date');
            if (!dateAttr) {
                return;
            }

            var target = new Date(dateAttr.replace(' ', 'T'));
            if (isNaN(target.getTime())) {
                return;
            }

            var dayEl = el.querySelector('[data-unit="days"]');
            var hourEl = el.querySelector('[data-unit="hours"]');
            var minEl = el.querySelector('[data-unit="minutes"]');
            var secEl = el.querySelector('[data-unit="seconds"]');

            function pad(n) {
                return n < 10 ? '0' + n : String(n);
            }

            function tick() {
                var diff = target.getTime() - Date.now();
                if (diff <= 0) {
                    if (dayEl) dayEl.textContent = '00';
                    if (hourEl) hourEl.textContent = '00';
                    if (minEl) minEl.textContent = '00';
                    if (secEl) secEl.textContent = '00';
                    clearInterval(timer);
                    return;
                }

                var seconds = Math.floor(diff / 1000);
                var days = Math.floor(seconds / 86400);
                seconds -= days * 86400;
                var hours = Math.floor(seconds / 3600);
                seconds -= hours * 3600;
                var minutes = Math.floor(seconds / 60);
                seconds -= minutes * 60;

                if (dayEl) dayEl.textContent = pad(days);
                if (hourEl) hourEl.textContent = pad(hours);
                if (minEl) minEl.textContent = pad(minutes);
                if (secEl) secEl.textContent = pad(seconds);
            }

            tick();
            var timer = setInterval(tick, 1000);
            el.dataset.bdeaCountdownInit = 'yes';
        });
    }

    function initTableOfContent(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.bdea-toc').forEach(function (toc) {
            if (toc.dataset.bdeaTocInit === 'yes') {
                return;
            }

            var list = toc.querySelector('.bdea-toc-list');
            if (!list) {
                return;
            }

            var tagsAttr = toc.getAttribute('data-headings') || 'h2';
            var tags = tagsAttr.split(',').map(function (t) {
                return t.trim().toLowerCase();
            }).filter(Boolean);

            var headings = [];
            document.querySelectorAll('h1, h2, h3, h4, h5, h6').forEach(function (h) {
                if (tags.indexOf(h.tagName.toLowerCase()) !== -1 && !h.closest('.bdea-toc')) {
                    headings.push(h);
                }
            });

            if (!headings.length) {
                return;
            }

            headings.forEach(function (h, i) {
                if (!h.id) {
                    h.id = 'bdea-toc-heading-' + i;
                }
                var li = document.createElement('li');
                var a = document.createElement('a');
                a.href = '#' + h.id;
                a.textContent = h.textContent;
                li.appendChild(a);
                list.appendChild(li);
            });

            list.querySelectorAll('a').forEach(function (a) {
                a.addEventListener('click', function (e) {
                    var target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });

            toc.dataset.bdeaTocInit = 'yes';
        });
    }

    function initProgressTracker(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.bdea-progress-tracker-fill').forEach(function (el) {
            var track = el.closest('.bdea-progress-tracker');
            var width = track ? track.getAttribute('data-width') : null;
            if (width === null) {
                return;
            }

            function fillTracker() {
                if (el.dataset.bdeaTrackerInit === 'yes') {
                    return;
                }
                el.dataset.bdeaTrackerInit = 'yes';
                el.style.width = width + '%';
            }

            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            fillTracker();
                            observer.disconnect();
                        }
                    });
                }, { threshold: 0.3 });
                observer.observe(el);
            } else {
                fillTracker();
            }
        });
    }

    function initVideoPlaylist(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.bdea-video-playlist-widget').forEach(function (widget) {
            if (widget.dataset.bdeaPlaylistInit === 'yes') {
                return;
            }

            var player = widget.querySelector('.bdea-video-player iframe');
            var items = widget.querySelectorAll('.bdea-playlist-item');

            if (!player || !items.length) {
                return;
            }

            items.forEach(function (item) {
                item.addEventListener('click', function () {
                    var src = item.getAttribute('data-src');
                    if (!src) {
                        return;
                    }
                    player.setAttribute('src', src);
                    items.forEach(function (i) {
                        i.classList.remove('is-active');
                    });
                    item.classList.add('is-active');
                });
                item.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        item.click();
                    }
                });
            });

            widget.dataset.bdeaPlaylistInit = 'yes';
        });
    }

    function initLottieWidgets(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.bdea-lottie').forEach(function (el) {
            if (el.dataset.bdeaLottieInit === 'yes') {
                return;
            }

            var data = {};
            try {
                data = JSON.parse(el.getAttribute('data-settings') || '{}');
            } catch (err) {
                data = {};
            }

            if (!data.src) {
                return;
            }

            function loadLottieLib() {
                return new Promise(function (resolve, reject) {
                    if (window.lottie) {
                        resolve(window.lottie);
                        return;
                    }
                    var script = document.createElement('script');
                    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js';
                    script.onload = function () {
                        resolve(window.lottie);
                    };
                    script.onerror = reject;
                    document.head.appendChild(script);
                });
            }

            loadLottieLib().then(function (lottie) {
                lottie.loadAnimation({
                    container: el,
                    renderer: 'svg',
                    loop: !!data.loop,
                    autoplay: !!data.autoplay,
                    path: data.src,
                    speed: data.speed || 1
                });
                el.dataset.bdeaLottieInit = 'yes';
            }).catch(function () {
                el.innerHTML = 'Lottie animation could not be loaded.';
            });
        });
    }

    function initForms(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.bdea-form').forEach(function (form) {
            if (form.dataset.bdeaFormInit === 'yes') {
                return;
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var message = form.querySelector('.bdea-form-message');
                if (!message) {
                    return;
                }

                var submitBtn = form.querySelector('.bdea-form-submit');
                var originalLabel = submitBtn ? submitBtn.textContent : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Sending...';
                }

                message.className = 'bdea-form-message';
                message.textContent = '';

                var data = new FormData(form);
                data.append('bdea_email_to', form.getAttribute('data-email-to') || '');
                data.append('bdea_email_subject', form.getAttribute('data-email-subject') || '');

                fetch(form.getAttribute('action'), {
                    method: 'POST',
                    body: data,
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(function (response) {
                    return response.json().catch(function () {
                        throw new Error('bad-response');
                    });
                }).then(function (json) {
                    if (json && json.success) {
                        message.className = 'bdea-form-message is-success';
                        message.textContent = form.getAttribute('data-success') || 'Thank you! Your message has been sent.';
                        form.reset();
                    } else {
                        message.className = 'bdea-form-message is-error';
                        message.textContent = (json && json.data && json.data.message) || form.getAttribute('data-error') || 'Sorry, your message could not be sent. Please try again.';
                    }
                }).catch(function () {
                    message.className = 'bdea-form-message is-error';
                    message.textContent = form.getAttribute('data-error') || 'Sorry, your message could not be sent. Please try again.';
                }).then(function () {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalLabel;
                    }
                });
            });

            form.dataset.bdeaFormInit = 'yes';
        });
    }

    /* ---------- Loop Grid pagination (load more / infinite scroll) ---------- */
    function initLoopPagination(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.bdea-loop-pagination[data-bdea-ajax]').forEach(function (nav) {
            if (nav.dataset.bdeaLoopPaginationInit === 'yes') {
                return;
            }

            var widgetEl = nav.closest('.elementor-widget-bdea_loop_grid');
            var grid = widgetEl ? widgetEl.querySelector('.bdea-loop-grid') : nav.parentElement.querySelector('.bdea-loop-grid');
            if (!grid) {
                return;
            }

            var type = nav.getAttribute('data-bdea-ajax');
            var button = nav.querySelector('.bdea-loop-load-more');
            var loading = nav.querySelector('.bdea-loop-loading');
            var page = parseInt(nav.getAttribute('data-bdea-page'), 10) || 1;
            var max = parseInt(nav.getAttribute('data-bdea-max'), 10) || 1;
            var settings = nav.getAttribute('data-bdea-settings') || '{}';
            var nonce = nav.getAttribute('data-bdea-nonce') || '';
            var ajaxUrl = nav.getAttribute('data-bdea-ajaxurl') || window.ajaxurl || '';
            var loadingFlag = false;
            var originalLabel = button ? button.textContent.trim() : '';

            function setLoading(on) {
                if (button) {
                    button.disabled = on;
                }
                if (loading) {
                    loading.hidden = !on;
                }
            }

            function loadPage() {
                if (loadingFlag || page >= max) {
                    return;
                }
                loadingFlag = true;
                setLoading(true);

                var body = new FormData();
                body.append('action', 'bdea_loop_load');
                body.append('nonce', nonce);
                body.append('page', page + 1);
                body.append('settings', settings);

                fetch(ajaxUrl, {
                    method: 'POST',
                    body: body,
                    credentials: 'same-origin'
                }).then(function (response) {
                    return response.json().catch(function () {
                        throw new Error('bad-response');
                    });
                }).then(function (json) {
                    if (!json || !json.success) {
                        return;
                    }
                    page += 1;
                    nav.setAttribute('data-bdea-page', page);
                    grid.insertAdjacentHTML('beforeend', json.data.html || '');
                    initAll(grid);

                    if (!json.data.has_more) {
                        nav.classList.add('is-finished');
                        if (button) {
                            button.hidden = true;
                        }
                        if (type === 'infinite_scroll') {
                            observer.disconnect();
                        }
                    }
                }).catch(function () {
                    if (button) {
                        button.textContent = 'Try Again';
                        button.disabled = false;
                    }
                }).then(function () {
                    loadingFlag = false;
                    if (button) {
                        button.disabled = false;
                        button.textContent = originalLabel;
                    }
                    if (loading) {
                        loading.hidden = true;
                    }
                });
            }

            if (type === 'load_more' && button) {
                button.addEventListener('click', loadPage);
            }

            if (type === 'infinite_scroll') {
                if (button) {
                    button.hidden = true;
                }
                var observer = null;
                if ('IntersectionObserver' in window) {
                    observer = new IntersectionObserver(function (entries) {
                        entries.forEach(function (entry) {
                            if (entry.isIntersecting) {
                                loadPage();
                            }
                        });
                    }, { rootMargin: '200px' });
                    observer.observe(nav);
                } else {
                    window.addEventListener('scroll', function () {
                        var rect = nav.getBoundingClientRect();
                        if (rect.top < window.innerHeight + 200) {
                            loadPage();
                        }
                    });
                }
            }

            nav.dataset.bdeaLoopPaginationInit = 'yes';
        });
    }

    /* ---------- Loop Grid: create template from editor ---------- */
    function initCreateLoopTemplate(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.bdea-create-loop-template').forEach(function (btn) {
            if (btn.dataset.bdeaCreateInit === 'yes') {
                return;
            }

            var wrap = btn.closest('.bdea-loop-template-prompt');
            var originalLabel = btn.textContent.trim();

            btn.addEventListener('click', function () {
                if (!wrap) {
                    return;
                }

                btn.disabled = true;
                btn.textContent = 'Creating...';

                var body = new FormData();
                body.append('action', 'bdea_create_loop_template');
                body.append('nonce', wrap.getAttribute('data-bdea-nonce'));

                fetch(wrap.getAttribute('data-bdea-ajaxurl'), {
                    method: 'POST',
                    body: body,
                    credentials: 'same-origin'
                }).then(function (response) {
                    return response.json().catch(function () {
                        throw new Error('bad-response');
                    });
                }).then(function (json) {
                    if (json && json.success && json.data && json.data.edit_url) {
                        try {
                            if (window.top && window.top !== window) {
                                window.top.location.href = json.data.edit_url;
                                return;
                            }
                        } catch (err) {
                            /* cross-origin top window - fall through */
                        }
                        window.location.href = json.data.edit_url;
                    } else {
                        btn.disabled = false;
                        btn.textContent = originalLabel;
                    }
                }).catch(function () {
                    btn.disabled = false;
                    btn.textContent = 'Try Again';
                });
            });

            btn.dataset.bdeaCreateInit = 'yes';
        });
    }

    function initCodeCopy(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.bdea-code-copy').forEach(function (btn) {
            if (btn.dataset.bdeaCopyInit === 'yes') {
                return;
            }

            btn.addEventListener('click', function () {
                var code = btn.closest('.bdea-code-highlight').querySelector('code');
                if (!code) {
                    return;
                }
                var text = code.textContent;
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(function () {
                        btn.textContent = 'Copied!';
                        setTimeout(function () {
                            btn.textContent = 'Copy';
                        }, 1800);
                    });
                } else {
                    var ta = document.createElement('textarea');
                    ta.value = text;
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand('copy');
                    document.body.removeChild(ta);
                    btn.textContent = 'Copied!';
                    setTimeout(function () {
                        btn.textContent = 'Copy';
                    }, 1800);
                }
            });

            btn.dataset.bdeaCopyInit = 'yes';
        });
    }

    function initAll(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;
        root.querySelectorAll('.bdea-portfolio-widget').forEach(initPortfolioFilter);
        root.querySelectorAll('.bdea-off-canvas-widget').forEach(initOffCanvas);
        root.querySelectorAll('.bdea-counter-widget').forEach(initCounter);
        root.querySelectorAll('.bdea-tabs-widget').forEach(initTabs);
        root.querySelectorAll('.bdea-accordion-widget').forEach(initAccordion);
        initSwiperWidgets(root);
        initAnimatedHeadline(root);
        initCountdown(root);
        initTableOfContent(root);
        initProgressTracker(root);
        initVideoPlaylist(root);
        initLottieWidgets(root);
        initForms(root);
        initLoopPagination(root);
        initCreateLoopTemplate(root);
        initCodeCopy(root);
    }

    function boot() {
        initAll(document);

        if (window.jQuery) {
            window.jQuery(window).on('elementor/frontend/init', function () {
                if (window.elementorFrontend && window.elementorFrontend.hooks) {
                    initAll(document);
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }

    if (window.jQuery) {
        window.jQuery(window).on('elementor/frontend/init', function () {
            if (window.elementorFrontend && window.elementorFrontend.hooks) {
                var addReadyHook = function (widgetName) {
                    window.elementorFrontend.hooks.addAction(
                        'frontend/element_ready/' + widgetName + '.default',
                        function (scope) {
                            var root = scope && scope[0] ? scope[0] : document;
                            initAll(root);
                        }
                    );
                };

                [
                    'bdea_portfolio',
                    'bdea_off_canvas',
                    'bdea_counter',
                    'bdea_tabs',
                    'bdea_accordion',
                    'bdea_slides',
                    'bdea_media_carousel',
                    'bdea_testimonial_carousel',
                    'bdea_animated_headline',
                    'bdea_countdown',
                    'bdea_table_of_content',
                    'bdea_progress_tracker',
                    'bdea_video_playlist',
                    'bdea_lottie',
                    'bdea_form',
                    'bdea_code_highlight',
                    'bdea_loop_grid'
                ].forEach(addReadyHook);
            }
        });
    }
}());
