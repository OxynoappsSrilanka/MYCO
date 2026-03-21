(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var page = document.querySelector('.mcyc-page');
    if (!page) return;

    var donateLink = document.getElementById('mcyc-donate-link');
    var amountButtons = page.querySelectorAll('.mcyc-donation-btn');
    var typeButtons = page.querySelectorAll('.mcyc-gift-type-btn');
    var customAmount = document.getElementById('mcyc-custom-amount');
    var sectionLinks = page.querySelectorAll('[data-section-link]');
    var cfg = typeof myco_mcyc !== 'undefined' ? myco_mcyc : {};
    var sectionTargets = [];
    var state = {
      amount: 50,
      type: 'one-time'
    };

    sectionLinks.forEach(function (link) {
      var targetId = link.dataset.sectionLink;
      var target = targetId ? document.getElementById(targetId) : null;

      if (target) {
        sectionTargets.push({
          id: targetId,
          element: target
        });
      }
    });

    function updateDonateHref() {
      if (!donateLink || !cfg.donate_url) return;

      var url = new URL(cfg.donate_url, window.location.origin);
      url.searchParams.set('fund', cfg.fund || 'mcyc');
      url.searchParams.set('type', state.type);
      if (state.amount > 0) {
        url.searchParams.set('amount', String(state.amount));
      }
      donateLink.href = url.toString();
    }

    amountButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        amountButtons.forEach(function (btn) {
          btn.classList.remove('is-selected');
        });
        button.classList.add('is-selected');
        state.amount = parseFloat(button.dataset.amount || '0') || 0;
        if (customAmount) customAmount.value = '';
        updateDonateHref();
      });
    });

    typeButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        typeButtons.forEach(function (btn) {
          btn.classList.remove('is-active');
        });
        button.classList.add('is-active');
        state.type = button.dataset.type || 'one-time';
        updateDonateHref();
      });
    });

    if (customAmount) {
      customAmount.addEventListener('input', function () {
        amountButtons.forEach(function (btn) {
          btn.classList.remove('is-selected');
        });
        state.amount = Math.max(0, parseFloat(customAmount.value || '0') || 0);
        updateDonateHref();
      });
    }

    page.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
      anchor.addEventListener('click', function (event) {
        var href = anchor.getAttribute('href');
        if (!href || href === '#') return;

        var target = document.querySelector(href);
        if (!target) return;

        event.preventDefault();
        var top = target.getBoundingClientRect().top + window.pageYOffset - 110;
        window.scrollTo({ top: top, behavior: 'smooth' });
      });
    });

    function updateActiveSection() {
      var current = sectionTargets.length ? sectionTargets[0].id : '';

      sectionTargets.forEach(function (target) {
        if (window.pageYOffset >= target.element.offsetTop - 170) {
          current = target.id;
        }
      });

      sectionLinks.forEach(function (link) {
        link.classList.toggle('is-active', link.dataset.sectionLink === current);
      });
    }

    if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
          }
        });
      }, { threshold: 0.14, rootMargin: '0px 0px -80px 0px' });

      page.querySelectorAll('.mcyc-fade-in').forEach(function (element) {
        observer.observe(element);
      });
    } else {
      page.querySelectorAll('.mcyc-fade-in').forEach(function (element) {
        element.classList.add('is-visible');
      });
    }

    window.addEventListener('scroll', updateActiveSection, { passive: true });
    updateActiveSection();
    updateDonateHref();
  });
})();
