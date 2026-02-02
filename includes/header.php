<?php
declare(strict_types=1);
require_once __DIR__ . "/functions.php";

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!doctype html>
<html lang="en">
<head>
  <!-- GLOBAL BASE URL -->
  <base href="/~NP03CS4A240352/car-rental/">

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? "Starcar Car Rental System") ?></title>

  <link rel="stylesheet" href="assets/css/style.css">
  <script defer src="assets/js/app.js"></script>
</head>

<body>
<header class="topbar">
  <div class="brand">
    <div class="logo">🚗</div>
    <div>
      <div class="brand-name">Starcar</div>
      <div class="brand-sub">Car Rental System</div>
    </div>
  </div>

  <div class="top-actions">
    <?php if (!empty($_SESSION['user'])): ?>
      <span class="user-pill">
        <?= e($_SESSION['user']['full_name']) ?>
        (<?= e($_SESSION['user']['role']) ?>)
      </span>

      <a class="btn btn-ghost"
         href="public/logout.php"
         onclick="return confirm('Are you sure you want to logout?')">
        Logout
      </a>
    <?php else: ?>
      <a class="btn btn-ghost" href="public/login.php">Login</a>
      <a class="btn" href="public/register.php">Sign up</a>
    <?php endif; ?>
  </div>
</header>

<main class="container">
