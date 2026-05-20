<footer class="site-footer">
  <div class="footer-inner">
    <div class="footer-col footer-brand">
      <p class="footer-logo">Saffron &amp; Salt</p>
      <p>14 Gillygate, York YO31 7EQ</p>
      <p><a href="tel:+441904123456">01904 123 456</a></p>
      <p><a href="mailto:hello@saffronandsalt.co.uk">hello@saffronandsalt.co.uk</a></p>
    </div>

    <div class="footer-col">
      <h4>Opening Hours</h4>
      <table class="hours-table">
        <tr><td>Tuesday – Thursday</td><td>6pm – 9:30pm</td></tr>
        <tr><td>Friday – Saturday</td><td>6pm – 10pm</td></tr>
        <tr><td>Sunday</td><td>12pm – 3pm</td></tr>
        <tr><td>Monday</td><td>Closed</td></tr>
      </table>
    </div>

    <div class="footer-col">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="<?= $root ?? '' ?>menu.php">Menu</a></li>
        <li><a href="<?= $root ?? '' ?>reservations.php">Reserve a Table</a></li>
        <li><a href="<?= $root ?? '' ?>about.php">Our Story</a></li>
        <li><a href="<?= $root ?? '' ?>contact.php">Contact</a></li>
      </ul>
    </div>

    <div class="footer-col footer-extras">
      <h4>Listen</h4>
      <button class="audio-toggle" id="audio-toggle" aria-label="Toggle ambient music">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
        <span id="audio-label">Play ambient music</span>
      </button>
      <audio id="ambient-audio" loop preload="none">
        <source src="<?= $root ?? '' ?>audio/ambient.mp3" type="audio/mpeg">
      </audio>

      <div class="validation-badges">
        <a href="https://validator.w3.org/check?uri=referer" target="_blank" rel="noopener">
          <img src="https://www.w3.org/Icons/valid-html5" alt="Valid HTML5" width="88" height="31">
        </a>
        <a href="https://jigsaw.w3.org/css-validator/check/referer" target="_blank" rel="noopener">
          <img src="https://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS" width="88" height="31">
        </a>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; <?= date('Y') ?> Saffron &amp; Salt. All rights reserved.</p>
  </div>
</footer>

<script src="<?= $root ?? '' ?>js/main.js"></script>
<script src="<?= $root ?? '' ?>js/slider.js"></script>
<script src="<?= $root ?? '' ?>js/validation.js"></script>
</body>
</html>
