/* ==========================================================================
   Saffron & Salt — validation.js
   Shared helper: clear field error when user starts correcting input.
   ========================================================================== */

(function () {
  'use strict';

  // For every form input/select/textarea, remove the error state as soon as
  // the user changes the value — they can see the fix is working.
  document.addEventListener('input', (e) => {
    const el = e.target;
    if (el.matches('input, select, textarea')) {
      el.classList.remove('error');
      const errEl = document.getElementById('err-' + el.id);
      if (errEl) errEl.classList.remove('show');
    }
  });

  document.addEventListener('change', (e) => {
    const el = e.target;
    if (el.matches('select')) {
      el.classList.remove('error');
      const errEl = document.getElementById('err-' + el.id);
      if (errEl) errEl.classList.remove('show');
    }
  });
})();
