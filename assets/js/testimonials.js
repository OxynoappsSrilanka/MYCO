/**
 * MYCO Testimonials Slider
 * Custom slider with prev/next navigation, dot indicators, swipe support, and auto-play
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var track = document.querySelector('.testi-track');
    if (!track) return;

    var prevBtn = document.getElementById('testi-prev');
    var nextBtn = document.getElementById('testi-next');
    var dotsContainer = document.getElementById('testi-dots');
    var totalItems = parseInt(track.getAttribute('data-total-items'), 10) || 0;
    var resizeTimer;

    var current = 0;
    var total = 0;
    var cardsPerPage = 0;
    var autoPlayInterval;
    var originalCards = Array.prototype.slice.call(track.querySelectorAll('.testi-card'));

    if (totalItems > 0) {
      originalCards = originalCards.slice(0, totalItems);
    }

    if (!originalCards.length) return;

    function getCardsPerPage() {
      if (window.innerWidth <= 767) return 1;
      if (window.innerWidth <= 1024) return 2;
      return 3;
    }

    function getDots() {
      if (!dotsContainer) return [];
      return Array.prototype.slice.call(dotsContainer.querySelectorAll('button'));
    }

    function buildDots() {
      if (!dotsContainer) return;

      dotsContainer.innerHTML = '';

      for (var i = 0; i < total; i++) {
        var dot = document.createElement('button');
        dot.className = 'testi-dot' + (i === current ? ' active' : '');
        dot.dataset.index = i;
        dot.setAttribute('aria-label', 'Page ' + (i + 1));
        dot.setAttribute('aria-selected', i === current ? 'true' : 'false');
        dot.setAttribute('role', 'tab');
        dot.addEventListener('click', function () {
          goTo(parseInt(this.dataset.index, 10));
        });
        dotsContainer.appendChild(dot);
      }

      dotsContainer.hidden = total <= 1;
    }

    function buildPages() {
      var firstVisibleCardIndex = current * (cardsPerPage || getCardsPerPage());
      cardsPerPage = getCardsPerPage();
      var renderCards = originalCards.slice();

      while (renderCards.length > 1 && renderCards.length % cardsPerPage !== 0) {
        renderCards.push(originalCards[renderCards.length % originalCards.length]);
      }

      total = Math.max(1, Math.ceil(renderCards.length / cardsPerPage));
      track.innerHTML = '';

      for (var i = 0; i < renderCards.length; i += cardsPerPage) {
        var page = document.createElement('div');
        page.className = 'testi-page';

        for (var j = i; j < Math.min(i + cardsPerPage, renderCards.length); j++) {
          page.appendChild(renderCards[j].cloneNode(true));
        }

        track.appendChild(page);
      }

      current = Math.floor(firstVisibleCardIndex / cardsPerPage);
      if (current >= total) current = total - 1;
      if (current < 0) current = 0;

      buildDots();
      goTo(current, false);
    }

    function goTo(index, shouldResetAutoPlay) {
      if (index < 0) index = total - 1;
      if (index >= total) index = 0;
      current = index;
      track.style.transform = 'translateX(-' + (current * 100) + '%)';
      updateDots();

      if (shouldResetAutoPlay !== false) {
        resetAutoPlay();
      }
    }

    function updateDots() {
      getDots().forEach(function (dot, i) {
        if (i === current) {
          dot.classList.add('active');
          dot.setAttribute('aria-selected', 'true');
        } else {
          dot.classList.remove('active');
          dot.setAttribute('aria-selected', 'false');
        }
      });
    }

    // Navigation buttons
    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        goTo(current - 1);
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        goTo(current + 1);
      });
    }

    // Auto-play
    function startAutoPlay() {
      clearInterval(autoPlayInterval);
      if (total <= 1) return;

      autoPlayInterval = setInterval(function () {
        goTo(current + 1, false);
      }, 6000);
    }

    function resetAutoPlay() {
      clearInterval(autoPlayInterval);
      startAutoPlay();
    }

    buildPages();
    startAutoPlay();

    // Pause on hover
    var sliderContainer = track.closest('section') || track.parentElement;
    if (sliderContainer) {
      sliderContainer.addEventListener('mouseenter', function () {
        clearInterval(autoPlayInterval);
      });
      sliderContainer.addEventListener('mouseleave', function () {
        startAutoPlay();
      });
    }

    // Touch/swipe support
    var touchStartX = 0;
    var touchEndX = 0;

    track.addEventListener('touchstart', function (e) {
      touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    track.addEventListener('touchend', function (e) {
      touchEndX = e.changedTouches[0].screenX;
      var diff = touchStartX - touchEndX;
      if (Math.abs(diff) > 50) {
        if (diff > 0) {
          goTo(current + 1);
        } else {
          goTo(current - 1);
        }
      }
    }, { passive: true });

    // Keyboard navigation
    document.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft') goTo(current - 1);
      if (e.key === 'ArrowRight') goTo(current + 1);
    });

    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        var nextCardsPerPage = getCardsPerPage();
        if (nextCardsPerPage !== cardsPerPage) {
          buildPages();
          startAutoPlay();
        }
      }, 150);
    });
  });
})();
