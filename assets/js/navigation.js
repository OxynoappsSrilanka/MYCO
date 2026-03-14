/**
 * MYCO Mobile Navigation - IMPROVED
 * Hamburger menu toggle with accessibility support and smooth animations
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var hamburger = document.getElementById('hamburger');
    var mobileMenu = document.getElementById('mobile-menu');

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
          var headerOffset = 80;
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
