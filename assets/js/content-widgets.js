(function () {
    'use strict';

    /* ---------- Portfolio filter ---------- */
    function initPortfolioFilter(widget) {
        if (!widget || widget.dataset.elementskeyPortfolioInit === 'yes') {
            return;
        }

        var grid = widget.querySelector('.elementskey-portfolio-grid');
        if (!grid) {
            return;
        }

        var cards = grid.querySelectorAll('.elementskey-portfolio-card');
        var buttons = widget.querySelectorAll('.elementskey-portfolio-filter-btn');

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

        widget.dataset.elementskeyPortfolioInit = 'yes';
    }

    /* ---------- Off-Canvas ---------- */
    function initOffCanvas(widget) {
        if (!widget || widget.dataset.elementskeyOffCanvasInit === 'yes') {
            return;
        }

        var trigger = widget.querySelector('.elementskey-off-canvas-trigger');
        var closeBtn = widget.querySelector('.elementskey-off-canvas-close');
        var overlay = widget.querySelector('.elementskey-off-canvas-overlay');
        var panel = widget.querySelector('.elementskey-off-canvas-panel');

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

        widget.dataset.elementskeyOffCanvasInit = 'yes';
    }

    /* ---------- Counter ---------- */
    function initCounter(widget) {
        if (!widget || widget.dataset.elementskeyCounterInit === 'yes') {
            return;
        }

        var valueEl = widget.querySelector('.elementskey-counter-value');
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

        var prefixEl = widget.querySelector('.elementskey-counter-prefix');
        var suffixEl = widget.querySelector('.elementskey-counter-suffix');
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

        widget.dataset.elementskeyCounterInit = 'yes';
    }

    /* ---------- Tabs ---------- */
    function initTabs(widget) {
        if (!widget || widget.dataset.elementskeyTabsInit === 'yes') {
            return;
        }

        var tabs = widget.querySelectorAll('.elementskey-tab-item');
        var panes = widget.querySelectorAll('.elementskey-tab-pane');
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

        widget.dataset.elementskeyTabsInit = 'yes';
    }

    /* ---------- Accordion ---------- */
    function initAccordion(widget) {
        if (!widget || widget.dataset.elementskeyAccordionInit === 'yes') {
            return;
        }

        var items = widget.querySelectorAll('.elementskey-accordion-item');
        if (!items.length) {
            return;
        }

        items.forEach(function (item) {
            var header = item.querySelector('.elementskey-accordion-header');
            var body = item.querySelector('.elementskey-accordion-body');
            if (!header || !body) {
                return;
            }

            var content = item.querySelector('.elementskey-accordion-content');
            if (item.classList.contains('is-active')) {
                body.style.maxHeight = (content ? content.scrollHeight : 0) + 'px';
            }

            function toggle() {
                var isOpen = item.classList.contains('is-active');

                items.forEach(function (other) {
                    var otherBody = other.querySelector('.elementskey-accordion-body');
                    other.classList.remove('is-active');
                    if (otherBody) {
                        otherBody.style.maxHeight = '';
                    }
                    var otherHeader = other.querySelector('.elementskey-accordion-header');
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

        widget.dataset.elementskeyAccordionInit = 'yes';
    }

    function initSwiperWidgets(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        var selectors = ['.elementskey-slides', '.elementskey-media-carousel', '.elementskey-testimonial-carousel'];

        selectors.forEach(function (selector) {
            root.querySelectorAll(selector).forEach(function (el) {
                if (typeof Swiper === 'undefined' || el.dataset.elementskeySwiperInit === 'yes') {
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
                el.dataset.elementskeySwiperInit = 'yes';
            });
        });
    }

    function initAnimatedHeadline(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.elementskey-headline-words').forEach(function (el) {
            if (el.dataset.elementskeyHeadlineInit === 'yes') {
                return;
            }

            var words = el.querySelectorAll('.elementskey-headline-word');
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

            el.dataset.elementskeyHeadlineInit = 'yes';
        });
    }

    function initCountdown(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.elementskey-countdown').forEach(function (el) {
            if (el.dataset.elementskeyCountdownInit === 'yes') {
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
            el.dataset.elementskeyCountdownInit = 'yes';
        });
    }

    function initTableOfContent(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.elementskey-toc').forEach(function (toc) {
            if (toc.dataset.elementskeyTocInit === 'yes') {
                return;
            }

            var list = toc.querySelector('.elementskey-toc-list');
            if (!list) {
                return;
            }

            var tagsAttr = toc.getAttribute('data-headings') || 'h2';
            var tags = tagsAttr.split(',').map(function (t) {
                return t.trim().toLowerCase();
            }).filter(Boolean);

            var headings = [];
            document.querySelectorAll('h1, h2, h3, h4, h5, h6').forEach(function (h) {
                if (tags.indexOf(h.tagName.toLowerCase()) !== -1 && !h.closest('.elementskey-toc')) {
                    headings.push(h);
                }
            });

            if (!headings.length) {
                return;
            }

            headings.forEach(function (h, i) {
                if (!h.id) {
                    h.id = 'elementskey-toc-heading-' + i;
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

            toc.dataset.elementskeyTocInit = 'yes';
        });
    }

    function initProgressTracker(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.elementskey-progress-tracker-fill').forEach(function (el) {
            var track = el.closest('.elementskey-progress-tracker');
            var width = track ? track.getAttribute('data-width') : null;
            if (width === null) {
                return;
            }

            function fillTracker() {
                if (el.dataset.elementskeyTrackerInit === 'yes') {
                    return;
                }
                el.dataset.elementskeyTrackerInit = 'yes';
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

        root.querySelectorAll('.elementskey-video-playlist-widget').forEach(function (widget) {
            if (widget.dataset.elementskeyPlaylistInit === 'yes') {
                return;
            }

            var player = widget.querySelector('.elementskey-video-player iframe');
            var items = widget.querySelectorAll('.elementskey-playlist-item');

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

            widget.dataset.elementskeyPlaylistInit = 'yes';
        });
    }

    function initLottieWidgets(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.elementskey-lottie').forEach(function (el) {
            if (el.dataset.elementskeyLottieInit === 'yes') {
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
                el.dataset.elementskeyLottieInit = 'yes';
            }).catch(function () {
                el.innerHTML = 'Lottie animation could not be loaded.';
            });
        });
    }

    function initForms(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.elementskey-form').forEach(function (form) {
            if (form.dataset.elementskeyFormInit === 'yes') {
                return;
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var message = form.querySelector('.elementskey-form-message');
                if (!message) {
                    return;
                }

                var submitBtn = form.querySelector('.elementskey-form-submit');
                var originalLabel = submitBtn ? submitBtn.textContent : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Sending...';
                }

                message.className = 'elementskey-form-message';
                message.textContent = '';

                var data = new FormData(form);
                data.append('elementskey_email_to', form.getAttribute('data-email-to') || '');
                data.append('elementskey_email_subject', form.getAttribute('data-email-subject') || '');

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
                        message.className = 'elementskey-form-message is-success';
                        message.textContent = form.getAttribute('data-success') || 'Thank you! Your message has been sent.';
                        form.reset();
                    } else {
                        message.className = 'elementskey-form-message is-error';
                        message.textContent = (json && json.data && json.data.message) || form.getAttribute('data-error') || 'Sorry, your message could not be sent. Please try again.';
                    }
                }).catch(function () {
                    message.className = 'elementskey-form-message is-error';
                    message.textContent = form.getAttribute('data-error') || 'Sorry, your message could not be sent. Please try again.';
                }).then(function () {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalLabel;
                    }
                });
            });

            form.dataset.elementskeyFormInit = 'yes';
        });
    }

    /* ---------- Loop Grid pagination (load more / infinite scroll) ---------- */
    function initLoopPagination(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.elementskey-loop-pagination[data-elementskey-ajax]').forEach(function (nav) {
            if (nav.dataset.elementskeyLoopPaginationInit === 'yes') {
                return;
            }

            var widgetEl = nav.closest('.elementor-widget-elementskey_loop_grid');
            var grid = widgetEl ? widgetEl.querySelector('.elementskey-loop-grid') : nav.parentElement.querySelector('.elementskey-loop-grid');
            if (!grid) {
                return;
            }

            var type = nav.getAttribute('data-elementskey-ajax');
            var button = nav.querySelector('.elementskey-loop-load-more');
            var loading = nav.querySelector('.elementskey-loop-loading');
            var page = parseInt(nav.getAttribute('data-elementskey-page'), 10) || 1;
            var max = parseInt(nav.getAttribute('data-elementskey-max'), 10) || 1;
            var settings = nav.getAttribute('data-elementskey-settings') || '{}';
            var nonce = nav.getAttribute('data-elementskey-nonce') || '';
            var ajaxUrl = nav.getAttribute('data-elementskey-ajaxurl') || window.ajaxurl || '';
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
                body.append('action', 'elementskey_loop_load');
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
                    nav.setAttribute('data-elementskey-page', page);
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

            nav.dataset.elementskeyLoopPaginationInit = 'yes';
        });
    }

    /* ---------- Loop Grid: create template from editor ---------- */
    function initCreateLoopTemplate(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.elementskey-create-loop-template').forEach(function (btn) {
            if (btn.dataset.elementskeyCreateInit === 'yes') {
                return;
            }

            var wrap = btn.closest('.elementskey-loop-template-prompt');
            var originalLabel = btn.textContent.trim();

            btn.addEventListener('click', function () {
                if (!wrap) {
                    return;
                }

                btn.disabled = true;
                btn.textContent = 'Creating...';

                var body = new FormData();
                body.append('action', 'elementskey_create_loop_template');
                body.append('nonce', wrap.getAttribute('data-elementskey-nonce'));

                fetch(wrap.getAttribute('data-elementskey-ajaxurl'), {
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

            btn.dataset.elementskeyCreateInit = 'yes';
        });
    }

    function initCodeCopy(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;

        root.querySelectorAll('.elementskey-code-copy').forEach(function (btn) {
            if (btn.dataset.elementskeyCopyInit === 'yes') {
                return;
            }

            btn.addEventListener('click', function () {
                var code = btn.closest('.elementskey-code-highlight').querySelector('code');
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

            btn.dataset.elementskeyCopyInit = 'yes';
        });
    }

    function initAll(scope) {
        var root = scope && scope.querySelectorAll ? scope : document;
        root.querySelectorAll('.elementskey-portfolio-widget').forEach(initPortfolioFilter);
        root.querySelectorAll('.elementskey-off-canvas-widget').forEach(initOffCanvas);
        root.querySelectorAll('.elementskey-counter-widget').forEach(initCounter);
        root.querySelectorAll('.elementskey-tabs-widget').forEach(initTabs);
        root.querySelectorAll('.elementskey-accordion-widget').forEach(initAccordion);
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
                    'elementskey_portfolio',
                    'elementskey_off_canvas',
                    'elementskey_counter',
                    'elementskey_tabs',
                    'elementskey_accordion',
                    'elementskey_slides',
                    'elementskey_media_carousel',
                    'elementskey_testimonial_carousel',
                    'elementskey_animated_headline',
                    'elementskey_countdown',
                    'elementskey_table_of_content',
                    'elementskey_progress_tracker',
                    'elementskey_video_playlist',
                    'elementskey_lottie',
                    'elementskey_form',
                    'elementskey_code_highlight',
                    'elementskey_loop_grid'
                ].forEach(addReadyHook);
            }
        });
    }
}());
