<?php

// Configure secure session cookie settings before starting the session.
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => (
        !empty($_SERVER['HTTPS']) &&
        $_SERVER['HTTPS'] !== 'off'
    ),
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

// Hide PHP version information from HTTP responses.
header_remove('X-Powered-By');
header('X-Content-Type-Options: nosniff');

header(
    "Content-Security-Policy: default-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'; object-src 'none'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net; style-src-attr 'unsafe-inline'; img-src 'self' data:; font-src 'self' https://cdn.jsdelivr.net data:; connect-src 'self'"
);

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function requireLogin()
{
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}