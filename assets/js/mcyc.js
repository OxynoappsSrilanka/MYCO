(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var page = document.querySelector('.mcyc-page');
    if (!page) return;

    var amountButtons = page.querySelectorAll('.mcyc-donation-btn');
    var typeButtons   = page.querySelectorAll('.mcyc-gift-type-btn');
    var customAmount  = document.getElementById('mcyc-custom-amount');
    var sectionLinks  = page.querySelectorAll('[data-section-link]');
    var sectionTargets = [];

    // ── Donation widget state ──────────────────────────────────────────
    var donationState = {
      amount        : 50,
      donationType  : 'one-time',
      coverFees     : true,
      feePercentage : 3.5,
      fund          : 'mcyc',
      stripe        : null,
      elements      : null,
    };

    // ── DOM refs ───────────────────────────────────────────────────────
    var step1          = document.getElementById('mcyc-donate-step-1');
    var step2          = document.getElementById('mcyc-donate-step-2');
    var submitBtn      = document.getElementById('mcyc-donate-submit');
    var confirmBtn     = document.getElementById('mcyc-confirm-btn');
    var backBtn        = document.getElementById('mcyc-donate-back');
    var errorBox       = document.getElementById('mcyc-donate-error');
    var paymentError   = document.getElementById('mcyc-payment-error');
    var paymentEl      = document.getElementById('mcyc-payment-element');
    var s2Amount       = document.getElementById('mcyc-s2-amount');
    var s2Type         = document.getElementById('mcyc-s2-type');
    var customWrap     = document.getElementById('mcyc-custom-wrap');
    var customInput    = document.getElementById('mcyc-custom-amount');
    var fundSelect     = document.getElementById('mcyc-fund-select');
    var coverFeesChk   = document.getElementById('mcyc-cover-fees');
    var emailInput     = document.getElementById('mcyc-donor-email');
    var monthlyNotice  = document.getElementById('mcyc-monthly-notice');
    var sumAmount      = document.getElementById('mcyc-sum-amount');
    var sumFees        = document.getElementById('mcyc-sum-fees');
    var sumTotal       = document.getElementById('mcyc-sum-total');

    // ── Config from wp_localize_script ─────────────────────────────────
    var cfg      = (typeof myco_donate !== 'undefined') ? myco_donate : {};
    var ajaxUrl  = cfg.ajax_url   || '/wp-admin/admin-ajax.php';
    var nonce    = cfg.nonce      || '';
    var stripeKey = cfg.stripe_key || '';

    // ── Amount preset buttons ──────────────────────────────────────────
    amountButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        amountButtons.forEach(function (b) { b.classList.remove('is-selected'); });
        btn.classList.add('is-selected');
        donationState.amount = parseFloat(btn.dataset.amount || '0') || 0;
        // Clear custom input when preset selected
        if (customInput) {
          customInput.value = '';
          customInput.style.borderColor = '#E5E7EB';
          customInput.style.background = '#F9FAFB';
        }
        updateSummary();
      });
    });

    // ── Custom amount input — clears preset when typing ────────────────
    if (customInput) {
      customInput.addEventListener('input', function () {
        var val = parseFloat(customInput.value || '0') || 0;
        if (val > 0) {
          // Clear any preset selection
          amountButtons.forEach(function (b) { b.classList.remove('is-selected'); });
          donationState.amount = val;
        } else {
          donationState.amount = 0;
        }
        updateSummary();
      });

      customInput.addEventListener('focus', function () {
        customInput.style.borderColor = '#C8402E';
        customInput.style.background = 'white';
      });

      customInput.addEventListener('blur', function () {
        customInput.style.borderColor = '#E5E7EB';
        if (!customInput.value) customInput.style.background = '#F9FAFB';
      });
    }

    // ── Donation type toggle ───────────────────────────────────────────
    typeButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        typeButtons.forEach(function (b) { b.classList.remove('is-active'); });
        btn.classList.add('is-active');
        donationState.donationType = btn.dataset.type || 'one-time';
        if (monthlyNotice) monthlyNotice.style.display = donationState.donationType === 'monthly' ? '' : 'none';
        updateSummary();
      });
    });

    // ── Fund selector ──────────────────────────────────────────────────
    if (fundSelect) {
      fundSelect.addEventListener('change', function () {
        donationState.fund = fundSelect.value;
      });
    }

    // ── Cover fees ─────────────────────────────────────────────────────
    if (coverFeesChk) {
      coverFeesChk.addEventListener('change', function () {
        donationState.coverFees = coverFeesChk.checked;
        updateSummary();
      });
    }

    // ── Summary calculator ─────────────────────────────────────────────
    function updateSummary() {
      var amount = donationState.amount;
      var fees   = donationState.coverFees ? Math.round(amount * donationState.feePercentage) / 100 : 0;
      var total  = amount + fees;
      if (sumAmount) sumAmount.textContent = '$' + amount.toFixed(2);
      if (sumFees)   sumFees.textContent   = donationState.coverFees ? '+$' + fees.toFixed(2) : '$0.00';
      if (sumTotal)  sumTotal.textContent  = '$' + total.toFixed(2);
    }

    // init summary with default $50 selected
    updateSummary();

    // ── "Continue to Donate" ───────────────────────────────────────────
    if (submitBtn) {
      submitBtn.addEventListener('click', function () {
        var amount = donationState.amount;
        if (customInput && parseFloat(customInput.value) > 0) {
          amount = parseFloat(customInput.value);
          donationState.amount = amount;
        }
        if (amount < 1) {
          showError(errorBox, 'Please select or enter a donation amount.');
          return;
        }
        // Email required for monthly
        var email = emailInput ? emailInput.value.trim() : '';
        if (donationState.donationType === 'monthly' && !email) {
          if (emailInput) { emailInput.focus(); emailInput.style.borderColor = '#DC2626'; }
          showError(errorBox, 'Email is required for monthly donations.');
          return;
        }
        hideError(errorBox);
        setLoading(submitBtn, true, 'Preparing secure form…');

        var fees        = donationState.coverFees ? Math.round(amount * donationState.feePercentage) / 100 : 0;
        var totalAmount = amount + fees;

        var formData = new FormData();
        formData.append('action',        'myco_create_payment_intent');
        formData.append('nonce',         nonce);
        formData.append('amount',        totalAmount.toFixed(2));
        formData.append('fund',          donationState.fund || 'mcyc');
        formData.append('donation_type', donationState.donationType);
        formData.append('cover_fees',    donationState.coverFees ? 1 : 0);
        formData.append('donor_email',   email);

        fetch(ajaxUrl, { method: 'POST', body: formData, credentials: 'same-origin' })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            setLoading(submitBtn, false, 'Continue to Donate');
            if (!data.success) {
              showError(errorBox, (data.data && data.data.message) ? data.data.message : 'Could not prepare the donation form.');
              return;
            }
            mountPaymentElement(data.data.client_secret, totalAmount);
          })
          .catch(function () {
            setLoading(submitBtn, false, 'Continue to Donate');
            showError(errorBox, 'Connection error. Please try again.');
          });
      });
    }

    // ── Mount Stripe Payment Element ───────────────────────────────────
    function mountPaymentElement(clientSecret, amount) {
      if (!stripeKey) {
        showError(errorBox, 'Online donations are not configured. Please contact our team.');
        return;
      }

      if (step1) step1.style.display = 'none';
      if (step2) step2.style.display = '';

      if (s2Amount) s2Amount.textContent = '$' + amount.toFixed(2);
      if (s2Type)   s2Type.textContent   = donationState.donationType === 'monthly' ? 'Monthly Donation' : 'One-Time Donation';

      donationState.stripe   = Stripe(stripeKey);
      donationState.elements = donationState.stripe.elements({
        clientSecret : clientSecret,
        appearance   : {
          theme     : 'stripe',
          variables : {
            colorPrimary    : '#C8402E',
            colorBackground : '#ffffff',
            colorText       : '#141943',
            colorDanger     : '#df1b41',
            fontFamily      : 'Inter, sans-serif',
            borderRadius    : '10px',
            fontSizeBase    : '15px',
          },
          rules: {
            '.Input'       : { border: '1.5px solid #e5e7eb', boxShadow: 'none', padding: '12px 16px' },
            '.Input:focus' : { border: '1.5px solid #C8402E', boxShadow: '0 0 0 3px rgba(200,64,46,0.12)' },
            '.Label'       : { fontWeight: '600', color: '#374151', marginBottom: '6px' },
          }
        }
      });

      var paymentElement = donationState.elements.create('payment', { layout: 'tabs' });
      if (paymentEl) paymentEl.innerHTML = '';
      paymentElement.mount('#mcyc-payment-element');
    }

    // ── Back button ────────────────────────────────────────────────────
    if (backBtn) {
      backBtn.addEventListener('click', function () {
        if (step2) step2.style.display = 'none';
        if (step1) step1.style.display = '';
        if (paymentEl) paymentEl.innerHTML = '';
        donationState.stripe   = null;
        donationState.elements = null;
        hideError(paymentError);
      });
    }

    // ── "Confirm Donation" ─────────────────────────────────────────────
    if (confirmBtn) {
      confirmBtn.addEventListener('click', function () {
        if (!donationState.stripe || !donationState.elements) return;
        setLoading(confirmBtn, true, 'Finalizing donation…');
        hideError(paymentError);

        var returnUrl = (cfg.return_url || window.location.href.split('?')[0]) + '?donation=success';

        donationState.stripe.confirmPayment({
          elements      : donationState.elements,
          confirmParams : { return_url: returnUrl }
        }).then(function (result) {
          if (result.error) {
            setLoading(confirmBtn, false, 'Confirm Donation');
            showError(paymentError, result.error.message);
          }
          // On success Stripe redirects automatically
        });
      });
    }

    // ── Helpers ────────────────────────────────────────────────────────
    function setLoading(btn, loading, label) {
      if (!btn) return;
      btn.disabled = loading;
      if (loading) {
        btn.dataset.origText = btn.textContent;
        btn.textContent      = label;
        btn.style.opacity    = '0.7';
      } else {
        btn.textContent   = label || btn.dataset.origText || '';
        btn.style.opacity = '1';
        btn.disabled      = false;
      }
    }

    function showError(el, msg) {
      if (!el) return;
      el.textContent   = msg;
      el.style.display = '';
    }

    function hideError(el) {
      if (!el) return;
      el.textContent   = '';
      el.style.display = 'none';
    }

    // ── Podcast Player factory ─────────────────────────────────────────
    function initPodcastPlayer(cfg) {
      var epCover      = document.getElementById(cfg.cover);
      var epTitle      = document.getElementById(cfg.title);
      var epSubtitle   = cfg.subtitle ? document.getElementById(cfg.subtitle) : null;
      var progressFill  = document.getElementById(cfg.fill);
      var progressThumb = document.getElementById(cfg.thumb);
      var progressTrack = document.getElementById(cfg.track);
      var timeCur      = document.getElementById(cfg.timeCur);
      var timeTot      = document.getElementById(cfg.timeTot);
      var playBtn      = document.getElementById(cfg.playBtn);
      var playIcon     = document.getElementById(cfg.playIcon);
      var pauseIcon    = document.getElementById(cfg.pauseIcon);
      var epPrev       = document.getElementById(cfg.epPrev);
      var epNext       = document.getElementById(cfg.epNext);
      var epItems      = document.querySelectorAll('#' + cfg.epTrack + ' .mcyc-ep-item');
      var waveformBars = document.querySelectorAll('#' + cfg.waveform + ' .mcyc-waveform-bar');

      if (!playBtn) return; // player not on this page

      var state = { playing: false, progress: 0.32, activeEp: 0, ticker: null };

      function tick() {
        state.progress += 0.002;
        if (state.progress >= 1) state.progress = 0;
        updateProgress(state.progress);
      }

      function updateProgress(pct) {
        var p = Math.min(Math.max(pct, 0), 1) * 100;
        if (progressFill)  progressFill.style.width = p + '%';
        if (progressThumb) progressThumb.style.left  = p + '%';
        waveformBars.forEach(function (bar, i) {
          bar.classList.toggle('is-played', (i / waveformBars.length) * 100 <= p);
        });
        var totalSecs = 192;
        var cur = Math.floor(pct * totalSecs);
        if (timeCur) timeCur.textContent = Math.floor(cur / 60) + ':' + ('0' + (cur % 60)).slice(-2);
      }

      function setPlaying(playing) {
        state.playing = playing;
        if (playIcon)  playIcon.style.display  = playing ? 'none' : '';
        if (pauseIcon) pauseIcon.style.display = playing ? ''     : 'none';
        if (playing) {
          state.ticker = setInterval(tick, 100);
        } else {
          clearInterval(state.ticker);
        }
      }

      function activateEpisode(index) {
        epItems.forEach(function (item, i) { item.classList.toggle('is-active', i === index); });
        state.activeEp = index;
        state.progress = 0;
        updateProgress(0);
        var ep = epItems[index];
        if (ep) {
          if (epTitle)    epTitle.textContent    = ep.dataset.title    || '';
          if (epSubtitle) epSubtitle.textContent = ep.dataset.position || '';
          if (epCover) {
            epCover.src = ep.dataset.cover || epCover.src;
          }
          if (timeTot) timeTot.textContent = ep.dataset.dur || '—';
        }
        if (state.playing) { clearInterval(state.ticker); state.ticker = setInterval(tick, 100); }
      }

      playBtn.addEventListener('click', function () { setPlaying(!state.playing); });

      epItems.forEach(function (item, i) {
        item.addEventListener('click', function () { activateEpisode(i); });
      });

      if (epPrev) epPrev.addEventListener('click', function () {
        activateEpisode((state.activeEp - 1 + epItems.length) % epItems.length);
      });
      if (epNext) epNext.addEventListener('click', function () {
        activateEpisode((state.activeEp + 1) % epItems.length);
      });

      if (progressTrack) {
        progressTrack.addEventListener('click', function (e) {
          var rect = progressTrack.getBoundingClientRect();
          state.progress = (e.clientX - rect.left) / rect.width;
          updateProgress(state.progress);
        });
      }

      updateProgress(state.progress);
    }

    // Init player 1 (building section — now video player, no podcast init needed)
    // ── Video Player ───────────────────────────────────────────────────
    (function () {
      var video      = document.getElementById('mcyc-main-video');
      if (!video) return;

      var overlayBtn  = document.getElementById('mcyc-vp-overlay-btn');
      var bigPlayIcon = document.getElementById('mcyc-vp-big-play-icon');
      var bigPauseIcon= document.getElementById('mcyc-vp-big-pause-icon');
      var playBtn     = document.getElementById('mcyc-vp-play-btn');
      var playIcon    = document.getElementById('mcyc-vp-play-icon');
      var pauseIcon   = document.getElementById('mcyc-vp-pause-icon');
      var fill        = document.getElementById('mcyc-vp-fill');
      var thumb       = document.getElementById('mcyc-vp-thumb');
      var progressWrap= document.getElementById('mcyc-vp-progress-wrap');
      var timeEl      = document.getElementById('mcyc-vp-time');
      var volSlider   = document.getElementById('mcyc-vp-vol');
      var fsBtn       = document.getElementById('mcyc-vp-fullscreen');
      var titleEl     = document.getElementById('mcyc-vp-title');
      var thumbItems  = document.querySelectorAll('.mcyc-vp-thumb-item');
      var sliderEl    = document.getElementById('mcyc-vp-slider');
      var prevBtn     = document.getElementById('mcyc-vp-prev');
      var nextBtn     = document.getElementById('mcyc-vp-next');

      function fmt(s) {
        s = Math.floor(s || 0);
        return Math.floor(s / 60) + ':' + ('0' + (s % 60)).slice(-2);
      }

      function syncPlayState(playing) {
        if (playIcon)     playIcon.style.display     = playing ? 'none' : '';
        if (pauseIcon)    pauseIcon.style.display    = playing ? ''     : 'none';
        if (bigPlayIcon)  bigPlayIcon.style.display  = playing ? 'none' : '';
        if (bigPauseIcon) bigPauseIcon.style.display = playing ? ''     : 'none';
        if (overlayBtn)   overlayBtn.classList.toggle('is-playing', playing);
      }

      function togglePlay() {
        if (video.paused) { video.play(); } else { video.pause(); }
      }

      if (overlayBtn) overlayBtn.addEventListener('click', togglePlay);
      if (playBtn)    playBtn.addEventListener('click', togglePlay);

      video.addEventListener('play',  function () { syncPlayState(true); });
      video.addEventListener('pause', function () { syncPlayState(false); });
      video.addEventListener('ended', function () { syncPlayState(false); });

      video.addEventListener('timeupdate', function () {
        if (!video.duration) return;
        var pct = (video.currentTime / video.duration) * 100;
        if (fill)  fill.style.width = pct + '%';
        if (thumb) thumb.style.left  = pct + '%';
        if (timeEl) timeEl.textContent = fmt(video.currentTime) + ' / ' + fmt(video.duration);
      });

      if (progressWrap) {
        progressWrap.addEventListener('click', function (e) {
          var rect = progressWrap.getBoundingClientRect();
          video.currentTime = ((e.clientX - rect.left) / rect.width) * (video.duration || 0);
        });
      }

      if (volSlider) {
        video.volume = 0.8;
        volSlider.addEventListener('input', function () { video.volume = volSlider.value / 100; });
      }

      if (fsBtn) {
        fsBtn.addEventListener('click', function () {
          if (video.requestFullscreen) video.requestFullscreen();
          else if (video.webkitRequestFullscreen) video.webkitRequestFullscreen();
        });
      }

      // Thumbnail slider — clicking swaps poster/title
      thumbItems.forEach(function (item, i) {
        item.addEventListener('click', function () {
          thumbItems.forEach(function (t) { t.classList.remove('is-active'); });
          item.classList.add('is-active');
          var wasPlaying = !video.paused;
          video.pause();
          video.src    = item.dataset.src    || video.src;
          video.poster = item.dataset.poster || '';
          if (titleEl) titleEl.textContent = item.dataset.title || '';
          if (fill)  fill.style.width = '0%';
          if (thumb) thumb.style.left  = '0%';
          if (timeEl) timeEl.textContent = '0:00 / 0:00';
          video.load();
          if (wasPlaying) video.play();
        });
      });

      // Slider scroll arrows
      if (prevBtn && sliderEl) {
        prevBtn.addEventListener('click', function () { sliderEl.scrollBy({ left: -220, behavior: 'smooth' }); });
      }
      if (nextBtn && sliderEl) {
        nextBtn.addEventListener('click', function () { sliderEl.scrollBy({ left: 220, behavior: 'smooth' }); });
      }
    }());

    // Init player 2 (community stories section)
    initPodcastPlayer({
      cover: 'mcyc-ep-cover-2', title: 'mcyc-ep-title-2', subtitle: 'mcyc-ep-subtitle-2',
      fill: 'mcyc-progress-fill-2', thumb: 'mcyc-progress-thumb-2', track: 'mcyc-progress-track-2',
      timeCur: 'mcyc-time-cur-2', timeTot: 'mcyc-time-tot-2',
      playBtn: 'mcyc-play-btn-2', playIcon: 'mcyc-play-icon-2', pauseIcon: 'mcyc-pause-icon-2',
      epPrev: 'mcyc-ep-prev-2', epNext: 'mcyc-ep-next-2',
      epTrack: 'mcyc-ep-track-2', waveform: 'mcyc-waveform-2',
    });

    // ── Construction Photo Slider ──────────────────────────────────────
    (function () {
      var mainImg = document.getElementById('mcyc-cslider-main-img');
      var caption = document.getElementById('mcyc-cslider-caption');
      var counter = document.getElementById('mcyc-cslider-counter');
      var prevBtn = document.getElementById('mcyc-cslider-prev');
      var nextBtn = document.getElementById('mcyc-cslider-next');
      var thumbs  = document.querySelectorAll('.mcyc-cslider-thumb');
      if (!mainImg || !thumbs.length) return;

      var current = 0;
      var total   = thumbs.length;

      function goTo(index) {
        current = (index + total) % total;
        var btn = thumbs[current];
        mainImg.style.opacity = '0';
        setTimeout(function () {
          mainImg.src = btn.dataset.src;
          mainImg.alt = btn.dataset.alt || '';
          if (caption) caption.textContent = btn.dataset.alt || '';
          if (counter) counter.textContent = (current + 1) + ' / ' + total;
          mainImg.style.opacity = '1';
        }, 180);
        thumbs.forEach(function (t, i) { t.classList.toggle('is-active', i === current); });
        btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
      }

      thumbs.forEach(function (btn, i) {
        btn.addEventListener('click', function () { goTo(i); });
      });
      if (prevBtn) prevBtn.addEventListener('click', function () { goTo(current - 1); });
      if (nextBtn) nextBtn.addEventListener('click', function () { goTo(current + 1); });
    }());

    sectionLinks.forEach(function (link) {
      var targetId = link.dataset.sectionLink;
      var target   = targetId ? document.getElementById(targetId) : null;
      if (target) sectionTargets.push({ id: targetId, element: target });
    });

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
      sectionTargets.forEach(function (t) {
        if (window.pageYOffset >= t.element.offsetTop - 170) current = t.id;
      });
      sectionLinks.forEach(function (link) {
        link.classList.toggle('is-active', link.dataset.sectionLink === current);
      });
    }

    if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) entry.target.classList.add('is-visible');
        });
      }, { threshold: 0.14, rootMargin: '0px 0px -80px 0px' });
      page.querySelectorAll('.mcyc-fade-in').forEach(function (el) { observer.observe(el); });
    } else {
      page.querySelectorAll('.mcyc-fade-in').forEach(function (el) { el.classList.add('is-visible'); });
    }

    window.addEventListener('scroll', updateActiveSection, { passive: true });
    updateActiveSection();
  });
})();
