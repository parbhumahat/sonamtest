<?php
$page_title = 'My Bookings';
$page_desc  = 'View and manage your reservations at Saffron &amp; Salt.';
$body_class = 'page-account';
require 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'includes/db.php';

$user_id = (int) $_SESSION['user_id'];
$flash   = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Fetch user details
try {
    $stmt = get_db()->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    $user = ['name' => 'Guest', 'email' => ''];
}

// Handle cancellation POST (non-JS fallback)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {
    $cancel_id = (int) $_POST['cancel_id'];
    try {
        $upd = get_db()->prepare(
            "UPDATE reservations SET status = 'cancelled' WHERE id = ? AND email = ? AND date >= CURDATE()"
        );
        $upd->execute([$cancel_id, $user['email']]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Your reservation has been cancelled.'];
    } catch (PDOException $e) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Could not cancel that reservation.'];
    }
    header('Location: account.php');
    exit;
}

// Fetch reservations
try {
    $stmt = get_db()->prepare(
        "SELECT * FROM reservations WHERE email = ? ORDER BY date DESC, time DESC"
    );
    $stmt->execute([$user['email']]);
    $reservations = $stmt->fetchAll();
} catch (PDOException $e) {
    $reservations = [];
}
?>

<main>
  <div class="page-hero">
    <div class="container">
      <p class="section-label">Welcome back</p>
      <h1><?= htmlspecialchars($user['name']) ?></h1>
      <p><?= htmlspecialchars($user['email']) ?></p>
    </div>
  </div>

  <section class="section">
    <div class="container">

      <?php if ($flash): ?>
      <div class="form-message <?= htmlspecialchars($flash['type']) ?>" style="display:block;max-width:700px;margin:0 auto 2rem;">
        <?= htmlspecialchars($flash['message']) ?>
      </div>
      <?php endif; ?>

      <div id="cancel-msg" class="form-message" role="alert" aria-live="polite" style="max-width:700px;margin:0 auto 2rem;"></div>

      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <h2>Your reservations</h2>
        <div style="display:flex;gap:1rem;flex-wrap:wrap;">
          <a href="reservations.php" class="btn btn--fill">Book a table</a>
          <a href="handlers/auth.php?action=logout" class="btn">Sign out</a>
        </div>
      </div>

      <?php if (empty($reservations)): ?>
      <div style="text-align:center;padding:4rem;color:var(--text-muted);">
        <p style="font-size:1.1rem;margin-bottom:1.5rem;">No reservations yet.</p>
        <a href="reservations.php" class="btn btn--fill">Reserve a table</a>
      </div>
      <?php else: ?>
      <div style="overflow-x:auto;">
        <table class="bookings-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Time</th>
              <th>Guests</th>
              <th>Notes</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservations as $res): ?>
            <?php
              $is_future     = strtotime($res['date']) >= strtotime('today');
              $is_confirmed  = $res['status'] === 'confirmed';
              $formatted_date = date('D j M Y', strtotime($res['date']));
              $formatted_time = date('g:ia', strtotime($res['time']));
            ?>
            <tr>
              <td><?= htmlspecialchars($formatted_date) ?></td>
              <td><?= htmlspecialchars($formatted_time) ?></td>
              <td><?= (int)$res['guests'] ?></td>
              <td style="max-width:200px;font-size:0.82rem;"><?= htmlspecialchars($res['notes'] ?? '—') ?></td>
              <td><span class="badge badge--<?= $res['status'] ?>"><?= ucfirst($res['status']) ?></span></td>
              <td>
                <?php if ($is_future && $is_confirmed): ?>
                <button
                  class="btn btn--terra"
                  style="padding:0.4rem 0.9rem;font-size:0.78rem;"
                  data-id="<?= (int)$res['id'] ?>"
                  onclick="cancelReservation(<?= (int)$res['id'] ?>, this)"
                >
                  Cancel
                </button>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>

    </div>
  </section>
</main>

<script>
async function cancelReservation(id, btn) {
  if (!confirm('Cancel this reservation? This cannot be undone.')) return;

  btn.textContent = 'Cancelling…';
  btn.disabled    = true;

  const msgBox = document.getElementById('cancel-msg');

  try {
    const body = new FormData();
    body.append('id', id);
    const res  = await fetch('handlers/auth.php?action=cancel', { method: 'POST', body });
    const data = await res.json();

    msgBox.className   = 'form-message ' + (data.success ? 'success' : 'error');
    msgBox.textContent = data.message;

    if (data.success) {
      const row = btn.closest('tr');
      row.querySelector('.badge').className  = 'badge badge--cancelled';
      row.querySelector('.badge').textContent = 'Cancelled';
      btn.remove();
    }
  } catch {
    msgBox.className   = 'form-message error';
    msgBox.textContent = 'Could not cancel that reservation. Please call us on 01904 123 456.';
    btn.textContent    = 'Cancel';
    btn.disabled       = false;
  }

  msgBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
</script>

<?php require 'includes/footer.php'; ?>
