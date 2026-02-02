<?php
declare(strict_types=1);
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../includes/csrf.php";
require_role('admin');

$cars = $pdo->query("SELECT * FROM cars ORDER BY created_at DESC")->fetchAll();

$title = "Admin • Cars";
require_once __DIR__ . "/../includes/header.php";
?>

<div class="row" style="justify-content:space-between;margin-bottom:12px">
  <div>
    <h1 class="h1">Manage Cars</h1>
    <p class="p">Add, edit or remove cars</p>
  </div>
  <a class="btn" href="admin/car_add.php">+ Add Car</a>

</div>

<div class="car-grid">
<?php foreach ($cars as $car): ?>
  <div class="car">
    <div class="car-img">
      <?php if ($car['image_path']): ?>
        <img src="<?= e($car['image_path']) ?>" alt="car">

      <?php else: ?>
        <div class="small">No image</div>
      <?php endif; ?>
    </div>

    <div class="car-body">
      <div class="badges">
        <span class="badge"><?= e($car['car_type']) ?></span>
        <span class="badge"><?= e($car['color']) ?></span>
      </div>

      <div style="margin-top:10px;font-weight:900">
        <?= e($car['make']) ?> <?= e($car['model']) ?> (<?= (int)$car['year'] ?>)
      </div>

      <div class="price">
        NRP <?= number_format((float)$car['price_per_day_nrp'], 2) ?>
        <span class="small">/ day</span>
      </div>

      <div class="row" style="justify-content:space-between;margin-top:10px">
        <a class="btn btn-ghost" href="admin/car_edit.php?id=<?= (int)$car['id'] ?>">Edit</a>
        <a class="btn btn-ghost"
           href="admin/car_delete.php?id=<?= (int)$car['id'] ?>"
           onclick="return confirm('Delete this car?')">Delete</a>

      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
