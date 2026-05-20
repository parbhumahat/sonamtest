<?php
$page_title = 'Reserve a Table';
$page_desc  = 'Book your table at Saffron &amp; Salt in York. Available Tuesday through Sunday.';
$body_class = 'page-reservations';
require 'includes/header.php';
?>

<main>
  <div class="page-hero">
    <div class="container">
      <p class="section-label">Come and sit with us</p>
      <h1>Reserve a Table</h1>
      <p>We're a small restaurant — 30 covers — and we fill up quickly, especially on weekends. Book ahead to avoid disappointment.</p>
    </div>
  </div>

  <section class="section">
    <div class="container">
      <div class="reservation-wrap">

        <!-- Info column -->
        <div class="reservation-info reveal">
          <h2>Before you book</h2>
          <p>
            Reservations are available Tuesday through Sunday. We seat guests at 6pm, 7pm, 7:30pm, 8pm, 8:30pm and 9pm.
            Tables are held for 15 minutes past your booking time — please call ahead if you're running late.
          </p>
          <p>
            For groups larger than 8, or to enquire about private hire, please <a href="contact.php">contact us directly</a>.
          </p>

          <ul class="info-list">
            <li><span>Tuesday – Thursday</span> <strong>6pm – 9:30pm</strong></li>
            <li><span>Friday – Saturday</span>  <strong>6pm – 10pm</strong></li>
            <li><span>Sunday</span>             <strong>12pm – 3pm (lunch only)</strong></li>
            <li><span>Monday</span>             <strong>Closed</strong></li>
            <li><span>Cancellations</span>      <strong>Please give 24hrs notice</strong></li>
          </ul>
        </div>

        <!-- Booking form -->
        <div class="reveal">
          <div class="form-card">
            <h3 style="margin-bottom:1.5rem;">Book your visit</h3>

            <div id="form-message" class="form-message" role="alert" aria-live="polite"></div>

            <form id="reservation-form" novalidate>
              <div class="form-row">
                <div class="form-group">
                  <label for="res-name">Full name <span aria-hidden="true">*</span></label>
                  <input type="text" id="res-name" name="name" required autocomplete="name" placeholder="Layla Shirazi">
                  <p class="field-error" id="err-name">Please enter your name.</p>
                </div>
                <div class="form-group">
                  <label for="res-phone">Phone number <span aria-hidden="true">*</span></label>
                  <input type="tel" id="res-phone" name="phone" required autocomplete="tel" placeholder="07700 900000">
                  <p class="field-error" id="err-phone">Please enter a phone number.</p>
                </div>
              </div>

              <div class="form-group">
                <label for="res-email">Email address <span aria-hidden="true">*</span></label>
                <input type="email" id="res-email" name="email" required autocomplete="email" placeholder="you@example.com">
                <p class="field-error" id="err-email">Please enter a valid email address.</p>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="res-date">Date <span aria-hidden="true">*</span></label>
                  <input type="date" id="res-date" name="date" required>
                  <p class="field-error" id="err-date">Please choose a date today or later.</p>
                </div>
                <div class="form-group">
                  <label for="res-time">Time <span aria-hidden="true">*</span></label>
                  <select id="res-time" name="time" required>
                    <option value="">Select a time</option>
                    <option value="18:00">6:00pm</option>
                    <option value="18:30">6:30pm</option>
                    <option value="19:00">7:00pm</option>
                    <option value="19:30">7:30pm</option>
                    <option value="20:00">8:00pm</option>
                    <option value="20:30">8:30pm</option>
                    <option value="21:00">9:00pm</option>
                    <option value="21:30">9:30pm</option>
                  </select>
                  <p class="field-error" id="err-time">Please select a time.</p>
                </div>
              </div>

              <div class="form-group">
                <label for="res-guests">Party size <span aria-hidden="true">*</span></label>
                <select id="res-guests" name="guests" required>
                  <option value="">Number of guests</option>
                  <?php for ($i = 1; $i <= 8; $i++): ?>
                  <option value="<?= $i ?>"><?= $i ?> <?= $i === 1 ? 'guest' : 'guests' ?></option>
                  <?php endfor; ?>
                </select>
                <p class="field-error" id="err-guests">Please select party size.</p>
              </div>

              <div class="form-group">
                <label for="res-notes">Dietary requirements or notes</label>
                <textarea id="res-notes" name="notes" placeholder="Allergies, special occasions, seating preferences..."></textarea>
              </div>

              <button type="submit" class="btn btn--fill" style="width:100%;justify-content:center;" id="submit-btn">
                Confirm Reservation
              </button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </section>
</main>

<script>
(function () {
  // Set date minimum to today
  const dateInput = document.getElementById('res-date');
  if (dateInput) {
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);
  }

  const form = document.getElementById('reservation-form');
  const msgBox = document.getElementById('form-message');
  const submitBtn = document.getElementById('submit-btn');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!validateReservationForm()) return;

    submitBtn.textContent = 'Sending…';
    submitBtn.disabled = true;
    msgBox.className = 'form-message';

    try {
      const res = await fetch('handlers/reservation.php', {
        method: 'POST',
        body: new FormData(form),
      });
      const data = await res.json();

      if (data.success) {
        msgBox.className = 'form-message success';
        msgBox.textContent = data.message;
        form.reset();
      } else {
        msgBox.className = 'form-message error';
        msgBox.textContent = data.message || 'Something went wrong. Please try again.';
      }
    } catch {
      msgBox.className = 'form-message error';
      msgBox.textContent = 'Unable to send your request. Please try again or call us directly.';
    } finally {
      submitBtn.textContent = 'Confirm Reservation';
      submitBtn.disabled = false;
      msgBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  });

  function validateReservationForm() {
    let valid = true;

    function check(id, errorId, condition) {
      const input = document.getElementById(id);
      const err   = document.getElementById(errorId);
      if (!condition(input.value.trim())) {
        input.classList.add('error');
        err.classList.add('show');
        valid = false;
      } else {
        input.classList.remove('error');
        err.classList.remove('show');
      }
    }

    check('res-name',   'err-name',   v => v.length >= 2);
    check('res-phone',  'err-phone',  v => v.length >= 7);
    check('res-email',  'err-email',  v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v));
    check('res-date',   'err-date',   v => v && v >= new Date().toISOString().split('T')[0]);
    check('res-time',   'err-time',   v => v !== '');
    check('res-guests', 'err-guests', v => v !== '');

    return valid;
  }
})();
</script>

<?php require 'includes/footer.php'; ?>
