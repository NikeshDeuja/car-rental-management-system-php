<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/csrf.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_verify();

  $email = strtolower(post_string('email', 150));
  $pass  = $_POST['password'] ?? '';

  $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if (!$user || !password_verify($pass, $user['password_hash'])) {
    $error = "Invalid email or password.";
  } else {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $_SESSION['user'] = $user;

    if ($user['role'] === 'admin') {
      header("Location: ../admin/index.php");
    } else {
      header("Location: ../client/index.php");
    }

    exit;
  }
}

$title = "Login";
require_once __DIR__ . "/../includes/header.php";
?>

<div class="card" style="max-width:420px;margin:40px auto">
  <div class="pad">
    <h1 class="h1">Login</h1>
    <p class="p">Login as client or admin</p>

    <?php if ($error): ?>
      <div class="alert danger"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="field">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>

      <div class="field">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>

      <a class="small" href="public/forgot_password.php">Forgot password?</a>


      <div class="row" style="justify-content:space-between;margin-top:12px">
        <a class="btn btn-ghost" href="public/register.php">Create account</a>

        <button class="btn">Login</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
