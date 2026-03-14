/**
 * MYCO Newsletter Signup
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('.newsletter-form');

    forms.forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();

        var emailInput = form.querySelector('input[type="email"]');
        var submitBtn = form.querySelector('button[type="submit"]');
        var messageEl = form.querySelector('.newsletter-message');

        if (!emailInput || !emailInput.value) return;

        var originalText = submitBtn ? submitBtn.textContent : '';
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.textContent = 'Subscribing...';
        }

        var formData = new FormData();
        formData.append('action', 'myco_newsletter_signup');
        formData.append('nonce', typeof myco_ajax !== 'undefined' ? myco_ajax.nonce : '');
        formData.append('email', emailInput.value);

        var ajaxUrl = typeof myco_ajax !== 'undefined' ? myco_ajax.ajax_url : '/wp-admin/admin-ajax.php';

        fetch(ajaxUrl, {
          method: 'POST',
          body: formData,
          credentials: 'same-origin'
        })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            if (data.success) {
              emailInput.value = '';
              if (messageEl) {
                messageEl.textContent = data.data.message || 'Thank you for subscribing!';
                messageEl.style.color = '#16a34a';
              }
            } else {
              if (messageEl) {
                messageEl.textContent = data.data.message || 'An error occurred.';
                messageEl.style.color = '#C8402E';
              }
            }
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.textContent = originalText;
            }
          })
          .catch(function () {
            if (messageEl) {
              messageEl.textContent = 'Connection error. Please try again.';
              messageEl.style.color = '#C8402E';
            }
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.textContent = originalText;
            }
          });
      });
    });
  });
})();
