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
            if (window.innerWidth <= 1024) {
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
            if (window.scrollY > 10) {
                header.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
            } else {
                header.style.boxShadow = 'none';
            }
        });
    }
})();
