<?php
declare(strict_types=1);
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/auth.php";
require_role('admin');

$rows = $pdo->query("
  SELECT b.*, u.full_name, u.email, c.make, c.model, c.year
  FROM bookings b
  JOIN users u ON u.id=b.user_id
  JOIN cars c ON c.id=b.car_id
  ORDER BY b.created_at DESC
")->fetchAll();

$title = "Admin • Bookings";
require_once __DIR__ . "/../includes/header.php";
?>

<div class="card">
  <div class="pad">
    <div class="row" style="justify-content:space-between">
      <div>
        <h1 class="h1">Bookings</h1>
        <p class="p">All client bookings with details.</p>
      </div>
      <a class="btn btn-ghost" href="admin/index.php">Admin Home</a>

    </div>

    <table class="table">
      <thead>
        <tr>
          <th>ID</th><th>Client</th><th>Car</th><th>Dates</th><th>Total (NRP)</th><th>Status</th><th>Created</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= e($r['full_name']) ?><div class="small"><?= e($r['email']) ?></div></td>
            <td><?= e($r['make']." ".$r['model']." ".$r['year']) ?></td>
            <td><?= e($r['start_date']) ?> → <?= e($r['end_date']) ?></td>
            <td><?= number_format((float)$r['total_nrp'],2) ?></td>
            <td><?= e($r['status']) ?></td>
            <td><?= e($r['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?>
          <tr><td colspan="7" class="small">No bookings yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
