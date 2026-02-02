<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_OFF); // ignore if you don't use mysqli

$DB_HOST = "localhost";   // usually localhost
$DB_NAME = "NP03CS4A240352";
$DB_USER = "NP03CS4A240352";
$DB_PASS = "qx3kPKjPLF";

try {
  $pdo = new PDO(
    "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]
  );
} catch (PDOException $e) {
  // Production: Log error and show generic message
  error_log("Database Error: " . $e->getMessage());
  http_response_code(500);
  die("Database connection failed. Please check configuration.");
}
