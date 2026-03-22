/**
 * MYCO Mobile Navigation - IMPROVED
 * Hamburger menu toggle with accessibility support and smooth animations
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var root = document.documentElement;
    var siteHeader = document.querySelector('.site-header');
    var siteHeaderInner = siteHeader ? siteHeader.querySelector('.site-header-inner') : null;
    var hamburger = document.getElementById('hamburger');
    var mobileMenu = document.getElementById('mobile-menu');

    function syncSiteHeaderOffset() {
      if (!siteHeader) {
        return;
      }

      var measuredHeader = siteHeaderInner || siteHeader;
      var headerHeight = Math.ceil(measuredHeader.getBoundingClientRect().height);
      var extraGap = window.innerWidth >= 1024 ? 4 : 10;

      root.style.setProperty('--site-header-height', headerHeight + 'px');
      root.style.setProperty('--site-header-offset', (headerHeight + extraGap) + 'px');
    }

    syncSiteHeaderOffset();
    window.addEventListener('load', syncSiteHeaderOffset);

    if (siteHeader && 'ResizeObserver' in window) {
      var headerObserver = new ResizeObserver(function () {
        syncSiteHeaderOffset();
      });

      headerObserver.observe(siteHeader);
    }

    if (!hamburger || !mobileMenu) {
      return;
    }

    // Toggle mobile menu with smooth animation
    function toggleMenu(forceClose) {
      var isOpen = !mobileMenu.classList.contains('hidden');

      if (isOpen || forceClose) {
        // Close menu
        mobileMenu.classList.add('hidden');
        hamburger.setAttribute('aria-expanded', 'false');
        hamburger.setAttribute('aria-label', 'Open navigation menu');
        document.body.style.overflow = ''; // Re-enable scrolling
      } else {
        // Open menu
        mobileMenu.classList.remove('hidden');
        hamburger.setAttribute('aria-expanded', 'true');
        hamburger.setAttribute('aria-label', 'Close navigation menu');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
        
        // Focus first link for accessibility
        var firstLink = mobileMenu.querySelector('a');
        if (firstLink) {
          setTimeout(function() { firstLink.focus(); }, 100);
        }
      }
    }

    hamburger.addEventListener('click', function () {
      toggleMenu();
    });

    // Close menu when a link inside is clicked
    mobileMenu.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        toggleMenu(true);
      });
    });

    // Close menu on outside click
    document.addEventListener('click', function (e) {
      if (
        !hamburger.contains(e.target) &&
        !mobileMenu.contains(e.target) &&
        !mobileMenu.classList.contains('hidden')
      ) {
        toggleMenu(true);
      }
    });

    // Close menu on ESC key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
        toggleMenu(true);
        hamburger.focus(); // Return focus to hamburger
      }
    });

    // Handle window resize - close mobile menu if resizing to desktop
    var resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        syncSiteHeaderOffset();

        if (window.innerWidth >= 768 && !mobileMenu.classList.contains('hidden')) {
          toggleMenu(true);
        }
      }, 250);
    });

    // Add active class to current page nav item
    var currentPath = window.location.pathname;
    document.querySelectorAll('.pill-nav a').forEach(function (link) {
      var linkPath = new URL(link.href).pathname;
      if (linkPath === currentPath) {
        link.classList.add('active');
        link.setAttribute('aria-current', 'page');
      }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
      anchor.addEventListener('click', function (e) {
        var href = this.getAttribute('href');
        if (href === '#') return;

        var target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          var headerOffset = parseFloat(getComputedStyle(root).getPropertyValue('--site-header-height')) || 80;
          var elementPosition = target.getBoundingClientRect().top;
          var offsetPosition = elementPosition + window.pageYOffset - headerOffset;

          window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
          });

          // Update URL without jumping
          history.pushState(null, null, href);
          
          // Focus target for accessibility
          target.setAttribute('tabindex', '-1');
          target.focus();
        }
      });
    });
  });
})();

/**
 * Mobile Search Expand
 * On mobile (≤767px), clicking the search button expands an input overlay
 * over the filter tabs. Works for gallery, news, and events filter bars.
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    if (window.innerWidth > 1024) return;

    var configs = [
      {
        barSel: '.gallery-filter-bar',
        tabsSel: '.gallery-filter-tabs',
        inputId: 'gallery-search',
        btnSel: '.gallery-filter-bar div[style*="width: 380px"] button',
      },
      {
        barSel: '.news-filter-bar',
        tabsSel: '.news-filter-tabs',
        inputId: 'news-search',
        btnSel: '.news-filter-bar div[style*="width: 380px"] button',
      },
      {
        barSel: '.filter-search-container',
        tabsSel: '.filter-group',
        inputId: 'program-search',
        btnSel: '.programs-search-btn',
      },
      {
        barSel: '.events-controls',
        tabsSel: '.events-filter-tabs',
        inputId: 'events-search-input',
        btnSel: null, // events uses a label/input, no separate button
      },
    ];

    configs.forEach(function (cfg) {
      var bar = document.querySelector(cfg.barSel);
      var tabs = document.querySelector(cfg.tabsSel);
      var input = cfg.inputId ? document.getElementById(cfg.inputId) : null;
      var btn = cfg.btnSel ? document.querySelector(cfg.btnSel) : null;

      if (!bar || !input) return;

      // For gallery/news: button toggles expand
      if (btn) {
        btn.addEventListener('click', function (e) {
          var isOpen = bar.classList.contains('search-expanded');
          if (isOpen) {
            // collapse
            bar.classList.remove('search-expanded');
            input.style.display = 'none';
            input.value = '';
            // trigger clear search
            input.dispatchEvent(new Event('input'));
            input.dispatchEvent(new Event('keyup'));
          } else {
            // expand
            bar.classList.add('search-expanded');
            input.style.display = 'block';
            input.focus();
          }
          e.stopPropagation();
        });

        // Collapse on outside click
        document.addEventListener('click', function (e) {
          if (bar.classList.contains('search-expanded') && !bar.contains(e.target)) {
            bar.classList.remove('search-expanded');
            input.style.display = 'none';
            input.value = '';
            input.dispatchEvent(new Event('input'));
            input.dispatchEvent(new Event('keyup'));
          }
        });
      }
    });
  });
})();
