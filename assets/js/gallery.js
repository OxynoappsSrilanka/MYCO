/**
 * MYCO Gallery — Filter + Lightbox
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var galleryItems = document.querySelectorAll('.gallery-item');
    var lightbox = document.getElementById('lightbox');
    var lightboxImg = document.getElementById('lightbox-img');
    var lightboxCaption = document.getElementById('lightbox-caption');
    var lightboxCounter = document.getElementById('lightbox-counter');
    var lightboxClose = document.getElementById('lightbox-close');
    var lightboxPrev = document.getElementById('lightbox-prev');
    var lightboxNext = document.getElementById('lightbox-next');

    var currentImages = [];
    var currentIndex = 0;

    // ─── Filter ─────────────────────────────────────────────
    window.filterGallery = function (album, btn) {
      // Update active tab
      document.querySelectorAll('.album-tab').forEach(function (tab) {
        tab.classList.remove('active');
      });
      if (btn) btn.classList.add('active');

      // Filter items
      galleryItems.forEach(function (item) {
        var itemAlbum = item.dataset.album || '';
        if (album === 'all' || itemAlbum === album) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    };

    // ─── Lightbox ───────────────────────────────────────────
    function buildImageList() {
      currentImages = [];
      galleryItems.forEach(function (item) {
        if (item.style.display !== 'none') {
          var img = item.querySelector('img');
          if (img) {
            currentImages.push({
              src: img.src,
              alt: img.alt || '',
              caption: item.dataset.caption || img.alt || ''
            });
          }
        }
      });
    }

    window.openLightbox = function (index) {
      buildImageList();
      if (index >= 0 && index < currentImages.length) {
        currentIndex = index;
        updateLightbox();
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
      }
    };

    window.closeLightbox = function () {
      lightbox.classList.remove('active');
      document.body.style.overflow = '';
    };

    window.navigateLightbox = function (direction) {
      currentIndex += direction;
      if (currentIndex < 0) currentIndex = currentImages.length - 1;
      if (currentIndex >= currentImages.length) currentIndex = 0;
      updateLightbox();
    };

    function updateLightbox() {
      if (!currentImages[currentIndex]) return;
      lightboxImg.src = currentImages[currentIndex].src;
      lightboxImg.alt = currentImages[currentIndex].alt;
      if (lightboxCaption) {
        lightboxCaption.textContent = currentImages[currentIndex].caption;
      }
      if (lightboxCounter) {
        lightboxCounter.textContent = (currentIndex + 1) + ' / ' + currentImages.length;
      }
    }

    // Event listeners
    if (lightboxClose) {
      lightboxClose.addEventListener('click', closeLightbox);
    }
    if (lightboxPrev) {
      lightboxPrev.addEventListener('click', function () { navigateLightbox(-1); });
    }
    if (lightboxNext) {
      lightboxNext.addEventListener('click', function () { navigateLightbox(1); });
    }

    // Click gallery items to open lightbox
    galleryItems.forEach(function (item, index) {
      item.addEventListener('click', function () {
        // Recalculate index based on visible items
        var visibleIndex = 0;
        var visibleItems = [];
        galleryItems.forEach(function (gi) {
          if (gi.style.display !== 'none') visibleItems.push(gi);
        });
        visibleIndex = visibleItems.indexOf(item);
        openLightbox(visibleIndex >= 0 ? visibleIndex : index);
      });
    });

    // Close on backdrop click
    if (lightbox) {
      lightbox.addEventListener('click', function (e) {
        if (e.target === lightbox) closeLightbox();
      });
    }

    // Keyboard navigation
    document.addEventListener('keydown', function (e) {
      if (!lightbox || !lightbox.classList.contains('active')) return;
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') navigateLightbox(-1);
      if (e.key === 'ArrowRight') navigateLightbox(1);
    });
  });
})();
