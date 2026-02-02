<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/csrf.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_verify();

  $name  = post_string('name', 100);
  $email = post_string('email', 150);
  $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

  if ($name && $email) {
    $stmt = $pdo->prepare(
      "INSERT INTO users (full_name,email,password_hash,role)
       VALUES (?,?,?,'client')"
    );
    $stmt->execute([$name,$email,$pass]);

    header("Location: login.php");
    exit;
  } else {
    $error = "All fields are required.";
  }
}

$title = "Register";
require_once __DIR__ . "/../includes/header.php";
?>

<div class="card" style="max-width:420px;margin:40px auto">
  <div class="pad">
    <h1 class="h1">Create Account</h1>

    <?php if ($error): ?>
      <div class="alert danger"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="field">
        <label>Name</label>
        <input name="name" required>
      </div>

      <div class="field">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>

      <div class="field">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>

      <div class="row" style="justify-content:space-between">
        <a class="btn btn-ghost" href="login.php">Back to Login</a>
        <button class="btn">Register</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
