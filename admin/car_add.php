<?php
declare(strict_types=1);

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../includes/csrf.php";

require_role('admin');

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_car'])) {
  csrf_verify();

  $make  = post_string('make', 60);
  $model = post_string('model', 60);
  $year  = (int)($_POST['year'] ?? 0);
  $type  = post_string('car_type', 30);
  $color = post_string('color', 30);
  $seats = (int)($_POST['seats'] ?? 4);
  $trans = post_string('transmission', 20);
  $fuel  = post_string('fuel', 20);
  $price = (float)($_POST['price_per_day_nrp'] ?? 0);

  if ($make === "" || $model === "" || $year < 1990 || $price <= 0) {
    $error = "Please enter valid car details.";
  } else {

    // IMAGE UPLOAD
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
      $allowed = ['jpg', 'jpeg', 'png', 'webp'];
      $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

      if (!in_array($ext, $allowed, true)) {
        $error = "Only JPG, PNG, or WEBP images allowed.";
      } else {
        $dir = __DIR__ . "/../assets/cars/";
        if (!is_dir($dir)) {
          mkdir($dir, 0777, true);
        }

        $fileName = "car_" . time() . "_" . rand(1000, 9999) . "." . $ext;
        $target = $dir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
          $imagePath = "assets/cars/" . $fileName;
        } else {
          $error = "Image upload failed.";
        }
      }
    }

    if ($error === "") {
      $stmt = $pdo->prepare("
        INSERT INTO cars 
        (make, model, year, car_type, color, seats, transmission, fuel, price_per_day_nrp, image_path, is_active)
        VALUES (?,?,?,?,?,?,?,?,?,?,1)
      ");
      $stmt->execute([
        $make, $model, $year, $type, $color,
        $seats, $trans, $fuel, $price, $imagePath
      ]);

      header("Location: ../admin/cars.php?added=1");

      exit;
    }
  }
}

$title = "Admin • Add Car";
require_once __DIR__ . "/../includes/header.php";
?>

<div class="card" style="max-width:720px;margin:30px auto">
  <div class="pad">
    <h1 class="h1">Add New Car</h1>
    <p class="p">Enter car details and upload an image</p>

    <?php if ($error): ?>
      <div class="alert danger"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="row">
        <div class="field" style="flex:1">
          <label>Make</label>
          <input name="make" required>
        </div>
        <div class="field" style="flex:1">
          <label>Model</label>
          <input name="model" required>
        </div>
      </div>

      <div class="row">
        <div class="field" style="flex:1">
          <label>Year</label>
          <input type="number" name="year" min="1990" max="2035" required>
        </div>
        <div class="field" style="flex:1">
          <label>Type</label>
          <select name="car_type">
            <option>SUV</option>
            <option>Sedan</option>
            <option>Hatchback</option>
            <option>Van</option>
            <option>Pickup</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="field" style="flex:1">
          <label>Color</label>
          <input name="color" required>
        </div>
        <div class="field" style="flex:1">
          <label>Seats</label>
          <input type="number" name="seats" value="5">
        </div>
      </div>

      <div class="row">
        <div class="field" style="flex:1">
          <label>Transmission</label>
          <input name="transmission" value="Automatic">
        </div>
        <div class="field" style="flex:1">
          <label>Fuel</label>
          <input name="fuel" value="Petrol">
        </div>
      </div>

      <div class="field">
        <label>Price per day (NRP)</label>
        <input type="number" step="0.01" name="price_per_day_nrp" required>
      </div>

      <div class="field">
        <label>Car Image</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
      </div>

      <div class="row" style="justify-content:space-between;margin-top:14px">
        <a class="btn btn-ghost" href="admin/cars.php">← Back</a>

        <button class="btn" name="add_car" value="1">Add Car</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
