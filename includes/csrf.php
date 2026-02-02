<?php
// includes/csrf.php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function csrf_token(): string {
  if (empty($_SESSION['csrf_token'])) {
    $token = '';
    try {
        if (function_exists('random_bytes')) {
            $token = bin2hex(random_bytes(32));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(32);
            if ($bytes !== false) {
                $token = bin2hex($bytes);
            }
        }
    } catch (Throwable $e) {
        // ignore
    }
    
    if ($token === '') {
        $token = md5(uniqid((string)mt_rand(), true));
    }
    
    $_SESSION['csrf_token'] = $token;
  }
  return $_SESSION['csrf_token'];
}

function csrf_verify(): void {
  $token = $_POST['csrf_token'] ?? '';
  if (!$token || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
    http_response_code(403);
    die("CSRF check failed.");
  }
}
