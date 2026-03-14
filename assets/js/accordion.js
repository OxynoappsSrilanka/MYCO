/**
 * MYCO Accordion Toggle
 * For Privacy Policy sections and FAQ items
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var headers = document.querySelectorAll('.accordion-header');

    headers.forEach(function (header) {
      header.addEventListener('click', function () {
        var item = this.closest('.accordion-item') || this.closest('.faq-item');
        if (!item) return;

        var isActive = item.classList.contains('active');

        // Optional: Close all others (accordion behavior)
        var siblings = item.parentElement.querySelectorAll('.accordion-item, .faq-item');
        siblings.forEach(function (sib) {
          sib.classList.remove('active');
        });

        // Toggle current
        if (!isActive) {
          item.classList.add('active');
        }
      });
    });
  });
})();
