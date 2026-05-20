<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title ?? 'Saffron & Salt') ?> — Saffron &amp; Salt</title>
  <meta name="description" content="<?= htmlspecialchars($page_desc ?? 'A family-run Persian-Mediterranean restaurant in the heart of York. Rooted in tradition. Cooked with love.') ?>">
  <link rel="icon" href="<?= $root ?? '' ?>images/favicon.ico" type="image/x-icon">
  <!-- Google Fonts: Cormorant Garant (serif) and Inter (sans-serif)
       Source: https://fonts.google.com — free under the Open Font Licence -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garant:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $root ?? '' ?>css/style.css">
  <link rel="stylesheet" href="<?= $root ?? '' ?>css/responsive.css">
</head>
<body class="<?= htmlspecialchars($body_class ?? '') ?>">

<header class="site-header" id="site-header">
  <div class="header-inner">
    <a href="<?= $root ?? '' ?>index.php" class="logo" aria-label="Saffron &amp; Salt — Home">
      <span class="logo-main">Saffron &amp; Salt</span>
      <span class="logo-sub">York</span>
    </a>

    <button class="nav-toggle" id="nav-toggle" aria-controls="main-nav" aria-expanded="false" aria-label="Open navigation">
      <span></span><span></span><span></span>
    </button>

    <nav class="main-nav" id="main-nav" role="navigation" aria-label="Main navigation">
      <ul>
        <li><a href="<?= $root ?? '' ?>index.php"         class="<?= $current_page === 'index.php'        ? 'active' : '' ?>">Home</a></li>
        <li><a href="<?= $root ?? '' ?>menu.php"          class="<?= $current_page === 'menu.php'         ? 'active' : '' ?>">Menu</a></li>
        <li><a href="<?= $root ?? '' ?>reservations.php"  class="<?= $current_page === 'reservations.php' ? 'active' : '' ?>">Reserve</a></li>
        <li><a href="<?= $root ?? '' ?>gallery.php"       class="<?= $current_page === 'gallery.php'      ? 'active' : '' ?>">Gallery</a></li>
        <li><a href="<?= $root ?? '' ?>about.php"         class="<?= $current_page === 'about.php'        ? 'active' : '' ?>">Our Story</a></li>
        <li><a href="<?= $root ?? '' ?>contact.php"       class="<?= $current_page === 'contact.php'      ? 'active' : '' ?>">Contact</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="<?= $root ?? '' ?>account.php"     class="<?= $current_page === 'account.php'      ? 'active' : '' ?>">My Bookings</a></li>
          <li><a href="<?= $root ?? '' ?>handlers/auth.php?action=logout" class="nav-cta">Sign out</a></li>
        <?php else: ?>
          <li><a href="<?= $root ?? '' ?>login.php"       class="nav-cta <?= $current_page === 'login.php' ? 'active' : '' ?>">Sign in</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>
