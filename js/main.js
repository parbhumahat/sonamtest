/* ==========================================================================
   Saffron & Salt — main.js
   Handles: sticky header, mobile nav, scroll reveal, open/closed widget,
            audio toggle

   IntersectionObserver usage (scroll reveal) adapted from MDN documentation:
   https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API
   ========================================================================== */

(function () {
  'use strict';

  /* --- Sticky header ---------------------------------------------------- */
  const header = document.getElementById('site-header');
  if (header) {
    const onScroll = () => {
      header.classList.toggle('scrolled', window.scrollY > 60);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* --- Mobile nav toggle ------------------------------------------------- */
  const navToggle = document.getElementById('nav-toggle');
  const mainNav   = document.getElementById('main-nav');

  if (navToggle && mainNav) {
    navToggle.addEventListener('click', () => {
      const expanded = navToggle.getAttribute('aria-expanded') === 'true';
      navToggle.setAttribute('aria-expanded', String(!expanded));
      mainNav.classList.toggle('open', !expanded);
      document.body.style.overflow = expanded ? '' : 'hidden';
    });

    // Close nav when a link is clicked
    mainNav.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        navToggle.setAttribute('aria-expanded', 'false');
        mainNav.classList.remove('open');
        document.body.style.overflow = '';
      });
    });

    // Close on Escape
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && mainNav.classList.contains('open')) {
        navToggle.setAttribute('aria-expanded', 'false');
        mainNav.classList.remove('open');
        document.body.style.overflow = '';
        navToggle.focus();
      }
    });
  }

  /* --- Scroll reveal (IntersectionObserver) ------------------------------- */
  const revealEls = document.querySelectorAll('.reveal');
  if (revealEls.length && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );
    revealEls.forEach(el => observer.observe(el));
  } else {
    // Fallback: just show everything
    revealEls.forEach(el => el.classList.add('visible'));
  }

  /* --- Open / closed status widget --------------------------------------- */
  const statusDot  = document.getElementById('status-dot');
  const statusText = document.getElementById('status-text');

  if (statusDot && statusText) {
    const hours = {
      // day (0=Sun): [openHour, openMin, closeHour, closeMin] or null if closed
      0: [12, 0, 15, 0],  // Sunday: 12pm–3pm
      1: null,            // Monday: closed
      2: [18, 0, 21, 30], // Tuesday
      3: [18, 0, 21, 30], // Wednesday
      4: [18, 0, 21, 30], // Thursday
      5: [18, 0, 22, 0],  // Friday
      6: [18, 0, 22, 0],  // Saturday
    };

    const now  = new Date();
    const day  = now.getDay();
    const h    = now.getHours();
    const m    = now.getMinutes();
    const mins = h * 60 + m;

    const today = hours[day];
    if (!today) {
      statusDot.className   = 'status-dot';
      statusText.textContent = 'Closed today — open Tuesday from 6pm';
    } else {
      const openMins  = today[0] * 60 + today[1];
      const closeMins = today[2] * 60 + today[3];
      const pad = n => String(n).padStart(2, '0');
      const closeStr = `${today[2] > 12 ? today[2] - 12 : today[2]}:${pad(today[3])}${today[2] >= 12 ? 'pm' : 'am'}`;

      if (mins >= openMins && mins < closeMins) {
        statusDot.classList.add('open');
        statusText.textContent = `Open now — closes at ${closeStr}`;
      } else if (mins < openMins) {
        const openHr  = today[0] > 12 ? today[0] - 12 : today[0];
        const openStr = `${openHr}:${pad(today[1])}${today[0] >= 12 ? 'pm' : 'am'}`;
        statusText.textContent = `Closed — opens today at ${openStr}`;
      } else {
        statusText.textContent = 'Closed for today — see you next time';
      }
    }
  }

  /* --- Audio toggle (footer + contact page) ------------------------------- */
  const audio      = document.getElementById('ambient-audio');
  const toggleBtn  = document.getElementById('audio-toggle');
  const audioLabel = document.getElementById('audio-label');

  if (audio && toggleBtn) {
    let playing = false;

    function setPlaying(state) {
      playing = state;
      toggleBtn.classList.toggle('playing', playing);
      if (audioLabel) audioLabel.textContent = playing ? 'Pause ambient music' : 'Play ambient music';
      toggleBtn.setAttribute('aria-label', playing ? 'Pause ambient music' : 'Play ambient music');
    }

    toggleBtn.addEventListener('click', () => {
      if (playing) {
        audio.pause();
        setPlaying(false);
      } else {
        audio.play().then(() => setPlaying(true)).catch(() => {
          // Autoplay blocked — do nothing, user must interact again
        });
      }
    });

    audio.addEventListener('ended', () => setPlaying(false));
  }
})();
