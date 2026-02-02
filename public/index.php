<?php
declare(strict_types=1);

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/auth.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_theme'])) {
  if (session_status() === PHP_SESSION_NONE) session_start();
  $_SESSION['theme'] = (($_SESSION['theme'] ?? 'light') === 'light') ? 'dark' : 'light';

  // ✅ FIXED redirect (NO hardcoded path)
  redirect("public/index.php");
}

$title = "Starcar • Home";
require_once __DIR__ . "/../includes/header.php";
?>

<div class="card">
  <div class="pad">
    <h1 class="h1">Welcome to Starcar</h1>
    <p class="p">Client can book cars. Admin can manage cars, users and bookings.</p>

    <?php if (!is_logged_in()): ?>
      <div class="row">
        <!-- ✅ FIXED LINKS -->
        <a class="btn" href="public/login.php">Login</a>
        <a class="btn btn-ghost" href="public/register.php">Create Client Account</a>
        <a class="btn btn-ghost" href="public/seed.php">Insert Demo Data (run once)</a>
      </div>

      <p class="small" style="margin-top:10px">
        After seeding, admin login will be:
        <b>admin@starcar.com</b> / <b>admin123</b>
      </p>

    <?php else: ?>
      <div class="alert">
        You are logged in. Go to:

        <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
          <a class="btn" href="admin/index.php" style="margin-left:8px">
            Admin Dashboard
          </a>
        <?php else: ?>
          <a class="btn" href="client/index.php" style="margin-left:8px">
            Client Dashboard
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
