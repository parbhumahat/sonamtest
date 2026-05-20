<?php
$page_title = 'Home';
$page_desc  = 'A family-run Persian-Mediterranean restaurant in the heart of York. Rooted in tradition. Cooked with love.';
$body_class = 'page-home';
require 'includes/header.php';
require 'includes/db.php';

// Pull the 3 featured dishes for the specials strip
try {
    $stmt = get_db()->query("SELECT * FROM menu_items WHERE featured = 1 LIMIT 3");
    $specials = $stmt->fetchAll();
} catch (PDOException $e) {
    $specials = [];
}
?>

<!-- Hero -->
<section class="hero" aria-label="Welcome to Saffron &amp; Salt">
  <div class="hero-video-wrap">
    <video autoplay muted loop playsinline poster="images/hero-poster.jpg">
      <source src="video/kitchen.mp4" type="video/mp4">
      <!-- Fallback for browsers that don't support video -->
      <img src="images/hero-poster.jpg" alt="The Saffron &amp; Salt kitchen">
    </video>
  </div>
  <div class="hero-overlay"></div>

  <div class="hero-content">
    <p class="hero-eyebrow">York &bull; Est. 2012</p>
    <h1>Saffron &amp; Salt</h1>
    <p class="hero-tagline">Rooted in tradition. Cooked with love.</p>
    <div class="hero-actions">
      <a href="reservations.php" class="btn btn--fill">Reserve a Table</a>
      <a href="menu.php" class="btn">View the Menu</a>
    </div>
  </div>

  <p class="hero-status">
    <span class="status-dot" id="status-dot"></span>
    <span id="status-text">Checking hours...</span>
  </p>
</section>

<!-- Today's Specials -->
<?php if (!empty($specials)): ?>
<section class="specials" aria-label="Today's specials">
  <div class="specials-grid">
    <?php foreach ($specials as $i => $dish): ?>
    <article class="special-card">
      <p class="label">Chef's Pick <?= $i + 1 ?></p>
      <h3><?= htmlspecialchars($dish['name']) ?></h3>
      <p><?= htmlspecialchars($dish['description']) ?></p>
      <p class="price">&pound;<?= number_format($dish['price'], 2) ?></p>
    </article>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- Our Story teaser -->
<section class="section" aria-label="Our story">
  <div class="container">
    <div class="story-teaser reveal">
      <div class="story-teaser-text">
        <p class="section-label">Our Story</p>
        <h2>Three generations.<br>One kitchen.</h2>
        <p>
          What started as Maryam Shirazi's home kitchen in Tehran became a pop-up in York's Shambles Market,
          and eventually — after twenty years of Sunday lunches, late harvests and long conversations — a restaurant.
          Every dish here begins with her handwritten notebooks.
        </p>
        <a href="about.php" class="btn" style="margin-top:0.5rem">Read our story</a>
      </div>
      <div class="story-teaser-img">
        <img src="images/about-kitchen.jpg" alt="Layla Shirazi at work in the Saffron &amp; Salt kitchen" loading="lazy">
      </div>
    </div>
  </div>
</section>

<!-- Pull quote -->
<section class="section--tight" style="background:var(--midnight-2);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
  <div class="container text-center reveal">
    <blockquote style="font-family:var(--font-serif);font-size:clamp(1.4rem,3vw,2.2rem);font-style:italic;color:var(--cream-dark);max-width:700px;margin:0 auto;line-height:1.5;">
      &ldquo;The kind of food that reminds you why you eat out.&rdquo;
    </blockquote>
    <p style="margin-top:1.25rem;color:var(--text-muted);font-size:0.85rem;letter-spacing:0.1em;text-transform:uppercase;">— The Yorkshire Post, 2023</p>
  </div>
</section>

<!-- CTA strip -->
<section class="section--tight">
  <div class="container text-center reveal">
    <p class="section-label">Ready to visit?</p>
    <h2 style="margin-bottom:2rem;">We'd love to have you</h2>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
      <a href="reservations.php" class="btn btn--fill">Book a Table</a>
      <a href="contact.php" class="btn">Get in Touch</a>
    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>
