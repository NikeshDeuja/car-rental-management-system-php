<?php
declare(strict_types=1);
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/auth.php";
require_role('client');

$stmt = $pdo->prepare("
  SELECT b.*, c.make, c.model, c.year
  FROM bookings b
  JOIN cars c ON c.id = b.car_id
  WHERE b.user_id=?
  ORDER BY b.created_at DESC
");
$stmt->execute([current_user_id()]);
$rows = $stmt->fetchAll();

$title = "My Bookings";
require_once __DIR__ . "/../includes/header.php";
?>

<div class="card">
  <div class="pad">
    <div class="row" style="justify-content:space-between">
      <div>
        <h1 class="h1">My Bookings</h1>
        <p class="p">All your confirmed bookings.</p>
      </div>
      <a class="btn" href="client/index.php">Book More</a>

    </div>

    <table class="table">
      <thead>
        <tr>
          <th>Car</th>
          <th>Dates</th>
          <th>Total (NRP)</th>
          <th>Status</th>
          <th>Booked at</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= e($r['make']." ".$r['model']." ".$r['year']) ?></td>
            <td><?= e($r['start_date']) ?> → <?= e($r['end_date']) ?></td>
            <td><?= number_format((float)$r['total_nrp'], 2) ?></td>
            <td><?= e($r['status']) ?></td>
            <td><?= e($r['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?>
          <tr><td colspan="5" class="small">No bookings yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
