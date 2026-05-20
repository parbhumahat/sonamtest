<?php
$page_title = 'Gallery';
$page_desc  = 'Photos from the Saffron &amp; Salt kitchen, dining room and events. Food, interiors and more.';
$body_class = 'page-gallery';

// Gallery data — categories: food, interiors, events
$gallery = [
  ['src' => 'images/g-khoresh.jpg',    'caption' => 'Lamb Khoresh — slow-braised for six hours',       'cat' => 'food'],
  ['src' => 'images/g-interior1.jpg',  'caption' => 'The dining room, early evening',                  'cat' => 'interiors'],
  ['src' => 'images/g-skewers.jpg',    'caption' => 'Saffron chicken skewers over charcoal',           'cat' => 'food'],
  ['src' => 'images/g-event1.jpg',     'caption' => 'Persian New Year dinner, March 2024',              'cat' => 'events'],
  ['src' => 'images/g-hummus.jpg',     'caption' => 'Smoked hummus with dukkah and warm flatbread',    'cat' => 'food'],
  ['src' => 'images/g-interior2.jpg',  'caption' => 'Candlelight at table four',                       'cat' => 'interiors'],
  ['src' => 'images/g-lovecake.jpg',   'caption' => 'Persian Love Cake with crystallised rose petals', 'cat' => 'food'],
  ['src' => 'images/g-event2.jpg',     'caption' => 'Cooking class with Layla — autumn 2023',          'cat' => 'events'],
  ['src' => 'images/g-koobideh.jpg',   'caption' => 'Koobideh lamb on flat skewers',                  'cat' => 'food'],
  ['src' => 'images/g-interior3.jpg',  'caption' => 'Hand-lettered menu board at the bar',             'cat' => 'interiors'],
  ['src' => 'images/g-icecream.jpg',   'caption' => 'Saffron and rosewater ice cream',                 'cat' => 'food'],
  ['src' => 'images/g-event3.jpg',     'caption' => 'Midsummer supper club on the terrace',            'cat' => 'events'],
];

require 'includes/header.php';
?>

<main>
  <div class="page-hero">
    <div class="container">
      <p class="section-label">Through the lens</p>
      <h1>Gallery</h1>
      <p>A look inside the kitchen and dining room — from weeknight dinners to seasonal events.</p>
    </div>
  </div>

  <section class="section">
    <div class="container">

      <!-- Filters -->
      <div class="menu-filters gallery-filters" role="group" aria-label="Filter gallery by category">
        <button class="filter-btn active" data-cat="all">All</button>
        <button class="filter-btn" data-cat="food">Food</button>
        <button class="filter-btn" data-cat="interiors">Interiors</button>
        <button class="filter-btn" data-cat="events">Events</button>
      </div>

      <!-- Masonry grid -->
      <div class="gallery-grid" id="gallery-grid" role="list" aria-label="Photo gallery">
        <?php foreach ($gallery as $i => $img): ?>
        <figure
          class="gallery-item"
          data-cat="<?= $img['cat'] ?>"
          data-index="<?= $i ?>"
          role="listitem"
          tabindex="0"
          aria-label="Open image: <?= htmlspecialchars($img['caption']) ?>"
        >
          <img
            src="<?= htmlspecialchars($img['src']) ?>"
            alt="<?= htmlspecialchars($img['caption']) ?>"
            loading="lazy"
            onerror="this.src='images/placeholder.jpg'"
          >
          <figcaption><?= htmlspecialchars($img['caption']) ?></figcaption>
        </figure>
        <?php endforeach; ?>
      </div>

      <!-- Video reel -->
      <div class="gallery-video reveal">
        <h3>An Evening at Saffron &amp; Salt</h3>
        <p style="color:var(--text-muted);margin-bottom:1.5rem;">
          A short film shot during our autumn supper club.
        </p>
        <div class="video-embed">
          <video
            controls
            poster="images/g-interior1.jpg"
            preload="none"
          >
            <source src="video/kitchen.mp4" type="video/mp4">
            <p style="padding:2rem;color:var(--text-muted);">
              Your browser does not support HTML5 video.
            </p>
          </video>
        </div>
      </div>

    </div>
  </section>
</main>

<!-- Lightbox -->
<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Image viewer">
  <button class="lightbox-close" id="lb-close" aria-label="Close image viewer">&times;</button>
  <button class="lightbox-prev" id="lb-prev" aria-label="Previous image">&#8249;</button>
  <img class="lightbox-img" id="lb-img" src="" alt="">
  <p class="lightbox-caption" id="lb-caption"></p>
  <button class="lightbox-next" id="lb-next" aria-label="Next image">&#8250;</button>
</div>

<script>
(function () {
  const items = <?= json_encode(array_values($gallery)) ?>;
  let current = 0;
  let visible = items.map((_, i) => i);

  const lightbox  = document.getElementById('lightbox');
  const lbImg     = document.getElementById('lb-img');
  const lbCaption = document.getElementById('lb-caption');
  const lbClose   = document.getElementById('lb-close');
  const lbPrev    = document.getElementById('lb-prev');
  const lbNext    = document.getElementById('lb-next');

  function openLightbox(index) {
    current = index;
    showImage();
    lightbox.classList.add('open');
    lbClose.focus();
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    lightbox.classList.remove('open');
    document.body.style.overflow = '';
  }

  function showImage() {
    const item = items[current];
    lbImg.src     = item.src;
    lbImg.alt     = item.caption;
    lbCaption.textContent = item.caption;
  }

  function navigate(dir) {
    const pos = visible.indexOf(current);
    const next = visible[(pos + dir + visible.length) % visible.length];
    current = next;
    showImage();
  }

  // Open on click or Enter key
  document.querySelectorAll('.gallery-item').forEach(fig => {
    fig.addEventListener('click', () => {
      const idx = parseInt(fig.dataset.index, 10);
      openLightbox(idx);
    });
    fig.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        const idx = parseInt(fig.dataset.index, 10);
        openLightbox(idx);
      }
    });
  });

  lbClose.addEventListener('click', closeLightbox);
  lbPrev.addEventListener('click', () => navigate(-1));
  lbNext.addEventListener('click', () => navigate(1));

  document.addEventListener('keydown', e => {
    if (!lightbox.classList.contains('open')) return;
    if (e.key === 'Escape')      closeLightbox();
    if (e.key === 'ArrowLeft')  navigate(-1);
    if (e.key === 'ArrowRight') navigate(1);
  });

  // Click outside image closes
  lightbox.addEventListener('click', e => {
    if (e.target === lightbox) closeLightbox();
  });

  // Category filter
  document.querySelectorAll('.gallery-filters .filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.gallery-filters .filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const cat = btn.dataset.cat;
      visible = [];
      document.querySelectorAll('.gallery-item').forEach(fig => {
        const match = cat === 'all' || fig.dataset.cat === cat;
        fig.style.display = match ? '' : 'none';
        if (match) visible.push(parseInt(fig.dataset.index, 10));
      });
    });
  });
})();
</script>

<?php require 'includes/footer.php'; ?>
