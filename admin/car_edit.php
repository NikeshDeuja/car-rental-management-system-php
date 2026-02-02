<?php
declare(strict_types=1);
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../includes/csrf.php";
require_role('admin');

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id=?");
$stmt->execute([$id]);
$car = $stmt->fetch();
if (!$car) die("Car not found.");

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_car'])) {
  csrf_verify();

  $make = post_string("make",60);
  $model = post_string("model",60);
  $year = (int)($_POST['year'] ?? 0);
  $type = post_string("car_type",30);
  $color = post_string("color",30);
  $seats = (int)($_POST['seats'] ?? 4);
  $trans = post_string("transmission",20);
  $fuel = post_string("fuel",20);
  $price = (float)($_POST['price_per_day_nrp'] ?? 0);
  $active = isset($_POST['is_active']) ? 1 : 0;

  $imgPath = $car['image_path'];

  if (!empty($_FILES['image']['name'])) {
    $allowed = ['jpg','jpeg','png','webp'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) {
      $error = "Only JPG, PNG, WEBP allowed.";
    } else {
      $dir = __DIR__ . "/../assets/cars/";
      if (!is_dir($dir)) mkdir($dir, 0777, true);

      $safeName = "car_" . time() . "_" . random_int(1000,9999) . "." . $ext;
      $dest = $dir . $safeName;
      if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        $imgPath = "assets/cars/" . $safeName;
      } else {
        $error = "Upload failed.";
      }
    }
  }

  if ($error === "") {
    $upd = $pdo->prepare("UPDATE cars SET make=?,model=?,year=?,car_type=?,color=?,seats=?,transmission=?,fuel=?,price_per_day_nrp=?,image_path=?,is_active=? WHERE id=?");
    $upd->execute([$make,$model,$year,$type,$color,$seats,$trans,$fuel,$price,$imgPath,$active,$id]);
    redirect("../admin/cars.php");

  }
}

$title = "Edit Car";
require_once __DIR__ . "/../includes/header.php";
?>

<div class="card">
  <div class="pad" style="max-width:760px;margin:0 auto">
    <h1 class="h1">Edit Car #<?= (int)$car['id'] ?></h1>
    <?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="row">
        <div class="field" style="flex:1"><label>Make</label><input name="make" value="<?= e($car['make']) ?>" required></div>
        <div class="field" style="flex:1"><label>Model</label><input name="model" value="<?= e($car['model']) ?>" required></div>
      </div>

      <div class="row">
        <div class="field" style="flex:1"><label>Year</label><input name="year" type="number" value="<?= (int)$car['year'] ?>" required></div>
        <div class="field" style="flex:1"><label>Type</label><input name="car_type" value="<?= e($car['car_type']) ?>" required></div>
      </div>

      <div class="row">
        <div class="field" style="flex:1"><label>Color</label><input name="color" value="<?= e($car['color']) ?>" required></div>
        <div class="field" style="flex:1"><label>Seats</label><input name="seats" type="number" value="<?= (int)$car['seats'] ?>"></div>
      </div>

      <div class="row">
        <div class="field" style="flex:1"><label>Transmission</label><input name="transmission" value="<?= e($car['transmission']) ?>"></div>
        <div class="field" style="flex:1"><label>Fuel</label><input name="fuel" value="<?= e($car['fuel']) ?>"></div>
      </div>

      <div class="field"><label>Price/day (NRP)</label><input name="price_per_day_nrp" type="number" step="0.01" value="<?= e((string)$car['price_per_day_nrp']) ?>" required></div>

      <div class="field">
        <label>Replace image (optional)</label>
        <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp">
        <div class="small">Current: <?= $car['image_path'] ? e($car['image_path']) : "None" ?></div>
      </div>

      <div class="row">
        <label class="row" style="gap:8px">
          <input type="checkbox" name="is_active" <?= (int)$car['is_active'] ? "checked" : "" ?>>
          Active
        </label>
      </div>

      <div class="row" style="justify-content:space-between;margin-top:12px">
        <a class="btn btn-ghost" href="admin/cars.php">Back</a>

        <button class="btn" name="save_car" value="1" type="submit">Save</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
