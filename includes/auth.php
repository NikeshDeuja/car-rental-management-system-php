<?php
// includes/auth.php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function is_logged_in(): bool {
  return !empty($_SESSION['user']);
}

function require_login(): void {
  if (!is_logged_in()) {
    header("Location: ../public/login.php");

    exit;
  }
}

function require_role(string $role): void {
  require_login();
  if (($_SESSION['user']['role'] ?? '') !== $role) {
    http_response_code(403);
    die("Access denied.");
  }
}

function current_user_id(): int {
  return (int)($_SESSION['user']['id'] ?? 0);
}
