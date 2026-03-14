/**
 * IMPROVED MYCO Navigation
 * 
 * IMPROVEMENTS:
 * - Smooth animations
 * - Better accessibility
 * - Keyboard navigation support
 * - Focus trap in mobile menu
 * - ESC key to close
 * 
 * @package MYCO
 */

(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobile-menu');

    if (!hamburger || !mobileMenu) {
      return;
    }

    // Toggle mobile menu
    function toggleMenu(forceClose = false) {
      const isOpen = !mobileMenu.classList.contains('hidden');

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
        const firstLink = mobileMenu.querySelector('a');
        if (firstLink) {
          setTimeout(() => firstLink.focus(), 100);
        }
      }
    }

    // Hamburger click
    hamburger.addEventListener('click', function () {
      toggleMenu();
    });

    // Close menu when a link is clicked
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

    // Trap focus inside mobile menu when open
    mobileMenu.addEventListener('keydown', function (e) {
      if (e.key === 'Tab') {
        const focusableElements = mobileMenu.querySelectorAll(
          'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (e.shiftKey && document.activeElement === firstElement) {
          // Shift+Tab on first element -> go to last
          e.preventDefault();
          lastElement.focus();
        } else if (!e.shiftKey && document.activeElement === lastElement) {
          // Tab on last element -> go to first
          e.preventDefault();
          firstElement.focus();
        }
      }
    });

    // Handle window resize - close mobile menu if resizing to desktop
    let resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        if (window.innerWidth >= 768 && !mobileMenu.classList.contains('hidden')) {
          toggleMenu(true);
        }
      }, 250);
    });

    // Add active class to current page nav item
    const currentPath = window.location.pathname;
    document.querySelectorAll('.pill-nav a, .mobile-menu-link').forEach(function (link) {
      const linkPath = new URL(link.href).pathname;
      if (linkPath === currentPath) {
        link.classList.add('active');
        link.setAttribute('aria-current', 'page');
      }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
      anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href === '#') return;

        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          const headerOffset = 80;
          const elementPosition = target.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

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

    // Sticky header on scroll
    const header = document.querySelector('header');
    if (header) {
      let lastScroll = 0;
      
      window.addEventListener('scroll', function () {
        const currentScroll = window.pageYOffset;

        if (currentScroll <= 0) {
          header.classList.remove('shadow-md');
        } else {
          header.classList.add('shadow-md');
        }

        // Optional: Hide header on scroll down, show on scroll up
        // if (currentScroll > lastScroll && currentScroll > 100) {
        //   header.style.transform = 'translateY(-100%)';
        // } else {
        //   header.style.transform = 'translateY(0)';
        // }

        lastScroll = currentScroll;
      });
    }

  });
})();
