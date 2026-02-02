<?php
declare(strict_types=1);
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../includes/auth.php";
require_role('admin');

$rows = $pdo->query("SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC")->fetchAll();

$title = "Admin • Users";
require_once __DIR__ . "/../includes/header.php";
?>

<div class="card">
  <div class="pad">
    <div class="row" style="justify-content:space-between">
      <div>
        <h1 class="h1">Users</h1>
        <p class="p">View all login accounts (admin + clients).</p>
      </div>
      <a class="btn btn-ghost" href="admin/index.php">Admin Home</a>

    </div>

    <table class="table">
      <thead>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th></tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= e($r['full_name']) ?></td>
            <td><?= e($r['email']) ?></td>
            <td><?= e($r['role']) ?></td>
            <td><?= e($r['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
