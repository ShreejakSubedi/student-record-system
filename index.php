<?php
declare(strict_types=1);
// Root login page for the application
session_start();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/twig.php';
require_once __DIR__ . '/config/csrf.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: /fullstack2/student-record-system/public/index.php');
    exit;
}

$error = $_GET['error'] ?? '';

echo $twig->render('auth/login.html.twig', [
    'error' => $error,
    'username' => $_POST['username'] ?? '',
    'csrf_token' => CSRFToken::generate(),
    'csrf_token_name' => CSRFToken::name(),
]);
