<?php
$page_title = 'Menu';
$page_desc  = 'Persian-Mediterranean dishes made from three generations of family recipes. Explore our full menu.';
$body_class = 'page-menu';
require 'includes/header.php';
require 'includes/db.php';

try {
    $stmt = get_db()->query("SELECT * FROM menu_items ORDER BY category, id");
    $all_items = $stmt->fetchAll();
} catch (PDOException $e) {
    $all_items = [];
}

// Group by category
$categories = [
    'starter' => ['label' => 'Mezze &amp; Starters', 'items' => []],
    'grill'   => ['label' => 'From the Grill',       'items' => []],
    'pot'     => ['label' => 'From the Pot',          'items' => []],
    'sweet'   => ['label' => 'Sweets &amp; Drinks',   'items' => []],
];
foreach ($all_items as $item) {
    if (array_key_exists($item['category'], $categories)) {
        $categories[$item['category']]['items'][] = $item;
    }
}
?>

<main>
  <!-- Page hero -->
  <div class="page-hero">
    <div class="container">
      <p class="section-label">What we cook</p>
      <h1>The Menu</h1>
      <p>You'll find traces of Tehran, the Lebanese coast and the Yorkshire Dales in every dish — family recipes refined across three generations, made each day from scratch.</p>
    </div>
  </div>

  <section class="section">
    <div class="container">

      <!-- Category filter buttons -->
      <div class="menu-filters" role="tablist" aria-label="Filter menu by category">
        <button class="filter-btn active" data-filter="all" role="tab" aria-selected="true">All</button>
        <button class="filter-btn" data-filter="starter" role="tab" aria-selected="false">Starters</button>
        <button class="filter-btn" data-filter="grill"   role="tab" aria-selected="false">From the Grill</button>
        <button class="filter-btn" data-filter="pot"     role="tab" aria-selected="false">From the Pot</button>
        <button class="filter-btn" data-filter="sweet"   role="tab" aria-selected="false">Sweets &amp; Drinks</button>
      </div>

      <!-- Menu sections -->
      <?php foreach ($categories as $key => $cat): if (empty($cat['items'])) continue; ?>
      <div class="menu-category" data-category="<?= $key ?>">
        <h2 class="menu-category-title"><?= $cat['label'] ?></h2>
        <div class="menu-grid">
          <?php foreach ($cat['items'] as $item): ?>
          <article class="menu-item reveal">
            <img
              class="menu-item-img"
              src="<?= htmlspecialchars($item['image'] ?? 'images/placeholder.jpg') ?>"
              alt="<?= htmlspecialchars($item['name']) ?>"
              loading="lazy"
              onerror="this.src='images/placeholder.jpg'"
            >
            <div class="menu-item-info">
              <h3 class="menu-item-name"><?= htmlspecialchars($item['name']) ?></h3>
              <p class="menu-item-desc"><?= htmlspecialchars($item['description']) ?></p>
              <div class="menu-item-footer">
                <span class="menu-item-price">&pound;<?= number_format($item['price'], 2) ?></span>
                <?php if (!empty($item['allergens'])): ?>
                <span class="allergen-tag" title="Contains: <?= htmlspecialchars($item['allergens']) ?>">
                  <?= htmlspecialchars($item['allergens']) ?>
                </span>
                <?php endif; ?>
              </div>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Allergens note -->
      <p style="margin-top:3rem;font-size:0.82rem;color:var(--text-muted);border-top:1px solid var(--border);padding-top:1.5rem;">
        Please inform your server of any allergies or dietary requirements before ordering. Full allergen information is available on request. Some dishes may contain traces of nuts, gluten or dairy even when not listed.
      </p>

    </div>
  </section>
</main>

<script>
// Category filter — show/hide menu sections and items
document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.filter-btn').forEach(b => {
      b.classList.remove('active');
      b.setAttribute('aria-selected', 'false');
    });
    btn.classList.add('active');
    btn.setAttribute('aria-selected', 'true');

    const filter = btn.dataset.filter;
    document.querySelectorAll('.menu-category').forEach(cat => {
      if (filter === 'all' || cat.dataset.category === filter) {
        cat.style.display = '';
      } else {
        cat.style.display = 'none';
      }
    });
  });
});
</script>

<?php require 'includes/footer.php'; ?>
