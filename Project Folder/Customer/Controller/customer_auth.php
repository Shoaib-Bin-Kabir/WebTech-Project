<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$loginUrl = '../../Login and Signup/View/login.php';

$cookieName = 'customer_auth';
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

function customer_force_logout(string $loginUrl, string $cookieName, bool $isHttps): void
{
    $_SESSION = [];

    setcookie($cookieName, '', [
        'expires'  => time() - 42000,
        'path'     => '/',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_destroy();

    header('Location: ' . $loginUrl);
    exit();
}


if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header('Location: ' . $loginUrl);
    exit();
}
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Customer') {
    header('Location: ' . $loginUrl);
    exit();
}

// Cookie must exist (expires automatically after 30 days from login)
if (!isset($_COOKIE[$cookieName]) || $_COOKIE[$cookieName] !== '1') {
    customer_force_logout($loginUrl, $cookieName, $isHttps);
}
