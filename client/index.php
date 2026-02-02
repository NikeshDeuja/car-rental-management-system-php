<?php
declare(strict_types=1);
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/auth.php";
require_role('client');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_theme'])) {
  if (session_status() === PHP_SESSION_NONE) session_start();
  $_SESSION['theme'] = (($_SESSION['theme'] ?? 'light') === 'light') ? 'dark' : 'light';
  redirect("index.php");


}

$q = get_string("q", 60);
$type = get_string("type", 30);
$color = get_string("color", 30);
$start = get_string("start", 10);
$end = get_string("end", 10);

$params = [];
$where = "c.is_active = 1";

if ($q !== "") {
  $where .= " AND (c.make LIKE ? OR c.model LIKE ?)";
  $like = "%{$q}%";
  $params[] = $like; $params[] = $like;
}
if ($type !== "") { $where .= " AND c.car_type = ?"; $params[] = $type; }
if ($color !== "") { $where .= " AND c.color = ?"; $params[] = $color; }

// Availability: exclude cars that have overlapping bookings
$availSQL = "";
if ($start !== "" && $end !== "") {
  $availSQL = " AND c.id NOT IN (
      SELECT b.car_id FROM bookings b
      WHERE b.status='confirmed'
      AND NOT (b.end_date < ? OR b.start_date > ?)
    )";
  $params[] = $start;
  $params[] = $end;
}

$stmt = $pdo->prepare("SELECT c.* FROM cars c WHERE {$where} {$availSQL} ORDER BY c.created_at DESC");
$stmt->execute($params);
$cars = $stmt->fetchAll();

$title = "Client • Cars";
require_once __DIR__ . "/../includes/header.php";
?>

<div class="grid">
  <div class="card">
    <div class="pad">
      <h2 class="h1">Filters</h2>
      <p class="p">Search + dates + type + color</p>

      <form method="get">
        <div class="field">
          <label>Search car</label>
          <input name="q" value="<?= e($q) ?>" placeholder="Toyota, Civic...">
        </div>

        <div class="row">
          <div class="field" style="flex:1">
            <label>Start date</label>
            <input name="start" type="date" value="<?= e($start) ?>">
          </div>
          <div class="field" style="flex:1">
            <label>End date</label>
            <input name="end" type="date" value="<?= e($end) ?>">
          </div>
        </div>

        <div class="row">
          <div class="field" style="flex:1">
            <label>Type</label>
            <select name="type">
              <option value="">Any</option>
              <?php foreach (["SUV","Sedan","Hatchback","Van","Pickup"] as $t): ?>
                <option value="<?= e($t) ?>" <?= $type===$t?'selected':'' ?>><?= e($t) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field" style="flex:1">
            <label>Color</label>
            <select name="color">
              <option value="">Any</option>
              <?php foreach (["Black","White","Grey","Silver","Blue","Red","Brown"] as $c): ?>
                <option value="<?= e($c) ?>" <?= $color===$c?'selected':'' ?>><?= e($c) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="row" style="justify-content:space-between;margin-top:8px">
          <a class="btn btn-ghost" href="client/bookings.php">My Bookings</a>

          <button class="btn" type="submit">Apply</button>
        </div>
      </form>
    </div>
  </div>

  <div>
    <div class="row" style="justify-content:space-between;margin-bottom:12px">
      <div>
        <h2 class="h1"><?= count($cars) ?> cars to rent</h2>
        <p class="p">Prices shown in <b>NRP / day</b></p>
      </div>
    </div>

    <div class="car-grid">
      <?php foreach ($cars as $car): ?>
        <div class="car">
          <div class="car-img">
            <?php if (!empty($car['image_path'])): ?>
              <img src="<?= e($car['image_path']) ?>">
            <?php else: ?>
              <div class="small">No image</div>
            <?php endif; ?>
          </div>

          <div class="car-body">
            <div class="badges">
              <span class="badge ok">Available</span>
              <span class="badge"><?= e($car['car_type']) ?></span>
              <span class="badge"><?= e($car['color']) ?></span>
            </div>

            <div style="margin-top:10px;font-weight:900;font-size:16px">
              <?= e($car['make']) ?> <?= e($car['model']) ?>, <?= (int)$car['year'] ?>
            </div>

            <div class="price">
              NRP <?= number_format((float)$car['price_per_day_nrp'], 2) ?>
              <span class="small">/ day</span>
            </div>

            <div class="small">
              <?= (int)$car['seats'] ?> seats • <?= e($car['transmission']) ?> • <?= e($car['fuel']) ?>
            </div>

            <div class="row" style="justify-content:space-between;margin-top:12px">
              <a class="btn btn-ghost"
                 href="client/book.php?car_id=<?= (int)$car['id'] ?>&start=<?= e($start) ?>&end=<?= e($end) ?>">
                 Book
              </a>
              <span class="small">ID: <?= (int)$car['id'] ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
