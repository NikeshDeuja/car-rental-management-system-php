<?php
// includes/functions.php
declare(strict_types=1);

function e(string $str): string {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function post_string(string $key, int $max = 200): string {
  $v = trim((string)($_POST[$key] ?? ''));
  if (strlen($v) > $max) $v = substr($v, 0, $max);
  return $v;
}

function get_string(string $key, int $max = 200): string {
  $v = trim((string)($_GET[$key] ?? ''));
  if (strlen($v) > $max) $v = substr($v, 0, $max);
  return $v;
}

function days_between(string $start, string $end): int {
  $s = new DateTime($start);
  $e = new DateTime($end);
  $diff = $s->diff($e);
  return max(1, (int)$diff->days + 1); // inclusive days
}

function redirect(string $path): void {
  header("Location: {$path}");
  exit;
}
