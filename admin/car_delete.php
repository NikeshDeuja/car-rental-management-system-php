<?php
declare(strict_types=1);
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/auth.php";
require_role('admin');

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
  $stmt = $pdo->prepare("DELETE FROM cars WHERE id=?");
  $stmt->execute([$id]);
}
header("Location: ../admin/cars.php");

exit;
