<?php
declare(strict_types=1);
require_once __DIR__ . "/../includes/functions.php";

$title = "Forgot Password";
require_once __DIR__ . "/../includes/header.php";
?>

<div class="card" style="max-width:420px;margin:40px auto">
  <div class="pad">
    <h1 class="h1">Forgot Password</h1>
    <p class="p">
      Enter your registered email address.  
      For security reasons, password reset is handled by the administrator.
    </p>

    <form>
      <div class="field">
        <label>Email</label>
        <input type="email" placeholder="your@email.com" disabled>
      </div>

      <div class="alert">
        Please contact the system administrator to reset your password.
      </div>
    </form>

    <div class="row" style="justify-content:space-between;margin-top:14px">
      <a class="btn btn-ghost" href="public/login.php">

        ← Back to Login
      </a>
    </div>
  </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
