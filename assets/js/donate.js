/**
 * MYCO Donation Form + Stripe Integration
 *
 * Flow:
 *  Step 1 – User picks amount, fund, type  ? clicks "Complete Donation"
 *  Step 2 – AJAX creates PaymentIntent     ? Stripe Element mounts
 *  Step 3 – User fills card details        ? clicks "Complete Donation"
 *  Step 4 – stripe.confirmPayment()        ? redirects to ?donation=success
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

    /* -- Guard: only run on donate page -------------------------- */
    var form = document.getElementById('donation-form');
    if (!form) return;

    /* -- Config from wp_localize_script -------------------------- */
    var cfg = (typeof myco_donate !== 'undefined') ? myco_donate : {};
    var ajaxUrl   = cfg.ajax_url   || '/wp-admin/admin-ajax.php';
    var nonce     = cfg.nonce      || '';
    var stripeKey = cfg.stripe_key || '';

    /* -- State ---------------------------------------------------- */
    var state = {
      donationType  : 'one-time',
      amount        : 0,
      fund          : 'general',
      coverFees     : true,
      feePercentage : parseFloat(cfg.fee_percentage || 3.5),
      donorEmail    : '',
      step          : 1
    };

    /* -- DOM refs ------------------------------------------------- */
    var amountBtns        = document.querySelectorAll('.amt-btn');
    var customContainer   = document.getElementById('custom-amount-container');
    var customInput       = document.getElementById('custom-amount');
    var fundSelect        = document.getElementById('fund-select');
    var coverFeesCheckbox = document.getElementById('cover-fees');
    var donationTabs      = document.querySelectorAll('.donation-tab');
    var emailInput        = document.getElementById('donor-email');
    var monthlyNotice     = document.getElementById('monthly-notice');
    var summaryAmount     = document.getElementById('summary-amount');
    var summaryFees       = document.getElementById('summary-fees');
    var summaryTotal      = document.getElementById('summary-total');
    var proceedBtn        = document.getElementById('complete-donation');
    var paymentSection    = document.getElementById('payment-element-section');
    var paymentEl         = document.getElementById('payment-element');
    var paymentError      = document.getElementById('payment-error');
    var completeBtn       = document.getElementById('complete-donation-btn');
    var backBtn           = document.getElementById('back-to-form');
    var step1             = document.getElementById('donation-step-1');
    var step2             = document.getElementById('donation-step-2');

    if (fundSelect && fundSelect.value) {
      state.fund = fundSelect.value;
    }
    if (coverFeesCheckbox) {
      state.coverFees = coverFeesCheckbox.checked;
    }

    /* -- Stripe objects (set in Step 2) --------------------------- */
    var stripe   = null;
    var elements = null;

    /* -------------------------------------------------------------
       STEP 1 — FORM INTERACTIONS
    ------------------------------------------------------------- */

    /* Donation type tabs */
    donationTabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        donationTabs.forEach(function (t) { t.classList.remove('active'); });
        this.classList.add('active');
        state.donationType = this.dataset.type || 'one-time';
        // Show/hide monthly recurring notice
        if (monthlyNotice) {
          monthlyNotice.style.display = state.donationType === 'monthly' ? '' : 'none';
        }
        // Email required indicator for monthly
        if (emailInput) {
          var lbl = document.querySelector('label[for="donor-email"]');
          if (state.donationType === 'monthly') {
            emailInput.required = true;
            emailInput.style.borderColor = '#C8402E';
            emailInput.style.boxShadow   = '0 0 0 3px rgba(200,64,46,0.15)';
            if (lbl) lbl.querySelector('span') && (lbl.querySelector('span').textContent = '— required for monthly');
          } else {
            emailInput.required = false;
            emailInput.style.borderColor = '';
            emailInput.style.boxShadow   = '';
            if (lbl) lbl.querySelector('span') && (lbl.querySelector('span').textContent = '— optional');
          }
        }
        updateSummary();
      });
    });

    /* Preset amount buttons */
    amountBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        amountBtns.forEach(function (b) { b.classList.remove('active'); });
        this.classList.add('active');

        if (this.dataset.amount === 'custom') {
          state.amount = 0;
          if (customContainer) customContainer.style.display = '';
          if (customInput) customInput.focus();
        } else {
          state.amount = parseFloat(this.dataset.amount) || 0;
          if (customContainer) customContainer.style.display = 'none';
          if (customInput) customInput.value = '';
        }
        updateSummary();
      });
    });

    /* Custom amount input */
    if (customInput) {
      customInput.addEventListener('input', function () {
        var val = parseFloat(this.value);
        state.amount = (val > 0) ? val : 0;
        updateSummary();
      });
    }

    /* Fund selection */
    if (fundSelect) {
      fundSelect.addEventListener('change', function () {
        state.fund = this.value;
      });
    }

    /* Cover fees toggle */
    if (coverFeesCheckbox) {
      coverFeesCheckbox.addEventListener('change', function () {
        state.coverFees = this.checked;
        updateSummary();
      });
    }

    /* -- Summary Calculator ---------------------------------------- */
    function updateSummary() {
      var amount = state.amount;
      var fees   = state.coverFees ? Math.round(amount * state.feePercentage) / 100 : 0;
      var total  = amount + fees;

      if (summaryAmount) summaryAmount.textContent = '$' + amount.toFixed(2);
      if (summaryFees)   summaryFees.textContent   = state.coverFees ? '+$' + fees.toFixed(2) : '$0.00';
      if (summaryTotal)  summaryTotal.textContent   = '$' + total.toFixed(2);

      if (proceedBtn) {
        proceedBtn.disabled             = amount < 1;
        proceedBtn.style.opacity        = amount >= 1 ? '1' : '0.5';
        proceedBtn.style.cursor         = amount >= 1 ? 'pointer' : 'not-allowed';
      }
    }

    /* -------------------------------------------------------------
       "PROCEED TO PAYMENT" — Create PaymentIntent + Mount Element
    ------------------------------------------------------------- */

    if (proceedBtn) {
      proceedBtn.addEventListener('click', function (e) {
        e.preventDefault();
        if (state.amount < 1) return;

        // Email required for monthly
        var email = emailInput ? emailInput.value.trim() : '';
        if (state.donationType === 'monthly' && !email) {
          if (emailInput) {
            emailInput.focus();
            emailInput.style.borderColor = '#DC2626';
            emailInput.style.boxShadow   = '0 0 0 3px rgba(220,38,38,0.2)';
            emailInput.placeholder = 'Email is required for monthly donations';
          }
          return;
        }

        setLoading(proceedBtn, true, 'Preparing secure donation form...');

        var fees        = state.coverFees ? Math.round(state.amount * state.feePercentage) / 100 : 0;
        var totalAmount = state.amount + fees;

        var formData = new FormData();
        formData.append('action',         'myco_create_payment_intent');
        formData.append('nonce',          nonce);
        formData.append('amount',         totalAmount.toFixed(2));
        formData.append('fund',           state.fund);
        formData.append('donation_type',  state.donationType);
        formData.append('cover_fees',     state.coverFees ? 1 : 0);
        formData.append('donor_email',    emailInput ? emailInput.value.trim() : '');

        fetch(ajaxUrl, { method: 'POST', body: formData, credentials: 'same-origin' })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            setLoading(proceedBtn, false, '');

            if (!data.success) {
              showError(data.data ? data.data.message : 'Could not prepare the donation form.');
              return;
            }

            var clientSecret = data.data.client_secret;
            mountPaymentElement(clientSecret, totalAmount);
          })
          .catch(function () {
            setLoading(proceedBtn, false, '');
            showError('Connection error. Please try your donation again.');
          });
      });
    }

    /* -- Mount Stripe Payment Element ----------------------------- */
    function mountPaymentElement(clientSecret, totalAmount) {
      if (!stripeKey) {
        showError('Online donations are not configured right now. Please contact our team.');
        return;
      }

      /* Switch to Step 2 */
      if (step1) step1.style.display = 'none';
      if (step2) step2.style.display = '';

      /* Update step-2 summary */
      var s2amount = document.getElementById('s2-amount');
      var s2fund   = document.getElementById('s2-fund');
      var s2type   = document.getElementById('s2-type');
      if (s2amount) s2amount.textContent = '$' + totalAmount.toFixed(2);
      if (s2fund)   s2fund.textContent   = fundSelect ? fundSelect.options[fundSelect.selectedIndex].text : state.fund;
      if (s2type)   s2type.textContent   = state.donationType === 'monthly' ? 'Monthly Donation' : 'One-Time Donation';

      /* Init Stripe */
      stripe   = Stripe(stripeKey);
      elements = stripe.elements({
        clientSecret : clientSecret,
        appearance   : {
          theme : 'stripe',
          variables: {
            colorPrimary       : '#C8402E',
            colorBackground    : '#ffffff',
            colorText          : '#141943',
            colorDanger        : '#df1b41',
            fontFamily         : 'Inter, sans-serif',
            spacingUnit        : '4px',
            borderRadius       : '10px',
            fontSizeBase       : '15px',
          },
          rules: {
            '.Input': {
              border       : '1.5px solid #e5e7eb',
              boxShadow    : 'none',
              padding      : '12px 16px',
            },
            '.Input:focus': {
              border       : '1.5px solid #C8402E',
              boxShadow    : '0 0 0 3px rgba(200,64,46,0.12)',
            },
            '.Label': {
              fontWeight   : '600',
              color        : '#374151',
              marginBottom : '6px',
            },
          }
        }
      });

      var paymentElement = elements.create('payment', {
        layout: 'tabs'
      });

      if (paymentEl) paymentEl.innerHTML = '';
      paymentElement.mount('#payment-element');
    }

    /* -- Back to Form --------------------------------------------- */
    if (backBtn) {
      backBtn.addEventListener('click', function () {
        if (step2) step2.style.display = 'none';
        if (step1) step1.style.display = '';
        if (paymentEl) paymentEl.innerHTML = '';
        stripe   = null;
        elements = null;
        hideError();
      });
    }

    /* -------------------------------------------------------------
       "COMPLETE DONATION" — Confirm Payment
    ------------------------------------------------------------- */

    if (completeBtn) {
      completeBtn.addEventListener('click', function (e) {
        e.preventDefault();
        if (!stripe || !elements) return;

        setLoading(completeBtn, true, 'Finalizing donation...');
        hideError();

        var returnUrl = (cfg.return_url || window.location.href.split('?')[0]) + '?donation=success';

        stripe.confirmPayment({
          elements   : elements,
          confirmParams: {
            return_url : returnUrl,
          }
        }).then(function (result) {
          if (result.error) {
            setLoading(completeBtn, false, '');
            showError(result.error.message);
          }
          /* On success Stripe redirects automatically */
        });
      });
    }

    /* -------------------------------------------------------------
       HELPERS
    ------------------------------------------------------------- */

    function setLoading(btn, loading, text) {
      if (!btn) return;
      btn.disabled = loading;
      if (loading) {
        btn.dataset.origText = btn.textContent;
        if (text) btn.textContent = text;
        btn.style.opacity = '0.7';
      } else {
        btn.textContent    = btn.dataset.origText || (btn.id === 'complete-donation-btn' ? 'Confirm Donation' : 'Continue to Donate');
        btn.style.opacity  = '1';
        btn.disabled       = false;
      }
    }

    function showError(msg) {
      if (paymentError) {
        paymentError.textContent  = msg;
        paymentError.style.display = '';
      }
    }

    function hideError() {
      if (paymentError) {
        paymentError.textContent  = '';
        paymentError.style.display = 'none';
      }
    }

    function applyPrefillFromQuery() {
      var params = new URLSearchParams(window.location.search);
      var requestedType = params.get('type');
      var requestedFund = params.get('fund');
      var requestedAmount = parseFloat(params.get('amount') || '0');

      if (requestedType) {
        donationTabs.forEach(function (tab) {
          if (tab.dataset.type === requestedType) {
            tab.click();
          }
        });
      }

      if (requestedFund && fundSelect) {
        var hasFund = Array.prototype.some.call(fundSelect.options, function (option) {
          return option.value === requestedFund;
        });

        if (hasFund) {
          fundSelect.value = requestedFund;
          state.fund = requestedFund;
        }
      }

      if (requestedAmount > 0) {
        var matchedButton = false;

        amountBtns.forEach(function (btn) {
          if (btn.dataset.amount !== 'custom' && parseFloat(btn.dataset.amount) === requestedAmount) {
            btn.click();
            matchedButton = true;
          }
        });

        if (!matchedButton) {
          var customBtn = document.querySelector('.amt-btn[data-amount="custom"]');
          if (customBtn) {
            customBtn.click();
          }
          if (customInput) {
            customInput.value = requestedAmount;
          }
          state.amount = requestedAmount;
        }
      }
    }

    /* -- Init ----------------------------------------------------- */
    applyPrefillFromQuery();
    updateSummary();
  });
})();


