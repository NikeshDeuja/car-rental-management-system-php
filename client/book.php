<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../includes/csrf.php";
require_role('client');

$carId = (int)($_GET['car_id'] ?? 0);
$start = get_string("start", 10);
$end = get_string("end", 10);

$stmt = $pdo->prepare("SELECT * FROM cars WHERE id=? AND is_active=1");
$stmt->execute([$carId]);
$car = $stmt->fetch();
if (!$car) { die("Car not found."); }

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_book'])) {
  csrf_verify();

  $start = post_string("start_date", 10);
  $end = post_string("end_date", 10);

  if ($start === "" || $end === "") {
    $error = "Please select start and end date.";
  } elseif ($end < $start) {
    $error = "End date must be after start date.";
  } else {
    // check overlap
    $check = $pdo->prepare("
      SELECT id FROM bookings
      WHERE car_id=? AND status='confirmed'
      AND NOT (end_date < ? OR start_date > ?)
      LIMIT 1
    ");
    $check->execute([$carId, $start, $end]);
    if ($check->fetch()) {
      $error = "This car is already booked for those dates.";
    } else {
      $days = days_between($start, $end);
      $total = $days * (float)$car['price_per_day_nrp'];

      $ins = $pdo->prepare("INSERT INTO bookings(user_id,car_id,start_date,end_date,total_nrp,status)
                            VALUES (?,?,?,?,?,'confirmed')");
      $ins->execute([current_user_id(), $carId, $start, $end, $total]);

      $success = "Booking confirmed ✅ Total: NRP " . number_format($total, 2);
    }
  }
}

$title = "Book Car";
require_once __DIR__ . "/../includes/header.php";
?>

<div class="card">
  <div class="pad" style="max-width:760px;margin:0 auto">
    <h1 class="h1">Book: <?= e($car['make']) ?> <?= e($car['model']) ?> (<?= (int)$car['year'] ?>)</h1>
    <p class="p">Price: <b>NRP <?= number_format((float)$car['price_per_day_nrp'], 2) ?>/day</b></p>

    <?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert"><?= e($success) ?></div><?php endif; ?>

    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="row">
        <div class="field" style="flex:1">
          <label>Start date</label>
          <input name="start_date" type="date" value="<?= e($start) ?>" required>
        </div>
        <div class="field" style="flex:1">
          <label>End date</label>
          <input name="end_date" type="date" value="<?= e($end) ?>" required>
        </div>
      </div>

      <div class="row" style="justify-content:space-between;margin-top:12px">
        <a class="btn btn-ghost" href="index.php">Cancel</a>



        <button class="btn" name="do_book" value="1" type="submit">Confirm Booking</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
