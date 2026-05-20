/* ==========================================================================
   Saffron & Salt — slider.js
   Hero crossfade slideshow (used only on index.php).
   Expects: .hero-video-wrap contains either <video> or multiple <img> slides.
   ========================================================================== */

(function () {
  'use strict';

  // Only run if there are hero slides to rotate
  const slides = document.querySelectorAll('.hero-slide');
  if (slides.length < 2) return;

  let current  = 0;
  const total  = slides.length;
  const delay  = 5000; // ms between transitions

  function showSlide(index) {
    slides.forEach((s, i) => {
      s.classList.toggle('hero-slide--active', i === index);
    });
  }

  function next() {
    current = (current + 1) % total;
    showSlide(current);
  }

  // Initialise
  showSlide(0);
  let timer = setInterval(next, delay);

  // Pause on hover
  const hero = document.querySelector('.hero');
  if (hero) {
    hero.addEventListener('mouseenter', () => clearInterval(timer));
    hero.addEventListener('mouseleave', () => { timer = setInterval(next, delay); });
  }
})();
