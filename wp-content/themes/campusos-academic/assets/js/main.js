(function() {
    'use strict';

    // Mobile menu toggle
    var toggle = document.querySelector('.menu-toggle');
    var nav = document.getElementById('primary-navigation');
    if (toggle && nav) {
        toggle.addEventListener('click', function() {
            var expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
            nav.classList.toggle('is-open');
        });
    }

    // Mobile submenu toggle (click on parent items with children)
    var menuItems = document.querySelectorAll('.main-navigation .menu-item-has-children > a');
    menuItems.forEach(function(item) {
        item.addEventListener('click', function(e) {
            if (window.innerWidth <= 1023) {
                var parent = this.parentElement;
                if (!parent.classList.contains('menu-open')) {
                    e.preventDefault();
                    // Close other open items at same level
                    var siblings = parent.parentElement.querySelectorAll(':scope > .menu-open');
                    siblings.forEach(function(s) { s.classList.remove('menu-open'); });
                    parent.classList.add('menu-open');
                }
            }
        });
    });

    // Sticky header shadow on scroll
    var header = document.querySelector('.header-main');
    if (header) {
        window.addEventListener('scroll', function() {
            header.classList.toggle('is-scrolled', window.scrollY > 10);
        });
    }

    // Fix Elementor counter widgets that show "0"
    function initCounters() {
        var counters = document.querySelectorAll('.elementor-counter-number');
        if (!counters.length) return;
        counters.forEach(function(el) {
            if (el.dataset.counterInit) return;
            el.dataset.counterInit = '1';
            var end = parseInt(el.getAttribute('data-to-value'), 10);
            if (!end) return;
            var animated = false;
            function animate() {
                if (animated) return;
                animated = true;
                var duration = 2000;
                var startTime = null;
                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    var eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * end);
                    if (progress < 1) requestAnimationFrame(step);
                    else el.textContent = end;
                }
                requestAnimationFrame(step);
            }
            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            animate();
                            observer.disconnect();
                        }
                    });
                }, { threshold: 0.1 });
                observer.observe(el);
            } else {
                animate();
            }
            // Safety: if still 0 after 4s, just set the value
            setTimeout(function() { if (!animated) { el.textContent = end; } }, 4000);
        });
    }
    // Run after a short delay to ensure DOM is fully ready
    function tryInitCounters() {
        setTimeout(initCounters, 100);
        setTimeout(initCounters, 1000);
    }
    if (document.readyState === 'complete') tryInitCounters();
    else window.addEventListener('load', tryInitCounters);

    // FAQ Accordion
    function initFaqAccordion() {
        var faqQuestions = document.querySelectorAll('.faq-question');
        if (!faqQuestions.length) return;

        faqQuestions.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var expanded = this.getAttribute('aria-expanded') === 'true';
                var answer = this.nextElementSibling;

                // Close all other items in same accordion
                var accordion = this.closest('.faq-accordion');
                if (accordion) {
                    accordion.querySelectorAll('.faq-question[aria-expanded="true"]').forEach(function(otherBtn) {
                        if (otherBtn !== btn) {
                            otherBtn.setAttribute('aria-expanded', 'false');
                            otherBtn.nextElementSibling.hidden = true;
                        }
                    });
                }

                // Toggle current item
                this.setAttribute('aria-expanded', !expanded);
                answer.hidden = expanded;
            });
        });
    }

    // Initialize FAQ on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFaqAccordion);
    } else {
        initFaqAccordion();
    }

    // Stats V2 - Animated counters
    function initStatsV2Counters() {
        var counters = document.querySelectorAll('.stats-number-v2[data-count]');
        if (!counters.length) return;

        counters.forEach(function(el) {
            if (el.dataset.counterInit) return;
            el.dataset.counterInit = '1';
            var end = parseInt(el.getAttribute('data-count'), 10);
            if (!end) { el.textContent = '0'; return; }
            var animated = false;

            function animate() {
                if (animated) return;
                animated = true;
                var duration = 2000;
                var startTime = null;
                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    var eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * end);
                    if (progress < 1) requestAnimationFrame(step);
                    else el.textContent = end;
                }
                requestAnimationFrame(step);
            }

            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            animate();
                            observer.disconnect();
                        }
                    });
                }, { threshold: 0.1 });
                observer.observe(el);
            } else {
                animate();
            }
        });
    }

    // Staff Carousel navigation
    function initStaffCarousel() {
        var wrapper = document.querySelector('.staff-carousel-wrapper');
        if (!wrapper) return;
        var track = wrapper.querySelector('.staff-carousel-track');
        var prevBtn = wrapper.querySelector('.staff-carousel-prev');
        var nextBtn = wrapper.querySelector('.staff-carousel-next');
        if (!track || !prevBtn || !nextBtn) return;

        function getScrollAmount() {
            var card = track.querySelector('.staff-card');
            if (!card) return 300;
            var style = window.getComputedStyle(track);
            var gap = parseFloat(style.gap) || parseFloat(style.columnGap) || 20;
            return card.offsetWidth + gap;
        }

        prevBtn.addEventListener('click', function() {
            track.scrollBy({ left: -getScrollAmount() * 2, behavior: 'smooth' });
        });
        nextBtn.addEventListener('click', function() {
            track.scrollBy({ left: getScrollAmount() * 2, behavior: 'smooth' });
        });
    }

    // Init on load
    function initHomepage() {
        initStatsV2Counters();
        initStaffCarousel();
    }
    if (document.readyState === 'complete') {
        initHomepage();
    } else {
        window.addEventListener('load', initHomepage);
    }

    // Scroll fade-in animation
    function initScrollAnimations() {
        var elements = document.querySelectorAll('.homepage-section > .container, .profile-grid, .posts-grid, .error-404-content');
        if (!elements.length || !('IntersectionObserver' in window)) return;

        elements.forEach(function(el) { el.classList.add('fade-in-up'); });

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

        elements.forEach(function(el) { observer.observe(el); });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initScrollAnimations);
    } else {
        initScrollAnimations();
    }
})();
