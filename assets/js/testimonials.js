/**
 * MYCO Testimonials Slider
 * Custom slider with prev/next navigation, dot indicators, swipe support, and auto-play
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var track = document.querySelector('.testi-track');
    if (!track) return;

    var pages = track.querySelectorAll('.testi-page');
    var prevBtn = document.getElementById('testi-prev');
    var nextBtn = document.getElementById('testi-next');
    var dotsContainer = document.getElementById('testi-dots');

    var current = 0;
    var total = pages.length;
    var autoPlayInterval;

    // Create dots
    if (dotsContainer) {
      for (var i = 0; i < total; i++) {
        var dot = document.createElement('button');
        dot.className = 'w-3 h-3 rounded-full transition-all duration-300 ' +
          (i === 0 ? 'bg-red w-8' : 'bg-gray-300 hover:bg-gray-400');
        dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
        dot.dataset.index = i;
        dot.addEventListener('click', function () {
          goTo(parseInt(this.dataset.index));
        });
        dotsContainer.appendChild(dot);
      }
    }

    function goTo(index) {
      if (index < 0) index = total - 1;
      if (index >= total) index = 0;
      current = index;
      track.style.transform = 'translateX(-' + (current * 100) + '%)';
      updateDots();
      resetAutoPlay();
    }

    function updateDots() {
      if (!dotsContainer) return;
      var dots = dotsContainer.querySelectorAll('button');
      dots.forEach(function (dot, i) {
        if (i === current) {
          dot.className = 'w-8 h-3 rounded-full transition-all duration-300 bg-red';
        } else {
          dot.className = 'w-3 h-3 rounded-full transition-all duration-300 bg-gray-300 hover:bg-gray-400';
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
      autoPlayInterval = setInterval(function () {
        goTo(current + 1);
      }, 6000);
    }

    function resetAutoPlay() {
      clearInterval(autoPlayInterval);
      startAutoPlay();
    }

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
  });
})();
