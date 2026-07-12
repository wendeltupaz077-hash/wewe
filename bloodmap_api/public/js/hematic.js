(function () {
    'use strict';

    // Mobile nav toggle
    const navToggle = document.getElementById('hmNavToggle');
    const navMobile = document.getElementById('hmNavMobile');
    if (navToggle && navMobile) {
        navToggle.addEventListener('click', function () {
            navMobile.classList.toggle('open');
            const isOpen = navMobile.classList.contains('open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    // Blood bag toggle
    const bloodBag = document.getElementById('hmBloodBag');
    if (bloodBag) {
        const emptyImg = bloodBag.querySelector('.hm-blood-bag-img.empty');
        const filledImg = bloodBag.querySelector('.hm-blood-bag-img.filled');
        const hint = bloodBag.querySelector('.hm-blood-bag-hint');
        let filled = false;

        const toggle = function () {
            filled = !filled;
            bloodBag.setAttribute('aria-pressed', filled ? 'true' : 'false');
            if (emptyImg) emptyImg.style.opacity = filled ? '0' : '1';
            if (filledImg) filledImg.style.opacity = filled ? '1' : '0';
            if (hint) {
                hint.textContent = filled ? 'Status: Ready to Save a Life' : 'Tap to Activate';
            }
        };

        bloodBag.addEventListener('click', toggle);
        bloodBag.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggle();
            }
        });
    }

    // Scroll-triggered reveals
    const revealEls = document.querySelectorAll('.hm-reveal');
    if (revealEls.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

        revealEls.forEach(function (el) { observer.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add('is-visible'); });
    }

    // FAQ accordion
    document.querySelectorAll('.hm-accordion-trigger').forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            const item = trigger.closest('.hm-accordion-item');
            if (!item) return;
            const isOpen = item.classList.contains('is-open');
            const panel = item.closest('.hm-accordion');
            if (panel) {
                panel.querySelectorAll('.hm-accordion-item.is-open').forEach(function (open) {
                    if (open !== item) open.classList.remove('is-open');
                });
            }
            item.classList.toggle('is-open', !isOpen);
        });
    });

    // FAQ category tabs
    const faqTabs = document.querySelectorAll('[data-faq-tab]');
    const faqPanels = document.querySelectorAll('[data-faq-panel]');
    if (faqTabs.length) {
        faqTabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                const category = tab.getAttribute('data-faq-tab');
                faqTabs.forEach(function (t) { t.classList.remove('is-active'); });
                tab.classList.add('is-active');
                faqPanels.forEach(function (panel) {
                    panel.classList.toggle('hm-hidden', panel.getAttribute('data-faq-panel') !== category);
                });
            });
        });
    }

    // Donor directory filter + search
    const directorySearch = document.getElementById('hmDirectorySearch');
    const directoryFilters = document.querySelectorAll('[data-directory-filter]');
    const directoryCards = document.querySelectorAll('[data-directory-card]');
    const directoryCount = document.getElementById('hmDirectoryCount');

    function filterDirectory() {
        const query = (directorySearch ? directorySearch.value : '').toLowerCase();
        const activeFilter = document.querySelector('[data-directory-filter].is-active');
        const filterLabel = activeFilter ? activeFilter.getAttribute('data-directory-filter') : 'All';
        let visible = 0;

        directoryCards.forEach(function (card) {
            const name = (card.getAttribute('data-name') || '').toLowerCase();
            const city = (card.getAttribute('data-city') || '').toLowerCase();
            const status = card.getAttribute('data-status-label') || '';
            const matchesQuery = !query || name.includes(query) || city.includes(query);
            const matchesFilter = filterLabel === 'All' || status === filterLabel;
            const show = matchesQuery && matchesFilter;
            card.classList.toggle('hm-hidden', !show);
            if (show) visible++;
        });

        if (directoryCount) {
            directoryCount.textContent = visible + ' facilities found';
        }
    }

    if (directorySearch) directorySearch.addEventListener('input', filterDirectory);
    directoryFilters.forEach(function (btn) {
        btn.addEventListener('click', function () {
            directoryFilters.forEach(function (b) { b.classList.remove('is-active'); });
            btn.classList.add('is-active');
            filterDirectory();
        });
    });

    // Blog category filter
    const blogFilters = document.querySelectorAll('[data-blog-filter]');
    const blogFeatured = document.getElementById('hmBlogFeatured');
    const blogGrid = document.getElementById('hmBlogGrid');

    if (blogFilters.length && blogGrid) {
        const posts = JSON.parse(blogGrid.getAttribute('data-posts') || '[]');

        function renderBlog(category) {
            const filtered = category === 'All' ? posts : posts.filter(function (p) { return p.category === category; });
            const featured = filtered[0];
            const rest = filtered.slice(1);

            if (blogFeatured && featured) {
                blogFeatured.innerHTML =
                    '<p class="hm-eyebrow hm-mb-3">Featured · ' + featured.category + '</p>' +
                    '<h2 class="hm-section-title" style="font-size:clamp(1.5rem,3vw,1.875rem);">' + featured.title + '</h2>' +
                    '<p class="hm-text-muted" style="max-width:42rem;margin:1rem 0;">' + featured.excerpt + '</p>' +
                    '<p class="hm-post-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> ' + featured.date + '</p>';
                blogFeatured.classList.remove('hm-hidden');
            } else if (blogFeatured) {
                blogFeatured.classList.add('hm-hidden');
            }

            blogGrid.innerHTML = rest.map(function (p) {
                return '<article class="hm-card hm-post-card hm-reveal is-visible">' +
                    '<p class="hm-eyebrow" style="margin-bottom:0.75rem;font-size:0.75rem;">' + p.category + '</p>' +
                    '<h3 style="font-weight:700;margin:0 0 0.5rem;">' + p.title + '</h3>' +
                    '<p class="hm-text-muted" style="font-size:0.875rem;margin:0;">' + p.excerpt + '</p>' +
                    '<div class="hm-post-footer">' +
                    '<span class="hm-post-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> ' + p.date + '</span>' +
                    '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="hsl(0 72% 51%)" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>' +
                    '</div></article>';
            }).join('');
        }

        blogFilters.forEach(function (btn) {
            btn.addEventListener('click', function () {
                blogFilters.forEach(function (b) { b.classList.remove('is-active'); });
                btn.classList.add('is-active');
                renderBlog(btn.getAttribute('data-blog-filter'));
            });
        });
    }

    // Contact form
    const contactForm = document.getElementById('hmContactForm');
    const contactSuccess = document.getElementById('hmContactSuccess');
    if (contactForm && contactSuccess) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();
            contactForm.classList.add('hm-hidden');
            contactSuccess.classList.remove('hm-hidden');
        });
    }

    // Blog subscribe (prevent default)
    const subscribeForm = document.getElementById('hmSubscribeForm');
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', function (e) { e.preventDefault(); });
    }

    // Scroll to top on load (hash navigation)
    if (window.location.hash) {
        const target = document.querySelector(window.location.hash);
        if (target) target.scrollIntoView({ behavior: 'smooth' });
    }
})();
