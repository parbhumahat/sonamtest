<?php
$page_title = 'Contact';
$page_desc  = 'Get in touch with Saffron &amp; Salt. Find us on Gillygate in York, or send us a message.';
$body_class = 'page-contact';
require 'includes/header.php';
?>

<main>
  <div class="page-hero">
    <div class="container">
      <p class="section-label">We'd love to hear from you</p>
      <h1>Contact Us</h1>
      <p>For bookings of 9 or more, private hire, press enquiries, or just to say hello — drop us a message below.</p>
    </div>
  </div>

  <section class="section">
    <div class="container">
      <div class="contact-wrap">

        <!-- Contact form -->
        <div class="reveal">
          <h2 style="margin-bottom:0.5rem;">Send a message</h2>
          <p style="color:var(--text-muted);margin-bottom:2rem;">We aim to reply within one working day.</p>

          <div id="contact-message" class="form-message" role="alert" aria-live="polite"></div>

          <form id="contact-form" novalidate>
            <div class="form-row">
              <div class="form-group">
                <label for="c-name">Your name <span aria-hidden="true">*</span></label>
                <input type="text" id="c-name" name="name" required autocomplete="name" placeholder="Full name">
                <p class="field-error" id="err-c-name">Name is required.</p>
              </div>
              <div class="form-group">
                <label for="c-email">Email address <span aria-hidden="true">*</span></label>
                <input type="email" id="c-email" name="email" required autocomplete="email" placeholder="you@example.com">
                <p class="field-error" id="err-c-email">A valid email is required.</p>
              </div>
            </div>

            <div class="form-group">
              <label for="c-subject">Subject <span aria-hidden="true">*</span></label>
              <select id="c-subject" name="subject" required>
                <option value="">Select a topic</option>
                <option value="Reservation enquiry">Reservation enquiry</option>
                <option value="Private hire">Private hire</option>
                <option value="Press &amp; media">Press &amp; media</option>
                <option value="Feedback">Feedback</option>
                <option value="General">General</option>
              </select>
              <p class="field-error" id="err-c-subject">Please choose a subject.</p>
            </div>

            <div class="form-group">
              <label for="c-message">Message <span aria-hidden="true">*</span></label>
              <textarea id="c-message" name="message" required placeholder="Tell us what's on your mind..." maxlength="1000"></textarea>
              <p class="char-count"><span id="char-count">0</span> / 1000</p>
              <p class="field-error" id="err-c-message">Please write a message (at least 10 characters).</p>
            </div>

            <button type="submit" class="btn btn--fill" id="contact-submit">Send Message</button>
          </form>
        </div>

        <!-- Info panel -->
        <aside class="reveal" style="padding-top:4rem;">
          <div class="contact-detail">
            <h4>Find us</h4>
            <p>14 Gillygate<br>York, YO31 7EQ</p>
          </div>

          <div class="contact-detail">
            <h4>Call us</h4>
            <p><a href="tel:+441904123456">01904 123 456</a></p>
          </div>

          <div class="contact-detail">
            <h4>Email</h4>
            <p><a href="mailto:hello@saffronandsalt.co.uk">hello@saffronandsalt.co.uk</a></p>
          </div>

          <div class="contact-detail">
            <h4>Opening Hours</h4>
            <table class="hours-table">
              <tr><td>Tuesday – Thursday</td><td>6pm – 9:30pm</td></tr>
              <tr><td>Friday – Saturday</td><td>6pm – 10pm</td></tr>
              <tr><td>Sunday</td><td>12pm – 3pm</td></tr>
              <tr><td>Monday</td><td>Closed</td></tr>
            </table>
          </div>

          <div class="map-embed">
            <!-- Map provided by OpenStreetMap contributors (© OpenStreetMap)
                 Licence: https://www.openstreetmap.org/copyright
                 Embedded via the OSM export endpoint — no API key required -->
            <iframe
              src="https://www.openstreetmap.org/export/embed.html?bbox=-1.0921%2C53.9572%2C-1.0721%2C53.9672&amp;layer=mapnik"
              title="Map showing Saffron &amp; Salt location on Gillygate, York"
              loading="lazy"
              aria-label="Map of restaurant location"
            ></iframe>
          </div>

          <!-- Audio toggle -->
          <div style="margin-top:2rem;">
            <h4 style="margin-bottom:0.75rem;">Atmosphere</h4>
            <button class="audio-toggle" id="audio-toggle-contact" aria-label="Toggle ambient music">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
              Play ambient music
            </button>
          </div>
        </aside>

      </div>
    </div>
  </section>
</main>

<script>
(function () {
  // Character counter
  const msgField = document.getElementById('c-message');
  const counter  = document.getElementById('char-count');
  if (msgField) {
    msgField.addEventListener('input', () => {
      counter.textContent = msgField.value.length;
    });
  }

  // Contact form submission
  const form      = document.getElementById('contact-form');
  const msgBox    = document.getElementById('contact-message');
  const submitBtn = document.getElementById('contact-submit');

  form.addEventListener('submit', async (e) => {
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
    check('c-name',    'err-c-name',    v => v.length >= 2);
    check('c-email',   'err-c-email',   v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v));
    check('c-subject', 'err-c-subject', v => v !== '');
    check('c-message', 'err-c-message', v => v.length >= 10);
    if (!valid) return;

    submitBtn.textContent = 'Sending…';
    submitBtn.disabled = true;

    try {
      const res  = await fetch('handlers/contact.php', { method: 'POST', body: new FormData(form) });
      const data = await res.json();
      msgBox.className = 'form-message ' + (data.success ? 'success' : 'error');
      msgBox.textContent = data.message;
      if (data.success) { form.reset(); counter.textContent = '0'; }
    } catch {
      msgBox.className = 'form-message error';
      msgBox.textContent = 'Could not send your message. Please try emailing us directly.';
    } finally {
      submitBtn.textContent = 'Send Message';
      submitBtn.disabled = false;
      msgBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  });

  // Wire the secondary audio toggle to the same audio element as the footer
  const secondaryToggle = document.getElementById('audio-toggle-contact');
  if (secondaryToggle) {
    secondaryToggle.addEventListener('click', () => {
      document.getElementById('audio-toggle')?.click();
    });
  }
})();
</script>

<?php require 'includes/footer.php'; ?>
