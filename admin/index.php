<?php
declare(strict_types=1);
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/auth.php";
require_role('admin');

$cars = (int)$pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn();
$users = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$bookings = (int)$pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();

$title = "Admin • Dashboard";
require_once __DIR__ . "/../includes/header.php";
?>

<div class="row" style="justify-content:space-between;margin-bottom:16px">
  <div>
    <h1 class="h1">Admin Dashboard</h1>
    <p class="p">Manage cars, users and bookings</p>
  </div>
</div>

<div class="car-grid">
  <div class="car">
    <div class="car-body">
      <div class="badge ok">Total Cars</div>
      <div class="price"><?= $cars ?></div>
      <a class="btn" href="admin/cars.php">Manage Cars</a>
    </div>
  </div>

  <div class="car">
    <div class="car-body">
      <div class="badge">Total Users</div>
      <div class="price"><?= $users ?></div>
      <a class="btn" href="admin/users.php">View Users</a>
    </div>
  </div>

  <div class="car">
    <div class="car-body">
      <div class="badge">Total Bookings</div>
      <div class="price"><?= $bookings ?></div>
      <a class="btn" href="admin/bookings.php">View Bookings</a>
    </div>
  </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
