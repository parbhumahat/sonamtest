<?php
$page_title = 'Sign In';
$page_desc  = 'Sign in to your Saffron &amp; Salt account to view and manage your reservations.';
$body_class = 'page-login';
require 'includes/header.php';

if (isset($_SESSION['user_id'])) {
    header('Location: account.php');
    exit;
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<main>
  <div class="page-hero">
    <div class="container">
      <p class="section-label">Your account</p>
      <h1>Sign In</h1>
      <p>View and manage your reservations, or create an account to get started.</p>
    </div>
  </div>

  <section class="section">
    <div class="container">
      <div class="auth-wrap">

        <?php if ($flash): ?>
        <div class="form-message <?= htmlspecialchars($flash['type']) ?>" style="display:block;">
          <?= htmlspecialchars($flash['message']) ?>
        </div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="auth-tabs" role="tablist">
          <button class="auth-tab active" id="tab-login"    role="tab" aria-controls="panel-login"    aria-selected="true">Sign in</button>
          <button class="auth-tab"        id="tab-register" role="tab" aria-controls="panel-register" aria-selected="false">Create account</button>
        </div>

        <!-- Login panel -->
        <div class="auth-panel active form-card" id="panel-login" role="tabpanel" aria-labelledby="tab-login">
          <div id="login-msg" class="form-message" role="alert" aria-live="polite"></div>

          <form id="login-form" method="post" action="handlers/auth.php" novalidate>
            <input type="hidden" name="action" value="login">

            <div class="form-group">
              <label for="l-email">Email address</label>
              <input type="email" id="l-email" name="email" required autocomplete="email" placeholder="you@example.com">
              <p class="field-error" id="err-l-email">Please enter your email address.</p>
            </div>

            <div class="form-group">
              <label for="l-password">Password</label>
              <input type="password" id="l-password" name="password" required autocomplete="current-password" placeholder="••••••••">
              <p class="field-error" id="err-l-password">Please enter your password.</p>
            </div>

            <button type="submit" class="btn btn--fill" style="width:100%;">Sign in</button>
          </form>
        </div>

        <!-- Register panel -->
        <div class="auth-panel form-card" id="panel-register" role="tabpanel" aria-labelledby="tab-register" style="display:none;">
          <div id="reg-msg" class="form-message" role="alert" aria-live="polite"></div>

          <form id="register-form" novalidate>
            <div class="form-group">
              <label for="r-name">Full name</label>
              <input type="text" id="r-name" name="name" required autocomplete="name" placeholder="Your name">
              <p class="field-error" id="err-r-name">Please enter your name.</p>
            </div>

            <div class="form-group">
              <label for="r-email">Email address</label>
              <input type="email" id="r-email" name="email" required autocomplete="email" placeholder="you@example.com">
              <p class="field-error" id="err-r-email">Please enter a valid email address.</p>
            </div>

            <div class="form-group">
              <label for="r-password">Password</label>
              <input type="password" id="r-password" name="password" required autocomplete="new-password" placeholder="At least 8 characters">
              <div class="strength-bar"><div class="strength-fill" id="strength-fill"></div></div>
              <p class="field-error" id="err-r-password">Password must be at least 8 characters.</p>
            </div>

            <div class="form-group">
              <label for="r-confirm">Confirm password</label>
              <input type="password" id="r-confirm" name="confirm" required autocomplete="new-password" placeholder="Repeat your password">
              <p class="field-error" id="err-r-confirm">Passwords do not match.</p>
            </div>

            <button type="submit" class="btn btn--fill" style="width:100%;">Create account</button>
          </form>
        </div>

      </div>
    </div>
  </section>
</main>

<script>
(function () {
  // Tab switching
  const tabs = document.querySelectorAll('.auth-tab');
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
      tab.classList.add('active');
      tab.setAttribute('aria-selected','true');

      document.querySelectorAll('.auth-panel').forEach(p => {
        p.style.display = 'none';
        p.classList.remove('active');
      });
      const panel = document.getElementById(tab.getAttribute('aria-controls'));
      panel.style.display = 'block';
      panel.classList.add('active');
    });
  });

  // Password strength meter
  const pwField = document.getElementById('r-password');
  const fill    = document.getElementById('strength-fill');
  if (pwField) {
    pwField.addEventListener('input', () => {
      const v = pwField.value;
      let score = 0;
      if (v.length >= 8)              score++;
      if (/[A-Z]/.test(v))           score++;
      if (/[0-9]/.test(v))           score++;
      if (/[^A-Za-z0-9]/.test(v))   score++;
      const pct    = ['0%','25%','50%','75%','100%'][score];
      const colour = ['#6b6b6b','var(--terracotta)','#e8a020','#a8c56e','#5cb85c'][score];
      fill.style.width      = pct;
      fill.style.background = colour;
    });
  }

  // Register form
  const regForm = document.getElementById('register-form');
  const regMsg  = document.getElementById('reg-msg');

  regForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    let valid = true;

    function check(id, errId, condition) {
      const el  = document.getElementById(id);
      const err = document.getElementById(errId);
      if (!condition(el.value.trim())) {
        el.classList.add('error'); err.classList.add('show'); valid = false;
      } else {
        el.classList.remove('error'); err.classList.remove('show');
      }
    }

    check('r-name',     'err-r-name',     v => v.length >= 2);
    check('r-email',    'err-r-email',    v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v));
    check('r-password', 'err-r-password', v => v.length >= 8);

    const pw  = document.getElementById('r-password').value;
    const cfm = document.getElementById('r-confirm').value;
    const errCfm = document.getElementById('err-r-confirm');
    const cfmEl  = document.getElementById('r-confirm');
    if (pw !== cfm) {
      cfmEl.classList.add('error'); errCfm.classList.add('show'); valid = false;
    } else {
      cfmEl.classList.remove('error'); errCfm.classList.remove('show');
    }

    if (!valid) return;

    const btn = regForm.querySelector('button[type=submit]');
    btn.textContent = 'Creating account…';
    btn.disabled = true;

    try {
      const body = new FormData(regForm);
      body.append('action', 'register');
      const res  = await fetch('handlers/auth.php', { method: 'POST', body });
      const data = await res.json();
      regMsg.className = 'form-message ' + (data.success ? 'success' : 'error');
      regMsg.textContent = data.message;
      if (data.success) setTimeout(() => { window.location = 'account.php'; }, 800);
    } catch {
      regMsg.className = 'form-message error';
      regMsg.textContent = 'Something went wrong. Please try again.';
    } finally {
      btn.textContent = 'Create account';
      btn.disabled = false;
    }
  });
})();
</script>

<?php require 'includes/footer.php'; ?>
