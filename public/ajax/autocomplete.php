<?php
declare(strict_types=1);
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../includes/functions.php";

header("Content-Type: application/json; charset=utf-8");

$q = get_string("q", 60);
if (strlen($q) < 2) {
  echo json_encode([]);
  exit;
}

$stmt = $pdo->prepare("
  SELECT DISTINCT CONCAT(make,' ',model) AS name
  FROM cars
  WHERE is_active = 1 AND (make LIKE ? OR model LIKE ?)
  ORDER BY name
  LIMIT 8
");
$like = "%{$q}%";
$stmt->execute([$like, $like]);

$out = [];
foreach ($stmt->fetchAll() as $row) $out[] = $row['name'];
echo json_encode($out);
