<?php
declare(strict_types=1);
session_start();

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /fullstack2/student-record-system/');
    exit;
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/csrf.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\User;
use App\Controllers\AuthController;

// Verify CSRF token
$token = $_POST[CSRFToken::name()] ?? '';
if (!CSRFToken::verify($token)) {
    header('Location: /fullstack2/student-record-system/?error=Invalid+request');
    exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

try {
    $userModel = new User($pdo);
    $authController = new AuthController($userModel);
    
    $result = $authController->login($username, $password);
    if ($result['success']) {
        header('Location: /fullstack2/student-record-system/public/index.php');
        exit;
    }
} catch (Throwable $e) {
    error_log('Login error: ' . $e->getMessage());
}

// Invalid credentials or error
header('Location: /fullstack2/student-record-system/?error=Invalid+credentials');
exit;
